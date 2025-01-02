<?php
// Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the centralized configuration
require_once 'db_config.php';

// Determine the selected week
$selected_week = isset($_GET['week']) ? (int)$_GET['week'] : 1;
$weeks_result = range(1, 17);

// Fetch Roster Changes for the selected week
$roster_changes_sql = "
    SELECT rc.week, t.team_name, p.full_name AS player_name, 
           rc.change_type, rc.timestamp 
    FROM roster_changes rc
    JOIN teams t ON rc.team_id = t.team_id
    JOIN players p ON rc.player_id = p.player_id
    WHERE rc.week = $selected_week
    ORDER BY rc.timestamp ASC
";
$roster_changes = $conn->query($roster_changes_sql);

// Fetch Matchups for the selected week, including W-L-T and median calculation
$matchups_sql = "
    SELECT m.team_id, m.points, t.team_name, m.wins, m.losses, m.ties
    FROM matchups m
    JOIN teams t ON m.team_id = t.team_id
    WHERE m.league_id = '$league_id'
    AND m.week = $selected_week
";
$matchups_result = $conn->query($matchups_sql);

// Initialize variables for median calculation
$above_below_data = [];
$median = null;

if ($matchups_result && $matchups_result->num_rows > 0) {
    $week_points = [];
    $matchups_rows = []; // store rows for display after median calc

    while ($row = $matchups_result->fetch_assoc()) {
        $week_points[] = [
            'team_id' => $row['team_id'], 
            'points' => $row['points'], 
            'team_name' => $row['team_name'],
            // 'wins' => $row['wins'],
            // 'losses' => $row['losses'],
            // 'ties' => $row['ties']
        ];
    }

    // Calculate median
    $scores = array_column($week_points, 'points');
    sort($scores);
    $count = count($scores);
    if ($count > 0) {
        $median = ($count % 2 == 0)
            ? ($scores[$count/2 - 1] + $scores[$count/2]) / 2
            : $scores[floor($count/2)];
    }

    // Determine Above/Below Median status and store enhanced data
    if (!is_null($median)) {
        foreach ($week_points as $wp) {
            $status = ($wp['points'] >= $median) ? "Above Median" : "Below Median";
            $wp['status'] = $status;
            $above_below_data[] = $wp;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantasy Football Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Fantasy Football Tracker</h1>
    <!-- Button to refresh data by running update script -->
    <form method="POST" action="update_sleeper_data.php">
        <button type="submit" name="refresh">Refresh Data</button>
    </form>
</header>

<section>
    <h2>Select Week to View Details</h2>
    <form method="GET">
        <label for="week">Choose a Week:</label>
        <select name="week" id="week">
            <?php foreach ($weeks_result as $week): ?>
                <option value="<?php echo $week; ?>" <?php echo ($week == $selected_week) ? 'selected' : ''; ?>>
                    Week <?php echo $week; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">View</button>
    </form>
</section>

<section>
    <h2>Roster Changes - Week <?php echo $selected_week; ?></h2>
    <?php if ($roster_changes && $roster_changes->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Team</th>
                <th>Player</th>
                <th>Change Type</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $roster_changes->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                <td><?php echo htmlspecialchars($row['player_name']); ?></td>
                <td><?php echo ucfirst(htmlspecialchars($row['change_type'])); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No roster changes found for Week <?php echo $selected_week; ?>.</p>
    <?php endif; ?>
</section>

<section>
    <h2>Matchups - Week <?php echo $selected_week; ?></h2>
    <?php if (!empty($above_below_data)): ?>
        <p>Median Points: <?php echo number_format($median, 2); ?></p>
        <table>
            <thead>
                <tr>
                    <th>Team</th>
                    <th>Points</th>
                    <th>Median Status</th>
                    <!--<th>Record (W-L-T)</th>-->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($above_below_data as $data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['team_name']); ?></td>
                    <td><?php echo number_format($data['points'], 2); ?></td>
                    <td><?php echo htmlspecialchars($data['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No matchups found for Week <?php echo $selected_week; ?>.</p>
    <?php endif; ?>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Fantasy Football Tracker | Powered by Sleeper API</p>
</footer>
</body>
</html>
