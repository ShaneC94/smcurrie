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

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="nav nav-pills ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/LittleItaly/index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/LittleItaly/contactUs.php">Contact Us</a>
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

    <h1>Thank you for choosing to order from us!<br>We'll be contacting you shortly to confirm your pick-up time!</h1><br>

    <fieldset class="reservationConfirmation">
        <?php
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Define database connection constants
        define("DBHOST", "localhost");
        define("DBUSER", "jhkqxilpbxylykwb_rest_manager");
        define("DBPASS", ",9tfcG~WmJDz");
        define("DBNAME", "jhkqxilpbxylykwb_rest_form");

        // Check if order_id is passed in the URL
        if (isset($_GET['order_id'])) {
            $order_id = intval($_GET['order_id']);
            echo "<p>Order ID: " . $order_id . "</p>";

            // Connect to the database
            $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get the order details
            $sql = "SELECT orderName, emailAddress, phoneNumber, selectedFoods, totalPrice, specialRequest FROM order_form WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $order_id);

            if ($stmt->execute()) {
                $stmt->bind_result($orderName, $emailAddress, $phoneNumber, $selectedFoods, $totalPrice, $specialRequest);
                if ($stmt->fetch()) {
                    echo "<p>Order Name: " . htmlspecialchars($orderName) . "</p>";
                    echo "<p>Email Address: " . htmlspecialchars($emailAddress) . "</p>";
                    echo "<p>Phone Number: " . htmlspecialchars($phoneNumber) . "</p>";
                    echo "<p>Your Order: " . htmlspecialchars($selectedFoods) . "</p>";
                    echo "<p>Special Request: " . htmlspecialchars($specialRequest) . "</p>";
                    echo "<p>Total Price: $" . htmlspecialchars($totalPrice) . "</p>";
                } else {
                    echo "<p>Sorry, we couldn't find your order details. Please try calling the restaurant for assistance.</p>";
                }
            } else {
                echo "Error executing query: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "<p>Invalid request. No order ID passed.</p>";
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
