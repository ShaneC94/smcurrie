<?php
header('Content-Type: application/json');
include 'db.php';

// Log and retrieve JSON payload
$data = json_decode(file_get_contents('php://input'), true);
error_log("Received Payload: " . print_r($data, true));

// Validate required parameters
if (
    empty($data['user_id']) ||
    empty($data['start_lat']) || empty($data['start_lon']) ||
    empty($data['end_lat']) || empty($data['end_lon']) ||
    empty($data['duration'])
) {
    error_log("Error: Missing required parameters.");
    echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
    exit;
}

// Extract parameters
$user_id = intval($data['user_id']);
$start_lat = floatval($data['start_lat']);
$start_lon = floatval($data['start_lon']);
$end_lat = floatval($data['end_lat']);
$end_lon = floatval($data['end_lon']);
$apiKey = ""; // Enter your weather_api key

// Function to fetch weather data
function fetchWeather($lat, $lon, $apiKey) {
    $url = "https://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$lat},{$lon}";
    error_log("Weather API Request: " . $url);
    $response = @file_get_contents($url);

    if ($response === false) {
        $error = error_get_last();
        error_log("Failed to fetch weather data: {$error['message']}");
        return null;
    }
    error_log("Weather API Response: " . $response); // Log the full API response
    return json_decode($response, true);
}

// Fetch weather data
$startWeather = fetchWeather($start_lat, $start_lon, $apiKey);
$endWeather = fetchWeather($end_lat, $end_lon, $apiKey);

// Check if weather data is valid
if (!$startWeather || !$endWeather) {
    error_log("Error: Failed to fetch weather data.");
    echo json_encode(["status" => "error", "message" => "Failed to fetch weather data."]);
    exit;
}

// Log API Responses
error_log("Start Weather Response: " . print_r($startWeather, true));
error_log("End Weather Response: " . print_r($endWeather, true));

// Extract relevant weather details
$startTemp = $startWeather['current']['temp_c'] ?? null;
$startCondition = $startWeather['current']['condition']['text'] ?? null;
$endTemp = $endWeather['current']['temp_c'] ?? null;
$endCondition = $endWeather['current']['condition']['text'] ?? null;

if (!$startTemp || !$startCondition || !$endTemp || !$endCondition) {
    error_log("Error: Incomplete weather data.");
    echo json_encode(["status" => "error", "message" => "Incomplete weather data."]);
    exit;
}

// Insert into database
try {
    $sql = "INSERT INTO weather_queries (user_id, location, temperature, `condition`, query_time) 
            VALUES (?, ?, ?, ?, NOW()), (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL Prepare Error: " . $conn->error);
    }

    // Format location JSON strings
    $startLocation = json_encode(["lat" => $start_lat, "lon" => $start_lon]);
    $endLocation = json_encode(["lat" => $end_lat, "lon" => $end_lon]);

    // Bind values
    $stmt->bind_param(
        "isssisss",
        $user_id, $startLocation, $startTemp, $startCondition,
        $user_id, $endLocation, $endTemp, $endCondition
    );

    if (!$stmt->execute()) {
        throw new Exception("SQL Execution Error: " . $stmt->error);
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "start_weather" => ["temperature" => $startTemp, "condition" => $startCondition],
            "end_weather" => ["temperature" => $endTemp, "condition" => $endCondition],
        ]
    ]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
