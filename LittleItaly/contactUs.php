<!--
    The header and jumbotron HTML and CSS was written based off of the code that Shane Currie wrote while working on
    Lab 2 of web programming. 
-->

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
<fieldset class="contactUs">
    <form name="contactForm" class="contactUs" method="post" action="/LittleItaly/contactDB.php">

        <legend>Contact Form</legend>
        Name: <input type="text" name="contact_name"><br>
        Email Address: <input type="text" name="contact_email"><br>
        Contact Number: <input type="text" name="phone_number"><br>
        Message: <input type="text" name="contact_message"><br>

        <button type="submit" name="submit" class="contactBtn">Submit</button>
        <button type="button" name="close" id="closeButton" class="contactBtn">Return</button>
    </form>
</fieldset>
<script>
    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Collect form data
        const formData = new FormData(this);

        // Perform client-side validation
        const name = formData.get('contact_name').trim();
        const email = formData.get('contact_email').trim();
        const phoneNumber = formData.get('phone_number').trim();
        const message = formData.get('contact_message').trim();

        // Regex patterns for validation
        const emailPattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        const phonePattern = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

        if (!name || !email || !phoneNumber || !message) {
            alert('All fields must be filled out before submitting the form.');
            return;
        }

        if (!emailPattern.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        if (!phonePattern.test(phoneNumber)) {
            alert('Please enter a valid phone number. Format: 123-456-7890, 123.456.7890, or (123) 456 7890');
            return;
        }

        try {
            // Send form data using Fetch API
            const response = await fetch('/LittleItaly/contactDB.php', {
                method: 'POST',
                body: formData,
            });

            // Parse the JSON response
            const result = await response.json();

            if (response.ok) {
                // Redirect or show success message based on response
                alert(result.message); 
                window.location.href = "/LittleItaly/contactUs.php?id=" + result.id;
            } else {
                // Handle errors (e.g., validation errors)
                alert(result.error);
            }
        } catch (error) {
            console.error("Error submitting the form:", error);
            alert("There was an error submitting the form. Please try again later.");
        }
    });

    document.getElementById("closeButton").addEventListener("click", function() {
        window.location = "/LittleItaly/index.html";
    });
</script>


</body>
</html>
