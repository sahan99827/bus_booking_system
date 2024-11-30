<?php
include 'db_connection.php';
session_start();
$bookings ='';
if (empty($bookings)) {
    $sql = "SELECT bookings.*, 
    buses.name AS bus_name, 
    book_type.name AS book_type_name, 
    users.username AS user_name 
    FROM bookings
    JOIN book_type ON bookings.book_type_id = book_type.id
    JOIN users ON bookings.users_id = users.id
    JOIN buses ON bookings.bus_id = buses.bus_id";

    $result = $conn->query($sql);


    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        $bookings = $result; // Assign the result object to $bookings
    } else {
        $bookings = false; // Mark $bookings as false if no rows or query failed
    }
}

    // Render table
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="book.css">
    <style>
        .button{
            width: auto;
        }
        </style>
</head>
<body>
    <div>
    <?php include 'nav.php'; ?>
    </div>


    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h2>Welcome to the Bus Ticket Reservation System</h2>
        </div>
    </div>


    <div class="form-container view">
    <h2>Booking And Ticket</h2>
    <form id="registerForm" method="POST" action="book_add.php">
        <div class="grid">
        <label for="total_seats">Total Seats:</label>
        <input type="text" id="total_seats" name="total_seats" required>

        <label for="total_price">Total Price:</label>
        <input type="email" id="total_price" name="total_price" readonly required>
        
        <label>PricePer Seat:</label>
        <input type="number" id="pricePerSeat" required>

        <!-- <label for="seat_number">Seat Number:</label>
        <input type="text" id="seat_number" name="seat_number" required> -->

        <label for="booking_date">Booking Date</label>
        <input type="date" name="booking_date" id="booking_date" required>

       

        <label for="bus_id">Bus:</label>
                <?php
                        $sql1 = "SELECT * FROM buses"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="bus_id" id="bus_id" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['bus_id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>

                <label for="book_type">Book Type:</label>
                <?php
                        $sql1 = "SELECT * FROM book_type"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="book_type" id="book_type" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                ?>
                </select>

                <label for="user_id">User Name:</label>
                <?php
                        $sql1 = "SELECT * FROM users"; // Query to fetch locations
                        $result = $conn->query($sql1); // Execute the query
                     
                        if ($result && $result->num_rows > 0) { // Check if there are results
                        ?>
                            <select name="user_id" id="user_id" required>
                                <option value="" disabled selected>Select Here</option>
                                <?php
                                
                                while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
                                 
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['username']) . "</option>";
                                }
                            }
                ?>
                </select>
            </div>
            <div>
            <button type="submit">Book </button>  <button type="button" id="clearBtn">Clear </button>
            </div>
    </form>
</div>
<section class="schedule">
<div>
    <h2>Booking View</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Bus Name</th>
                <th>Total Seats</th>
                <th>Booking Type</th>
                <th>Total Price</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
           if ($bookings && $bookings->num_rows > 0) {
            $counter = 1;
            while ($row = $bookings->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td>" . htmlspecialchars($row['bus_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total_seats']) . "</td>";
                echo "<td>" . htmlspecialchars($row['book_type_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total_price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>
                        <button class='button busUpdateBtn' data-Book-id='" . htmlspecialchars($row['booking_id']) . "'>Book Edit</button>
                        <button class='button busDeleteBtn btn-secondary' 
                            onclick=\"if(confirm('Are you sure you want to delete this book?')) 
                            window.location.href='book_delete.php?book_id=" . htmlspecialchars($row['booking_id']) . "'\">Book Delete</button>
                      </td>";
                echo "</tr>";
            }
            } else {
                echo "<tr><td colspan='7'>No bookings found for this bus.</td></tr>";
            }
                ?>
                </tbody>
            </table>

    
            </section>


</div>

    <div>
    <?php include 'footer.html'; ?>
    </div>
</body>
<script>
        const totalSeats = document.getElementById('total_seats');
        const totalPrice = document.getElementById('total_price');
        const pricePerSeat = document.getElementById('pricePerSeat');

        // Function to update total price
        function updateTotalPrice() {
            const seats = parseInt(totalSeats.value) || 0; // Default to 0 if empty
            const price = parseInt(pricePerSeat.value) || 0; // Default to 0 if empty
            totalPrice.value = seats * price; // Calculate total price
        }

        // Add event listeners for real-time updates
        totalSeats.addEventListener('input', updateTotalPrice);
        pricePerSeat.addEventListener('input', updateTotalPrice);


        document.getElementById("clearBtn").addEventListener("click", function() {
        // Reset the form
        document.getElementById("registerForm").reset();

        // Optionally reset selects to their placeholder
        const selects = document.querySelectorAll("select");
        selects.forEach(select => {
            select.selectedIndex = 0;
        });
    });
    </script>
</html>