<?php
session_start(); // Start the session
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Store user ID in the session
            $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the column name for user ID in your database
            // Redirect to the home page after successful login
            header("Location: room.html");
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this email!";
    }
}

$conn->close();
?>
