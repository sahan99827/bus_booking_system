<?php
include 'db_connection.php';
session_start(); 

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
</head>
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