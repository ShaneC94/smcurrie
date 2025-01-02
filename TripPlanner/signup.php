<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation pattern
    $password_pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]{8,20}$/";

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    // Check if password meets the criteria
    elseif (!preg_match($password_pattern, $password)) {
        $error = "Password must be 8-20 characters, contain at least one uppercase letter, one lowercase letter, and one special character.";
    } else {
        // Hash the password and save the user if the password meets criteria
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/TripPlanner/styles.css">
</head>
<body>
    <h2>Sign Up</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form action="signup.php" method="POST" id="signup-form">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <small>Password must be 8-20 characters, contain at least one uppercase letter, one lowercase letter, and one special character.</small>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Sign Up</button>
    </form>

    <script>
    document.getElementById("signup-form").addEventListener("submit", function(event) {
        const password = document.querySelector("input[name='password']").value;
        const confirmPassword = document.querySelector("input[name='confirm_password']").value;
        const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,20}$/;

        if (!passwordPattern.test(password)) {
            event.preventDefault();
            alert("Password must be 8-20 characters, contain at least one uppercase letter, one lowercase letter, and one special character.");
        } else if (password !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match. Please try again.");
        }
    });
    </script>
</body>
</html>


