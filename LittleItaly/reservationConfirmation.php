<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/LittleItaly/restaurantStyles.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="reservationConfirmation">
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/LittleItaly/index.html">Little Italy</a>
    
    <!-- Hamburger menu button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="nav nav-pills ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="/LittleItaly/index.html">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/LittleItaly/index.html#contactus">Contact Us</a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="#">Food</a></li>
                    <li class="dropdown-item indented"><a href="/LittleItaly/Menu/pizzaMenu.html">Pizza</a></li>
                    <li class="dropdown-item indented"><a href="/LittleItaly/Menu/pastaMenu.html">Pasta</a></li>
                    <li class="dropdown-item indented"><a href="/LittleItaly/Menu/dessertMenu.html">Dessert</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Drinks</a></li>
                    <li class="dropdown-item indented"><a href="/LittleItaly/Menu/drinksMenu.html#drinkMenu">Alcoholic</a></li>
                    <li class="dropdown-item indented"><a href="/LittleItaly/Menu/drinksMenu.html#nonalcoholic">Non-Alcoholic</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/LittleItaly/restaurantReservation.php">Reservation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/LittleItaly/restaurantMenu.php">Order</a>
            </li>
        </ul>
    </div>
</nav>

<h1>Thank you for making a reservation!</h1>
<fieldset class="reservationConfirmation">

<?php
date_default_timezone_set('America/Toronto');

// Define your database connection constants here
define("DBHOST", "localhost");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_restres");

try {
    if (!empty($_POST)) {
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        $reservationName = $_POST["reservation_name"];
        $reservationDate = $_POST["reservation_date"];
        $reservationTime = $_POST["reservation_time"];
        $reservationGuests = $_POST["reservation_guests"];
        $contactNumber = $_POST["contact_number"];
        $specialRequest = $_POST["special_request"];

        // Insert reservation into the database
        $sql = "INSERT INTO restres (reservation_name, reservation_date, reservation_time, reservation_guests, contact_number, special_request) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiss", $reservationName, $reservationDate, $reservationTime, $reservationGuests, $contactNumber, $specialRequest);

        if ($stmt->execute()) {
            $reservationId = $conn->insert_id; // Get the inserted reservation ID
            // Redirect to confirmation page with the reservation ID
            header("Location: /LittleItaly/reservationConfirmation.php?reservation_id=$reservationId");
            exit();  // Important to stop further script execution
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo '<p><a href="javascript:history.back()">Go Back</a></p>';
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

<button type="button" name="close" id="closeButton">Back to Main Page</button>
<script>
    document.getElementById("closeButton").addEventListener("click", function() {
        window.location = "/LittleItaly/index.html";
    });
</script>
</fieldset>

</body>
</html>
