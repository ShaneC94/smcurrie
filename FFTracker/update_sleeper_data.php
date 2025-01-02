<?php
// Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the centralized configuration
require_once 'db_config.php';



// Basic fetch function
function get_data($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL error: " . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}

// Insert/update player only if not exists
function insert_player_if_missing($conn, $player_id, $player_data = null) {
    $player_id = strtoupper(trim($player_id));
    if (empty($player_id)) return;

    if ($player_data && isset($player_data['full_name'])) {
        $full_name = $player_data['full_name'];
        $first_name = $player_data['first_name'] ?? "";
        $last_name = $player_data['last_name'] ?? "";
    } else {
        // Generic defense fallback
        $full_name = $player_id . " Defense";
        $first_name = $player_id;
        $last_name = "Defense";
    }

    $stmt = $conn->prepare("INSERT INTO players (player_id, full_name, first_name, last_name)
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE 
                            full_name=VALUES(full_name), first_name=VALUES(first_name), last_name=VALUES(last_name)");
    $stmt->bind_param("ssss", $player_id, $full_name, $first_name, $last_name);
    $stmt->execute();
    $stmt->close();
}

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// 1. Insert/Update League
$league_data = get_data("https://api.sleeper.app/v1/league/$league_id");
if ($league_data) {
    $stmt = $conn->prepare("INSERT INTO leagues (league_id, name, total_rosters, status)
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE name=VALUES(name), total_rosters=VALUES(total_rosters), status=VALUES(status)");
    $stmt->bind_param("ssis", $league_id, $league_data['name'], $league_data['total_rosters'], $league_data['status']);
    $stmt->execute();
    $stmt->close();
}

// 2. Fetch users for team names
$users_data = get_data("https://api.sleeper.app/v1/league/$league_id/users");
$user_names = [];
if ($users_data) {
    foreach ($users_data as $user) {
        $user_names[$user['user_id']] = $user['display_name'];
    }
}

// 3. Insert/Update Teams
$roster_data = get_data("https://api.sleeper.app/v1/league/$league_id/rosters");
if ($roster_data) {
    foreach ($roster_data as $team) {
        $team_id = $team['roster_id'] ?? null;
        if (!$team_id) continue;

        $owner_id = $team['owner_id'] ?? "";
        $team_name = $user_names[$owner_id] ?? "Unknown Team";
        $points = $team['settings']['fpts'] ?? 0.0;

        $stmt = $conn->prepare("INSERT INTO teams (team_id, league_id, owner_id, team_name, points)
                                VALUES (?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE team_name=VALUES(team_name), points=VALUES(points)");
        $stmt->bind_param("ssssd", $team_id, $league_id, $owner_id, $team_name, $points);
        $stmt->execute();
        $stmt->close();
    }
}

// 4. Fetch All Players and Insert if Missing
$players_data = get_data("https://api.sleeper.app/v1/players/nfl");
if ($players_data) {
    foreach ($players_data as $pid => $pinfo) {
        insert_player_if_missing($conn, $pid, $pinfo);
    }
}

// 5. Insert/Update Roster Changes for each week
for ($week = 1; $week <= 17; $week++) {
    $transactions = get_data("https://api.sleeper.app/v1/league/$league_id/transactions/$week");
    if ($transactions) {
        foreach ($transactions as $transaction) {
            $team_ids = $transaction['roster_ids'] ?? [];
            $team_id = $team_ids[0] ?? null;
            $timestamp = date("Y-m-d H:i:s", (int)($transaction['created'] / 1000));

            if ($team_id) {
                // Adds
                foreach ($transaction['adds'] ?? [] as $add_pid => $status) {
                    $add_pid = strtoupper(trim($add_pid));
                    if (isset($players_data[$add_pid])) {
                        insert_player_if_missing($conn, $add_pid, $players_data[$add_pid]);
                    } else {
                        insert_player_if_missing($conn, $add_pid, null);
                    }

                    $stmt = $conn->prepare("INSERT IGNORE INTO roster_changes (league_id, team_id, player_id, change_type, timestamp, week)
                                            VALUES (?, ?, ?, 'add', ?, ?)");
                    $stmt->bind_param("ssssi", $league_id, $team_id, $add_pid, $timestamp, $week);
                    $stmt->execute();
                    $stmt->close();
                }

                // Drops
                foreach ($transaction['drops'] ?? [] as $drop_pid => $status) {
                    $drop_pid = strtoupper(trim($drop_pid));
                    if (isset($players_data[$drop_pid])) {
                        insert_player_if_missing($conn, $drop_pid, $players_data[$drop_pid]);
                    } else {
                        insert_player_if_missing($conn, $drop_pid, null);
                    }

                    $stmt = $conn->prepare("INSERT IGNORE INTO roster_changes (league_id, team_id, player_id, change_type, timestamp, week)
                                            VALUES (?, ?, ?, 'drop', ?, ?)");
                    $stmt->bind_param("ssssi", $league_id, $team_id, $drop_pid, $timestamp, $week);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }
}

// 6. Insert/Update Matchups incrementally for Weeks 1-17
for ($week = 1; $week <= 17; $week++) {
    $matchups = get_data("https://api.sleeper.app/v1/league/$league_id/matchups/$week");
    if ($matchups) {
        foreach ($matchups as $matchup) {
            $mteam_id = $matchup['roster_id'] ?? null;
            if (!$mteam_id) continue;
            $points = $matchup['points'] ?? 0.0;

            // Update points dynamically
            $stmt = $conn->prepare("
                INSERT INTO matchups (league_id, week, team_id, points)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE points = VALUES(points)
            ");
            $stmt->bind_param("ssis", $league_id, $week, $mteam_id, $points);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// 7. Calculate W-L-T records for each team up to each week and update matchups
for ($week = 1; $week <= 17; $week++) {
    // Fetch all matchups up to this week
    $sql = "
        SELECT m.week, m.matchup_id, m.team_id, m.points
        FROM matchups m
        WHERE m.league_id = '$league_id'
        AND m.week <= $week
        ORDER BY m.week ASC, m.matchup_id ASC, m.points DESC
    ";
    $res = $conn->query($sql);

    $matchups_all = [];
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $matchups_all[$row['week']][$row['matchup_id']][] = $row;
        }
    }

    $team_stats = [];
    foreach ($matchups_all as $w => $mups) {
        foreach ($mups as $mid => $teams) {
            if (count($teams) == 2) {
                $a = $teams[0];
                $b = $teams[1];

                if (!isset($team_stats[$a['team_id']])) {
                    $team_stats[$a['team_id']] = ['wins' => 0, 'losses' => 0, 'ties' => 0];
                }
                if (!isset($team_stats[$b['team_id']])) {
                    $team_stats[$b['team_id']] = ['wins' => 0, 'losses' => 0, 'ties' => 0];
                }

                if (floatval($a['points']) > floatval($b['points'])) {
                    $team_stats[$a['team_id']]['wins']++;
                    $team_stats[$b['team_id']]['losses']++;
                } elseif (floatval($b['points']) > floatval($a['points'])) {
                    $team_stats[$b['team_id']]['wins']++;
                    $team_stats[$a['team_id']]['losses']++;
                } else {
                    $team_stats[$a['team_id']]['ties']++;
                    $team_stats[$b['team_id']]['ties']++;
                }
            }
        }
    }

    $update_stmt = $conn->prepare("
        INSERT INTO matchups (league_id, week, team_id, points, wins, losses, ties)
        VALUES (?, ?, ?, 0, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            wins = VALUES(wins),
            losses = VALUES(losses),
            ties = VALUES(ties)
    ");
    foreach ($team_stats as $tid => $stats) {
        $wins = $stats['wins'] ?? 0;
        $losses = $stats['losses'] ?? 0;
        $ties = $stats['ties'] ?? 0;

        $update_stmt->bind_param("ssiiii", $league_id, $week, $tid, $wins, $losses, $ties);
        $update_stmt->execute();
    }
    $update_stmt->close();
}

echo "All Sleeper data successfully updated!";
$conn->close();
