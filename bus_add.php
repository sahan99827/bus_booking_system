<?php
// File: register.php

// Database configuration
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_name = $conn->real_escape_string($_POST['name']);
    $bus_type = $conn->real_escape_string($_POST['bus_type']);
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $route = $conn->real_escape_string($_POST['route']);
    $bus_number = $conn->real_escape_string($_POST['bus_number']);
    $created_at = date('Y-m-d H:i:s');
    $agents_id = $conn->real_escape_string($_POST['agents_id']);
   
    // Insert data into the `bus` table
    $sql = "INSERT INTO buses (name, type,bus_number,capacity, agents_agent_id,route,created_at) VALUES 
    ('$bus_name', '$bus_type', '.$bus_number.', '$capacity','$agents_id','$route','$created_at')";

    if ($conn->query($sql) === TRUE) {
        echo "successful Add bus!</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: bus.php");
?>
