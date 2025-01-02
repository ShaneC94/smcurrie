<?php
// Include database connection and any other required files
include 'db.php'; // Database connection
include 'weather_api.php'; 

// Function to save weather data into the database
function saveWeatherData($user_id, $start_location, $destination, $travel_duration, $start_temp, $start_condition, $end_temp, $end_condition, $forecast_time, $api_response) {
    global $conn;

    $sql = "INSERT INTO weather_queries (user_id, start_location, destination, travel_duration, start_temp, start_condition, end_temp, end_condition, forecast_time, api_response) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississssss", $user_id, $start_location, $destination, $travel_duration, $start_temp, $start_condition, $end_temp, $end_condition, $forecast_time, $api_response);

    if ($stmt->execute()) {
        return $conn->insert_id; // Return the ID of the newly inserted record for linking purposes
    } else {
        echo "Error saving weather data: " . $stmt->error;
        return null;
    }

    $stmt->close();
}

// Function to save a trip with associated weather data
function saveTripWithWeather($user_id, $start_location, $destination, $travel_duration) {
    // Fetch weather data from the API (implement this function in weather_api.php)
    $weatherData = fetchWeatherData($start_location, $destination, $travel_duration);

    // Extract necessary details from the API response
    $start_temp = $weatherData['start']['temp'];
    $start_condition = $weatherData['start']['condition'];
    $end_temp = $weatherData['end']['temp'];
    $end_condition = $weatherData['end']['condition'];
    $forecast_time = $weatherData['forecast_time'];
    $api_response = json_encode($weatherData['api_response']); // Optional: raw JSON for debugging

    // Save weather data and get the inserted weather_query_id
    $weather_query_id = saveWeatherData($user_id, $start_location, $destination, $travel_duration, $start_temp, $start_condition, $end_temp, $end_condition, $forecast_time, $api_response);

    if ($weather_query_id) {
        // Save the trip details in search_history and link to weather query
        saveSearchHistory($user_id, $start_location, $destination, $weather_query_id);
    }
}

// Function to save the search history with a link to the weather query
function saveSearchHistory($user_id, $start_location, $destination, $weather_query_id) {
    global $conn;

    $sql = "INSERT INTO search_history (user_id, search_query, weather_query_id)
            VALUES (?, ?, ?)";
    
    $search_query = "$start_location to $destination"; // Format search query string
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $search_query, $weather_query_id);

    if ($stmt->execute()) {
        echo "Search history saved with weather data link.";
    } else {
        echo "Error saving search history: " . $stmt->error;
    }

    $stmt->close();
}
