<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/LittleItaly/restaurantStyles.css">
    <title>Little Italy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="restaurantReservation">
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

<h1>Come and join us for a meal!</h1>
<fieldset class="restaurantReservation">
    <form name="reservationForm" class="restaurantReservation" method="post" action="/LittleItaly/restaurantReservation.php">
        <legend>Reservation Form</legend>
        Name: <input type="text" name="reservation_name"><br>
        Date: <input type="date" name="reservation_date" min="<?php echo date('Y-m-d'); ?>"><br>
        Time: <select name="reservation_time">
            <?php
            $start = new DateTime('11:00');
            $end = new DateTime('22:15'); // Ensures 10:00 PM is included
            $interval = new DateInterval('PT15M');
            $period = new DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                echo "<option value=\"" . $dt->format('H:i') . "\">" . $dt->format('g:i A') . "</option>";
            }
            ?>
        </select><br>
        Guests (including yourself): <input type="number" name="reservation_guests"><br>
        Contact Number: <input type="text" name="contact_number"><br>
        Special Requests: <input type="text" name="special_request"><br>

        <button type="submit" name="reservationSubmit" class="reservationBtn">Submit</button>
        <button type="button" name="close" id="closeButton" class="reservationBtn">Return</button>
    </form>
</fieldset>

<!-- Link to external JavaScript file -->
<script src="/LittleItaly/reservationValidation.js"></script>
</body>
</html>
