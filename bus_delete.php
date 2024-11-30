<?php
// Include database connection
include 'db_connection.php';

// Check if bus_id is provided in the GET request
if (isset($_GET['bus_id'])) {
    $bus_id = intval($_GET['bus_id']); // Sanitize the bus_id to avoid SQL injection

    // Prepare the delete query
    $query = "DELETE FROM buses WHERE bus_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $bus_id); // Bind the bus_id as an integer parameter

        // Execute the query
        if ($stmt->execute()) {
            echo "Bus deleted successfully.";
        } else {
            echo "Error deleting bus: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    $conn->close();

    // Redirect back to the main bus list page
    header("Location: bus.php");
    exit;
} else {
    echo "Invalid request. Bus ID is missing.";
    exit;
}
?>
