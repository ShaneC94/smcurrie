<?php
session_start();
header('Content-Type: application/json'); // Send JSON response
date_default_timezone_set('America/Toronto');

// Database connection
define("DBHOST", "localhost");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_rest_form");

// Function to calculate total price
function calculateTotalPrice($selectedItems, $quantities) {
    $totalPrice = 0;

    // Prices of the items
    $itemPrices = [
        "Pizza Margherita" => 18.99,
        "Pizza Siciliana" => 22.99,
        "Pizza Viennese" => 22.99,
        "Pizza Capricciosa" => 24.99,
    ];

    foreach ($selectedItems as $item) {
        if (array_key_exists($item, $itemPrices)) {
            $quantity = $quantities[$item];
            $totalPrice += $itemPrices[$item] * $quantity;
        }
    }

    return $totalPrice;
}

$response = ["success" => false];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $orderName = $_POST['orderName'];
        $emailAddress = $_POST['emailAddress'];
        $phoneNumber = $_POST['phoneNumber'];
        $selectedItems = $_POST['selected'];
        $quantities = $_POST['quantity'];
        $specialRequest = $_POST['specialRequest'];

        // Prepare the selected items and quantities
        $selectedFoods = [];
        foreach ($selectedItems as $item) {
            $quantity = $quantities[$item];
            $selectedFoods[] = "$item (Quantity: $quantity)";
        }

        $selectedFoodsString = implode(", ", $selectedFoods);
        $totalPrice = calculateTotalPrice($selectedItems, $quantities);

        // Insert the order into the database
        $sql = "INSERT INTO order_form (orderName, emailAddress, phoneNumber, selectedFoods, totalPrice, specialRequest)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssds", $orderName, $emailAddress, $phoneNumber, $selectedFoodsString, $totalPrice, $specialRequest);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['orderDetails'] = [
                "orderName" => $orderName,
                "emailAddress" => $emailAddress,
                "phoneNumber" => $phoneNumber,
                "selectedFoods" => $selectedFoodsString,
                "totalPrice" => number_format($totalPrice, 2),
                "specialRequest" => $specialRequest
            ];
        } else {
            throw new Exception("Failed to submit the order: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    $response["error"] = $e->getMessage();
}

echo json_encode($response);
