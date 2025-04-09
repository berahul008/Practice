<?php
// login_process.php

session_start(); // Move session_start() to the very top

$servername = getenv('MYSQL_HOST');
$username_db = getenv('MYSQL_USER');
$password_db = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        $conn->close();
        exit;
    }

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login successful
                echo "Login successful!";
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                // header("Location: dashboard.php");
                // exit;
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "Username not found.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
