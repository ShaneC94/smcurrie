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

<h1>Thank you for reaching out to us!</h1>
<fieldset class="contactConfirmation">

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define your database connection constants here
define("DBHOST", "localhost");
define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
define("DBPASS", ",9tfcG~WmJDz");
define("DBNAME", "jhkqxilpbxylykwb_rest_form");

// Check if id is passed
if (isset($_GET['id'])) {
    $contact_id = intval($_GET['id']);
    //echo "DEBUG: Passed ID is: " . $contact_id . "<br>";

    // Connect to the database
    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    if ($conn->connect_error) {
        die("DEBUG: Connection failed: " . $conn->connect_error);
    }

    // Direct SQL query (alternative to the prepared statement for debugging)
    $query = "SELECT contact_name, phone_number, contact_email FROM contact_us WHERE id = " . $contact_id;
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch associative array
        while ($row = $result->fetch_assoc()) {
            //echo "DEBUG: Data fetched successfully.<br>";
            echo "Thank you for your message " . htmlspecialchars($row['contact_name']) . "!" . "<br>";
            echo "We'll reach out to you at " . htmlspecialchars($row['phone_number']);
            echo " or " . htmlspecialchars($row['contact_email']) . " within 3 business days!" . "<br>";
        }
    } else {
        echo "DEBUG: No record found for ID " . $contact_id . "<br>";
    }

    $conn->close();
} else {
    echo "DEBUG: Invalid request. No ID passed.";
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
