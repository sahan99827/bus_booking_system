<?php
// Include database connection
include 'db_connection.php';

// Check if book_id is provided in the GET request
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']); // Sanitize the book_id to avoid SQL injection

    // Prepare the delete query
    $query = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $book_id); // Bind the book_id as an integer parameter

        // Execute the query
        if ($stmt->execute()) {
            echo "Book deleted successfully.";
        } else {
            echo "Error deleting Book: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    $conn->close();

    // Redirect back to the main Book list page
    header("Location: booking.php");
    exit;
} else {
    echo "Invalid request. Book ID is missing.";
    exit;
}
?>
