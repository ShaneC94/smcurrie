<?php
// Start the session
session_start();

// Check if the user is logged in and set variables accordingly
$is_logged_in = isset($_SESSION['username']) && isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Debugging session info
error_log("Session User ID: " . print_r($_SESSION['user_id'] ?? "Not Set", true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Trip Planner</title>
    <link rel="stylesheet" href="/TripPlanner/styles.css">
    
    <!-- Mapbox CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Meta tag to store user ID for JavaScript access -->
    <?php if ($is_logged_in): ?>
        <meta name="user_id" content="<?php echo htmlspecialchars($user_id); ?>">
    <?php else: ?>
        <meta name="user_id" content="null">
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Trip Planner</h1>
        <p>Plan your next trip and avoid bad weather along the way.</p>
        
        <div class="auth-links">
            <?php if ($is_logged_in): ?>
                <!-- If user is logged in, show logout and welcome message -->
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- If user is not logged in, show login and signup links -->
                <a href="login.php">Login</a> | 
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <div class="trip-form">
            <form id="trip-planner">
                <label for="start">Start Location:</label>
                <input type="text" id="start" name="start" placeholder="Enter start location" required>

                <label for="end">End Location:</label>
                <input type="text" id="end" name="end" placeholder="Enter destination" required>

                <button type="submit">Plan Trip</button>
            </form>
        </div>

        <div id="map"></div> <!-- Map will be embedded here -->
    </div>

    <!-- Displaying trip info -->
    <div id="trip-info">
        <h2>Your trip</h2>
        <div id="trip-details">No trip info yet.</div>
    </div>

    <div id="weather-info">
        <h2>Weather Along the Route</h2>
        <div id="weather-details">No weather data yet.</div>
    </div>

    <footer>
        <p><?php echo date("Y"); ?> Trip Planner.</p>
    </footer>

    <!-- Pass PHP variables to JavaScript -->
    <script>
        const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;
        const userId = <?php echo json_encode($user_id); ?>;
        // console.log("User Logged In:", isLoggedIn);
        // console.log("Retrieved User ID:", userId);
    </script>

    <!-- Mapbox JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <!-- Weather and Map JS -->
    <script src="map.js"></script>
    <script src="weather.js"></script>
</body>
</html>
