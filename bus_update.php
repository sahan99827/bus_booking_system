
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket Reservation</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
  
</head>
<body>
<?php
include 'db_connection.php';

if (isset($_GET['bus_id'])) {
    $bus_id = intval($_GET['bus_id']);
    $query = "SELECT * FROM buses WHERE bus_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bus = $result->fetch_assoc();
        ?>
       <form id="busUpdateForm" action="bus_update.php" method="POST">
    <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus['bus_id']); ?>">
    <label for="busName">Bus Name:</label>
    <input type="text" id="busName" name="name" value="<?php echo htmlspecialchars($bus['name']); ?>" required>
    
    <label for="busType">Bus Type:</label>
    <input type="text" id="busType" name="type" value="<?php echo htmlspecialchars($bus['type']); ?>" required>
    
    <label for="busCapacity">Capacity:</label>
    <input type="number" id="busCapacity" name="capacity" value="<?php echo htmlspecialchars($bus['capacity']); ?>" min="1" required>
    
    <label for="busRoute">Route Number:</label>
    <input type="text" id="busRoute" name="route" value="<?php echo htmlspecialchars($bus['route']); ?>" required>
    
    <label for="agents_id">Agent Name:</label>
    <select name="agents_id" id="agents_id" required>
        <option value="" disabled>Select Here</option>
        <?php
        $sql1 = "SELECT * FROM agents";
        $result = $conn->query($sql1);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($row['agent_id'] == $bus['agents_agent_id']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($row['agent_id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
            }
        } else {
            echo "<option value='' disabled>No agents available</option>";
        }
        ?>
    </select>

    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Update</button>
        <button class="btn btn-secondary" type="button" id="busUpdateCancelModal">Cancel</button>
    </div>
</form>

        <?php
    } else {
        echo "<p>Bus not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
</body>
</html>

<?php
// Include your database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = intval($_POST['bus_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $type = htmlspecialchars(trim($_POST['type']));
    $capacity = intval($_POST['capacity']);
    $route = htmlspecialchars(trim($_POST['route']));
    $agents_id = intval($_POST['agents_id']);

    // Validate the inputs
    if (empty($name) || empty($type) || empty($route) || $capacity <= 0 || $agents_id <= 0) {
        echo "All fields are required.";
        exit;
    }

    // Update the bus record
    $sql = "UPDATE buses SET name = ?, type = ?, capacity = ?, route = ?, agents_agent_id = ? WHERE bus_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssisii", $name, $type, $capacity, $route, $agents_id, $bus_id);
        if ($stmt->execute()) {
            echo "Bus details updated successfully.";
        } else {
            echo "Error updating bus: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();

    // Redirect to main bus list page
    header("Location: bus.php");
    exit;
}
?>
