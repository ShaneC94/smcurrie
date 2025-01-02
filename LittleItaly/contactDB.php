<?php
session_start();
header('Content-Type: application/json'); // Set the response type to JSON
date_default_timezone_set('America/Toronto');

define("DBHOST", "localhost:3306");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_rest_form");

$response = [];

try {
    if (!empty($_POST)) {
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        $contact_name   = trim($_POST['contact_name']);
        $phone_number   = trim($_POST['phone_number']);
        $contact_email  = trim($_POST['contact_email']);
        $contact_message = trim($_POST['contact_message']);

        // Validate fields
        if (empty($contact_name) || empty($phone_number) || empty($contact_email) || empty($contact_message)) {
            throw new Exception("All fields are required.");
        }

        // Validate email
        if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Validate phone number using a regex pattern
        if (!preg_match("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", $phone_number)) {
            throw new Exception("Invalid phone number format.");
        }

        $stmt = $conn->prepare("INSERT INTO `contact_us` (contact_name, phone_number, contact_email, contact_message) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssss", $contact_name, $phone_number, $contact_email, $contact_message);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $stmt->close();

        // Get the ID of the newly inserted contact
        $last_id = $conn->insert_id;

        // Prepare a success response
        $response['id'] = $last_id;
        $response['message'] = 'Success! We will reach out within three business days!';
        echo json_encode($response);
        exit();
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
?>
