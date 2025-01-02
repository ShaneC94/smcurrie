<?php
session_start();
require 'db.php';

$error = false; // Track if there's a login error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $password_hash);
    $stmt->fetch();
    $stmt->close();

    if ($user_id && password_verify($password, $password_hash)) {
        $_SESSION['username'] = $username;  // Store username in session
        $_SESSION['user_id'] = $user_id;    // **Fix: Store user_id in session**
        header("Location: index.php");      // Redirect to index page
        exit;
    } else {
        $error = true; // Set error flag to trigger JavaScript alert
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>    
    <link rel="stylesheet" href="/TripPlanner/styles.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST" id="login-form">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <?php if ($error): ?>
        <script>
            alert("Incorrect username or password. Please try again.");
        </script>
    <?php endif; ?>
</body>
</html>
