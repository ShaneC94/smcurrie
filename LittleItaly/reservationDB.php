<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Toronto');

// Define database connection constants
define("DBHOST", "localhost:3306");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_rest_form");

// define("DBHOST", "192.197.54.35:3306");
// define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
// define("DBPASS", ",9tfcG~WmJDz");
// define("DBNAME", "jhkqxilpbxylykwb_rest_form");

$response = [];

try {
    // Check if the request is a POST request
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
        // Establish database connection
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $reservationName = $_POST["reservation_name"];
        $reservationDate = $_POST["reservation_date"];
        $reservationTime = $_POST["reservation_time"];
        $reservationGuests = (int)$_POST["reservation_guests"];
        $contactNumber = $_POST["contact_number"];
        $specialRequest = $_POST["special_request"];

        // Step 1: Ensure no more than 20 guests per individual reservation
        if ($reservationGuests > 20) {
            $response['error'] = "For parties larger than 20 guests, please call the restaurant to make a reservation.";
            echo json_encode($response);
            exit(); // Ensure no further execution
        }

        // Step 2: Check if the total guests in this time slot (and the next 1 hour) exceed 60
        $query = "SELECT SUM(reservation_guests) AS total_guests 
                  FROM restres 
                  WHERE reservation_time BETWEEN ? AND ADDTIME(?, '01:00:00') 
                  AND reservation_date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $reservationTime, $reservationTime, $reservationDate);

        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalGuests = (int)$row['total_guests'];

        // Step 3: If the total guests are 60 or more, block new bookings
        if ($totalGuests + $reservationGuests > 60) {
            $response['error'] = "The restaurant is fully booked during this time. Please try to make a reservation at a later time or call the restaurant for more details.";
            echo json_encode($response);
            exit(); // Ensure no further execution
        }

        // Step 4: Proceed to insert the reservation into the database
        $sql = "INSERT INTO restres (reservation_name, reservation_date, reservation_time, reservation_guests, contact_number, special_request) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiss", $reservationName, $reservationDate, $reservationTime, $reservationGuests, $contactNumber, $specialRequest);

        // Check if the execution was successful
        if ($stmt->execute()) {
            $response['message'] = 'Reservation submitted successfully!';
            echo json_encode($response);
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    $response['error'] = "Error: " . $e->getMessage();
    echo json_encode($response);
    exit();
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
