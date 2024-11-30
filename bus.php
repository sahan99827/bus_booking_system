<?php
include 'db_connection.php';
session_start(); 
$bus="";
// Simulate a database connection (for testing purposes)

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $sartart_location = isset($_POST['sartart_location']) ? trim($_POST['sartart_location']) : '';
//     $end_location = isset($_POST['end_location']) ? trim($_POST['end_location']) : '';
//     $date = isset($_POST['date']) ? trim($_POST['date']) : '';

//     if (!empty($sartart_location) && !empty($end_location) && !empty($date)) {
//         $sql = "SELECT 
//                     schedules.id, 
//                     schedules.bus_name, 
//                     schedules.departure_time, 
//                     schedules.eta, 
//                     schedules.travel_date, 
//                     schedules.availability, 
//                     schedules.price, 
//                     sartart_location.name AS start_location_name, 
//                     end_location.name AS end_location_name
//                 FROM schedules
//                 JOIN sartart_location ON schedules.sartart_location_id = sartart_location.id
//                 JOIN end_location ON schedules.end_location_id = end_location.id
//                 WHERE schedules.sartart_location_id = ? 
//                   AND schedules.end_location_id = ? 
//                   AND schedules.travel_date = ?";
//         $stmt = $conn->prepare($sql);
//         if ($stmt) {
//             $stmt->bind_param("iis", $sartart_location, $end_location, $date);
//             $stmt->execute();
//             $filteredResult = $stmt->get_result(); // Fetch the filtered schedules

//             if ($filteredResult->num_rows > 0) {
//                 $schedul = $filteredResult; // Use the filtered result for the table
//             } else {
//                 echo "<p>No schedules found for the given criteria.</p>";
//                 $schedul = null; // Reset the variable if no data is found
//             }
//         } else {
//             echo "Failed to prepare the statement: " . $conn->error;
//         }
//     }
// }
    
// General query for all user (when no filter is applied)
if (empty($bus)) {
    $sql = "SELECT *  FROM buses";
           
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    $bus =$result;
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
                        echo '<button class="btn btn-primary" id="busNowBtn">Bus Search</button>';
                    }
                    ?>
        </div>
    </div>

    <!-- Buss Modal -->
    <div id="busModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Find Bus</span>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form method="post" action="bus.php">
                <label for="busname">Bus Name :</label>
                <input type="text" name="busname" id="busname">
                <label for="bus_type">Bus Type :</label>
                <input type="text" name="bus_type" id="bus_type">
                <label for="route">Route Number</label>
                <input type="text" name="route" id="route">
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="search">Find</button>
                    <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bus add Modal -->
    <div id="busAddModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Add bus</span>
                <span class="close" id="busCloseModal">&times;</span>
            </div>
            <form method="post" action="bus_add.php">
                <label for="name">Bus Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="bus_type">Bus Type :</label>
                <input type="text" name="bus_type" id="bus_type" required>


                <label for="capacity">Capacity :</label>
                <input type="text" name="capacity" id="capacity" required>

                <label for="route">Route Nuber:</label>
                <input type="text" name="route" id="route" required>
               
                <label for="bus_number">Bus Number</label>
                <input type="text" name="bus_number" id="bus_number">

                <label for="agents_id">Agent Name :</label>
                <?php
                        $sql1 = "SELECT * FROM agents"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="agents_id" id="agents_id" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['agent_id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="search">submit</button>
                    <button type="button" class="btn btn-secondary" id="busCancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

<!-- Bus update Modal -->
<div id="busUpdateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Edit Bus</span>
            <span class="close" id="busUpdateCloseModal">&times;</span>
        </div>
        <div id="busUpdateContent">
            <!-- Content from bus_update.php will be loaded here -->
        </div>
    </div>
</div>


    
    <?php 
                    if (isset($_SESSION['username'])) { 
                       
                        ?>
<!-- Buss Table -->
<section class="schedule">
<?php 
                    if (isset($_SESSION['role']) && $_SESSION['role']  == 'admin') { 
               
?>
    <div>
        <button id="buss_report">Bus Report</button>
        <button id="bus_addBtn">Bus Add</button>
    </div>
    <?php } ?>
    <div id="schedule">
        <h2>Bus Table</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Buss Name</th>
                    <th>Buss Type</th>
                    <th>Capacity</th>
                    <th>Route</th>
                    <th>Agent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // Initialize a counter for numbering rows
                while ($row = $bus->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['route']) . "</td>";
          
                    $sql1 = "SELECT * FROM agents";
                    $result = $conn->query($sql1);
                
                        echo "<td>";
                        if ($result && $result->num_rows > 0) {
                            while ($location = $result->fetch_assoc()) {
                                // Check if the current agents_agent_id matches the location id
                                $selected = ($row['agents_agent_id'] == $location['agent_id']);
                                if ($selected) {
                                    echo htmlspecialchars($location['name']);
                                }
                            }
                        }
                        echo "</td>";
                        echo "<td> <button class='busUpdateBtn' data-bus-id='" . htmlspecialchars($row['bus_id']) . "'>Bus Edit</button>
                                    <button class='busDeleteBtn btn-secondary' 
                                    onclick=\"if(confirm('Are you sure you want to delete this bus?')) 
                                    window.location.href='bus_delete.php?bus_id=" . htmlspecialchars($row['bus_id']) . "'\">Bus Delete</button>                        
                            </td>";
                    
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
        const busNowBtn = document.getElementById('busNowBtn');
        const busModal = document.getElementById('busModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');

        // const schedule = document.getElementById("schedule");
        const customer_report = document.getElementById("customer_report");

        busNowBtn.addEventListener('click', () => {
            busModal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
            busModal.style.display = 'none';
        });

        cancelModal.addEventListener('click', () => {
            busModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === busModal) {
                busModal.style.display = 'none';
            }
        });

   

        buss_report.addEventListener('click', function() {
        // Redirect to the generate_report.php file to download the PDF
        window.location.href = 'generate_bus_report.php';

        });

  
        //Buss Add modal
        const bus_addBtn = document.getElementById('bus_addBtn');
        const busAddModal = document.getElementById('busAddModal');
        const busCloseModal = document.getElementById('busCloseModal');
        const busCancelModal = document.getElementById('busCancelModal');

        bus_addBtn.addEventListener('click', () => {
            busAddModal.style.display = 'block';
        });

        busCancelModal.addEventListener('click', () => {
            busAddModal.style.display = 'none';
        });

        busCloseModal.addEventListener('click', () => {
            busAddModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === busAddModal) {
                busAddModal.style.display = 'none';
            }
        })


    // Selecting all "Edit" buttons in the table
    const busUpdateBtns = document.querySelectorAll('.busUpdateBtn');
    const busUpdateModal = document.getElementById('busUpdateModal');
    const busUpdateCloseModal = document.getElementById('busUpdateCloseModal');
    const busUpdateCancelModal = document.getElementById('busUpdateCancelModal');
    const busUpdateContent = document.getElementById('busUpdateContent');

    busUpdateBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const busId = btn.getAttribute('data-bus-id');

            // Fetch content dynamically
            fetch(`bus_update.php?bus_id=${busId}`)
                .then((response) => response.text())
                .then((html) => {
                    busUpdateContent.innerHTML = html; // Load content into the modal
                    busUpdateModal.style.display = 'block'; // Show the modal
                })
                .catch((error) => {
                    console.error('Error loading bus update content:', error);
                });
        });
    });

    // Close modal functionality
    busUpdateCloseModal.addEventListener('click', () => {
        busUpdateModal.style.display = 'none';
    });

    busUpdateCancelModal.addEventListener('click', () => {
        busUpdateModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === busUpdateModal) {
            busUpdateModal.style.display = 'none';
        }
    });



        
    </script>
</body>
</html>




