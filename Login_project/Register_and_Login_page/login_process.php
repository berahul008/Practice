<?php
// login_process.php

$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    // SQL to retrieve the hashed password for the given username
    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPasswordFromDb = $row["password"];

        // Verify the entered password against the stored hash
        if (password_verify($password, $hashedPasswordFromDb)) {
            // Login successful
            echo "Login successful!";
            // You would typically start a session and redirect the user here
            // session_start();
            // $_SESSION['username'] = $username;
            // header("Location: welcome.php");
            // exit();
        } else {
            // Password does not match
            echo "Invalid username or password.";
        }
    } else {
        // Username not found
        echo "Invalid username or password.";
    }
}

$conn->close();
?>
