<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "You need to log in to book a room!";
        exit();
    }

    // Retrieve form data
    $room_type = $_POST['room_type'];
    $checkin_date = $_POST['checkin'];
    $checkout_date = $_POST['checkout'];
    $booking_time = $_POST['time'];
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session after login

    // Check if the room is already booked for the selected dates
    $sql_check = "SELECT * FROM bookings WHERE room_type = '$room_type' AND 
                  (checkin_date <= '$checkout_date' AND checkout_date >= '$checkin_date')";

    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo "The selected room is unavailable for the chosen dates. Please select a different room or date.";
    } else {
        // Insert booking data into the database
        $sql = "INSERT INTO bookings (room_type, checkin_date, checkout_date, booking_time, user_id) 
                VALUES ('$room_type', '$checkin_date', '$checkout_date', '$booking_time', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Room booked successfully!";
            header("Location: confirmation.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
