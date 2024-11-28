<?php
// File: register.php

// Database configuration
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_seats = $conn->real_escape_string($_POST['total_seats']);
    $total_price = $conn->real_escape_string($_POST['total_price']);
    $bus_id = $conn->real_escape_string($_POST['bus_id']);
    $book_type = $conn->real_escape_string($_POST['book_type']);
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $booking_date = $conn->real_escape_string($_POST['booking_date']);
   

    // Insert data into the `book` table
    $sql = "INSERT INTO bookings (bus_id, total_seats, book_type_id, total_price,users_id) VALUES 
    ('$bus_id', '$total_seats', '$book_type', '$total_price', '$user_id')";

    // Insert data into the `tickets` table
    $sql2 = "INSERT INTO tickets (bus_id, booking_date, price,users_id) VALUES 
    ('$bus_id', '$booking_date', '$total_price', '$user_id')";


    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        echo "successful Add Schedules!</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: booking.php");
?>
