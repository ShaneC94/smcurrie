<?php
define("DBHOST", "localhost");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_rest_form");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $guests = intval($_POST['guests']);

    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

    if ($conn->connect_error) {
        die(json_encode(["error" => "Database connection failed."]));
    }

    // Calculate the start and end times of the 1.5-hour window
    $startTime = date("H:i", strtotime("$reservation_time - 1 hour 30 minutes"));
    $endTime = date("H:i", strtotime("$reservation_time + 1 hour 30 minutes"));

    // Query to calculate the total number of guests within the time window
    $query = "SELECT SUM(reservation_guests) AS total_guests FROM restres 
              WHERE reservation_date = ? 
              AND reservation_time BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $reservation_date, $startTime, $endTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $total_guests = $row['total_guests'] ? intval($row['total_guests']) : 0;

    // Check if adding the new reservation would exceed the 60 guests limit
    if (($total_guests + $guests) > 60) {
        echo json_encode(["exceedsLimit" => true]);
    } else {
        echo json_encode(["exceedsLimit" => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
