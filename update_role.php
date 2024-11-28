<?php
// Include your database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $role_id = intval($_POST['role_id']);

    // Update query
    $sql = "UPDATE users SET role_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $role_id, $customer_id);
        if ($stmt->execute()) {
            echo "Role updated successfully.";
        } else {
            echo "Error updating role: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    $conn->close();

    // Redirect back to the customer page
    header("Location: customer.php"); // Replace with your customer page file
    exit;
}
?>
