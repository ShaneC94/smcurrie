<?php
// db_config.php

// Enter your db and sleeper league details
$host = "";
$user = "";
$password = "";
$dbname = "";
$league_id = "";

// Database connection
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
