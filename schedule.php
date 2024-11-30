<?php
// File: register.php

// Database configuration
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = $conn->real_escape_string($_POST['bus_id']);
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $eta = $conn->real_escape_string($_POST['eta']);
    $travel_date = $conn->real_escape_string($_POST['travel_date']);
    $availability = $conn->real_escape_string($_POST['availability']);
    $price = $conn->real_escape_string($_POST['price']);
    $sartart_location_id = $conn->real_escape_string($_POST['sartart_location']);
    $end_location_id = $conn->real_escape_string($_POST['end_location']);

    // Insert data into the `schedules` table
    $sql = "INSERT INTO schedules (bus_id, departure_time, eta, travel_date,availability,price,sartart_location_id,end_location_id) VALUES 
    ('$bus_id', '$departure_time', '$eta', '$travel_date', '$availability', '$price', '$sartart_location_id', '$end_location_id')";

    if ($conn->query($sql) === TRUE) {
        echo "successful Add Schedules!</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: index.php");
?>
