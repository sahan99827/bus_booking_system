<?php
include 'db_connection.php';
session_start(); 
$customer="";
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
//user get by id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? trim($_GET['id']) : '';

    if (!empty($id)) {
        $sql1 = "SELECT * FROM users WHERE id = ?";       
        $result1 = $conn->prepare($sql1);
    
        if ($result1) {
            $result1->bind_param("i", $id); // Adjust this based on the data type of 'id'
            $result1->execute();
            $filteredResult = $result1->get_result();
    
            if ($filteredResult->num_rows > 0) {
                $user = $filteredResult->fetch_assoc(); // Fetch the data as an associative array
        
            } else {
                echo "<p>No user found for the given criteria.</p>";
                $user = null; // Reset the variable if no data is found
            }
        } else {
            echo "Failed to prepare the statement: " . $conn->error;
        }
    }
    

}
// General query for all user (when no filter is applied)
if (empty($customer)) {
    $sql = "SELECT *  FROM users";
           
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    $customer =$result;
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
                        echo '<button class="btn btn-primary" id="bookNowBtn">Customer Search</button>';
                    }
                    ?>
        </div>
    </div>

    <!-- Customer Modal -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Find Customer</span>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form method="post" action="customer.php">
                <label for="username">User Name :</label>
                <input type="text" name="username" id="username">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email">

                <label for="tel_number">Phone Number</label>
                <input type="text" name="tel_number" id="tel_number">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="search">Find</button>
                    <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php 
                    if (isset($_SESSION['username'])) { 
                       
                        ?>
<!-- Customer Table -->
<section class="schedule">
<?php 
                    if (isset($_SESSION['role']) && $_SESSION['role']  == 'admin') { 
               
?>
    <div>
        <button id="customer_report">Customer Report</button>
    </div>
    <?php } ?>
    <div id="schedule">
        <h2>Customer</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // Initialize a counter for numbering rows
                while ($row = $customer->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tel_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
          
                    $sql1 = "SELECT * FROM role";
                    $result = $conn->query($sql1);
                
                    if (isset($_SESSION['role']) && $_SESSION['role']  == 'admin') { 
                        echo "<td>";

                    
                    // Form to update the role
                    echo '<form action="update_role.php" method="POST">';
                    echo '<input type="hidden" name="customer_id" value="' . htmlspecialchars($row['id']) . '">';
                    
                    // Fetch roles for the dropdown
                   
                    if ($result && $result->num_rows > 0) {
                        echo '<select name="role_id" onchange="this.form.submit()" required>';
                        echo '<option value="" disabled>Select Here</option>';

                        while ($location = $result->fetch_assoc()) {
                            // Preselect if the role_id matches
                            $selected = ($row['role_id'] == $location['id']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($location['id']) . "' $selected>" . htmlspecialchars($location['name']) . "</option>";
                        }
                        echo '</select>';
                    }

                    echo '</form>';
                    echo "</td>";
                    }
                    else {
                        echo "<td>";
                        if ($result && $result->num_rows > 0) {
                            while ($location = $result->fetch_assoc()) {
                                // Check if the current role_id matches the location id
                                $selected = ($row['role_id'] == $location['id']);
                                if ($selected) {
                                    echo htmlspecialchars($location['name']);
                                }
                            }
                        }
                        echo "</td>";
                        
                    }
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
        const customerModal = document.getElementById('customerModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');

        // const schedule = document.getElementById("schedule");
        const sch = document.getElementById("sch");
        const search = document.getElementById("search");
        const customer_report = document.getElementById("customer_report");

        bookNowBtn.addEventListener('click', () => {
            customerModal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
            customerModal.style.display = 'none';
        });

        cancelModal.addEventListener('click', () => {
            customerModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === customerModal) {
                customerModal.style.display = 'none';
            }
        });

        // schedule.style.display = 'none';
        // sch.addEventListener('click', () => {
        //     schedule.style.display = 'block';
        // });
        // search.addEventListener('click', () => {
        //     schedule.style.display = 'block';
        // })


        customer_report.addEventListener('click', function() {
        // Redirect to the generate_report.php file to download the PDF
        window.location.href = 'generate_customer_report.php';

        });

  
        
    </script>
</body>
</html>
