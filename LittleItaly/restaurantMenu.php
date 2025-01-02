<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/LittleItaly/restaurantStyles.css">
    <title>Little Italy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="menu.js" defer></script> <!-- Load JavaScript file -->
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
        
    <h1>After ordering, we'll contact you to provide a time for pick-up!</h1>
    <div id="wrap">
        <form action="orderDB.php" id="orderform" method="POST">
            <div class="cont_order">
                <fieldset>
                    <h1>Pizza</h1><br>
                    <label class='checkboxlabel'>
                        <input type="checkbox" class="foodItem" name="selected[]" value="Pizza Margherita" onclick="updateQuantity(this); calculateTotal()">   Margherita<br>($18.99)<br>
                        <input type="number" class="quantity" name="quantity[Pizza Margherita]" value="0" min="0" onchange="calculateTotal()">
                    </label><br>
                    <label class='checkboxlabel'>
                        <input type="checkbox" class="foodItem" name="selected[]" value="Pizza Siciliana" onclick="updateQuantity(this); calculateTotal()">   Siciliana<br>($22.99)<br>
                        <input type="number" class="quantity" name="quantity[Pizza Siciliana]" value="0" min="0" onchange="calculateTotal()">
                    </label><br>
                    <label class='checkboxlabel'>
                        <input type="checkbox" class="foodItem" name="selected[]" value="Pizza Viennese" onclick="updateQuantity(this); calculateTotal()">   Viennese<br>($22.99)<br>
                        <input type="number" class="quantity" name="quantity[Pizza Viennese]" value="0" min="0" onchange="calculateTotal()">
                    </label><br>
                    <label class='checkboxlabel'>
                        <input type="checkbox" class="foodItem" name="selected[]" value="Pizza Capricciosa" onclick="updateQuantity(this); calculateTotal()">   Capricciosa<br>($24.99)<br>
                        <input type="number" class="quantity" name="quantity[Pizza Capricciosa]" value="0" min="0" onchange="calculateTotal()">
                    </label><br>

                    <br>
                    <input type="text" id="specialRequest" name="specialRequest" placeholder="Special Requests">
                    <br><br>
                    <div id="totalPrice"></div>
                    <input type="hidden" name="totalPrice" id="totalPriceInput" value="0">
                </fieldset>
            </div>
            <div class="con_info">
                <fieldset>
                    <br><h1>Contact Information</h1>
                    <label for='orderName'>Name</label>
                    <input type="text" id="orderName" name='orderName' required>
                    <br>
                    <label for='emailAddress'>Email Address</label>
                    <input type="email" id="emailAddress" name='emailAddress' required>
                    <br>
                    <label for='phoneNumber'>Phone Number</label>
                    <input type="text" id="phoneNumber" name='phoneNumber' required>
                    <br>
                </fieldset>
            </div>
            <br><input type='submit' id='submit' value='Submit' onclick="setTotalPrice()">
        </form>
    </div>
</body>

</html>
