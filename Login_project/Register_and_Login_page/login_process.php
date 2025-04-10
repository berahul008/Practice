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
    if (isset($_POST['name']) && isset($_POST['email'])) {
        // It seems like a registration attempt based on the presence of 'name' and 'email'
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        // Basic validation (you should add more robust validation)
        if (empty($name) || empty($email) || empty($username) || empty($password)) {
            echo "All registration fields are required.";
            $conn->close();
            exit;
        }

        // Check if the username already exists
        $check_sql = "SELECT username FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "Username already exists. Please choose a different one.";
            $check_stmt->close();
            $conn->close();
            exit;
        }
        $check_stmt->close();

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insert_sql = "INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        if ($insert_stmt) {
            $insert_stmt->bind_param("ssss", $name, $email, $username, $hashed_password);

            if ($insert_stmt->execute()) {
                echo "Registration successful! You can now log in.";
                // Optionally redirect to the login page:
                // header("Location: index.html");
                // exit;
            } else {
                echo "Error during registration: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        // It seems like a login attempt based on the absence of 'name' and 'email'
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo "Username and password are required for login.";
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
}

$conn->close();
?>
