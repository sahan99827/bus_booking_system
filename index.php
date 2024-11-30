<?php
include 'db_connection.php';
session_start(); 
$schedul="";
// Simulate a database connection (for testing purposes)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sartart_location = isset($_POST['sartart_location']) ? trim($_POST['sartart_location']) : '';
    $end_location = isset($_POST['end_location']) ? trim($_POST['end_location']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';

    if (!empty($sartart_location) && !empty($end_location) && !empty($date)) {
        $sql = "SELECT 
                    schedules.id, 
                    schedules.bus_name, 
                    schedules.departure_time, 
                    schedules.eta, 
                    schedules.travel_date, 
                    schedules.availability, 
                    schedules.price, 
                    sartart_location.name AS start_location_name, 
                    end_location.name AS end_location_name
                FROM schedules
                JOIN sartart_location ON schedules.sartart_location_id = sartart_location.id
                JOIN end_location ON schedules.end_location_id = end_location.id
                WHERE schedules.sartart_location_id = ? 
                  AND schedules.end_location_id = ? 
                  AND schedules.travel_date = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iis", $sartart_location, $end_location, $date);
            $stmt->execute();
            $filteredResult = $stmt->get_result(); // Fetch the filtered schedules

            if ($filteredResult->num_rows > 0) {
                $schedul = $filteredResult; // Use the filtered result for the table
            } else {
                echo "<p>No schedules found for the given criteria.</p>";
                $schedul = null; // Reset the variable if no data is found
            }
        } else {
            echo "Failed to prepare the statement: " . $conn->error;
        }
    }
}

// General query for all schedules (when no filter is applied)
if (empty($schedul)) {
    $sql = "SELECT 
                schedules.id, 
                schedules.bus_id,  
                schedules.departure_time, 
                schedules.eta, 
                schedules.travel_date, 
                schedules.availability, 
                schedules.price, 
                sartart_location.name AS start_location_name, 
                end_location.name AS end_location_name,
                buses.name AS bus_name
            FROM schedules
            JOIN buses ON schedules.bus_id = buses.bus_id
            JOIN sartart_location ON schedules.sartart_location_id = sartart_location.id
            JOIN end_location ON schedules.end_location_id = end_location.id";
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    $schedul =$result;
}
}
?>
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
    <!-- Header Section -->
    <?php include 'nav.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h2>Welcome to the Bus Ticket Reservation System</h2>
            <?php 
                    if (isset($_SESSION['username'])) { 
                        echo '<button class="btn btn-primary" id="bookNowBtn">Schedule Search</button>';
                    }
                    ?>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Find Schedule</span>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form method="post" action="index.php">
                <label for="sartart_location">Start :</label>
                <?php
                        $sql1 = "SELECT * FROM sartart_location"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="sartart_location" id="sartart_location" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>
                <label for="end_location">End :</label>
                <?php
                        $sql1 = "SELECT * FROM end_location"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="end_location" id="end_location" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>

                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="search">Find</button>
                    <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

   <!-- Schedule Modal -->
   <div id="scheduleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Add Schedule</span>
                <span class="close" id="scheduleCloseModal">&times;</span>
            </div>
            <form method="post" action="schedule.php">
                <label for="travel_date">Travel Date :</label>
                <input type="date" name="travel_date" id="travel_date" required>

                <label for="departure_time">Departure Time :</label>
                <input type="time" name="departure_time" id="departure_time" required>


                <label for="eta">Eta Time :</label>
                <input type="time" name="eta" id="eta" required>

                <label for="availability">Availability :</label>
                <input type="text" name="availability" id="availability" required>

                <label for="price">Price :</label>
                <input type="text" name="price" id="price" required>

                <label for="sartart_location">Start :</label>
                <?php
                        $sql1 = "SELECT * FROM sartart_location"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="sartart_location" id="sartart_location" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>
                <label for="end_location">End :</label>
                <?php
                        $sql1 = "SELECT * FROM end_location"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="end_location" id="end_location" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>
                <label for="bus_name">Bus Name :</label>
                <?php
                        $sql1 = "SELECT * FROM buses"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="bus_name" id="bus_name" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['bus_id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="search">submit</button>
                    <button type="button" class="btn btn-secondary" id="scheduleCancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

   
   <?php 
                    if (isset($_SESSION['username'])) { 
               
                        ?>
     <!-- Schedule Table -->
       <section class="schedule">
       <?php 
                    if (isset($_SESSION['role']) && $_SESSION['role']  == 'admin') { 
               
        ?>
       <div><button id="sechedul_report">sechedul Report</button>
       <button id="sechedul_addBtn">sechedul Add</button></div>

       <?php } ?>

        <div id="schedule">
            <h2>Bus Schedule</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Travel Date</th>
                        <th>Bus</th>
                        <th>Stard</th>
                        <th>End</th>
                        <th>Departure</th>
                        <th>ETA</th>
                        <th>Availability</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                    $counter = 1; // Initialize a counter for numbering rows
                    while ($row = $schedul->fetch_assoc()) { 
            
                        // Assuming $row contains fields like 'date', 'bus', 'location', 'departure', 'eta', 'availability', 'price'
                        echo "<tr>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['travel_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bus_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_location_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_location_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['eta']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['availability']) . "</td>";
                        echo "<td>Rs. " . htmlspecialchars($row['price']) . "</td>";
                        echo "<td><button class='book-now' onclick=\"window.location.href='booking.php?bus_id=" . htmlspecialchars($row['bus_id']) . "'\">Book Now</button></td>";
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
        </section>

            <?php } ?>
    <!-- Footer Section -->
    <?php include 'footer.html'; ?>


    <script>
        // JavaScript to handle modal
        const bookNowBtn = document.getElementById('bookNowBtn');
        const bookingModal = document.getElementById('bookingModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');

        bookNowBtn.addEventListener('click', () => {
            bookingModal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
            bookingModal.style.display = 'none';
        });

        cancelModal.addEventListener('click', () => {
            bookingModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === bookingModal) {
                bookingModal.style.display = 'none';
            }
        });


        const schedule = document.getElementById("schedule");
        const sch = document.getElementById("sch");
        const search = document.getElementById("search");
        const sechedul_report = document.getElementById("sechedul_report");

        // schedule.style.display = 'none';
        // sch.addEventListener('click', () => {
        //     schedule.style.display = 'block';
        // });
        // search.addEventListener('click', () => {
        //     schedule.style.display = 'block';
        // })


        sechedul_report.addEventListener('click', function() {
        // Redirect to the generate_report.php file to download the PDF
        window.location.href = 'generate_report.php';

        });

        //schedule modal
        const sechedul_addBtn = document.getElementById('sechedul_addBtn');
        const scheduleModal = document.getElementById('scheduleModal');
        const scheduleCloseModal = document.getElementById('scheduleCloseModal');
        const scheduleCancelModal = document.getElementById('scheduleCancelModal');

        sechedul_addBtn.addEventListener('click', () => {
            scheduleModal.style.display = 'block';
        });

        scheduleCancelModal.addEventListener('click', () => {
            scheduleModal.style.display = 'none';
        });

        scheduleCloseModal.addEventListener('click', () => {
            scheduleModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === scheduleModal) {
                scheduleModal.style.display = 'none';
            }
        });

        
    </script>
</body>
</html>
