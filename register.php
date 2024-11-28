<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="form-container">
        <h2>User Register</h2> 
        <form id="registerForm" method="POST" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="useremail">Useremail:</label>
            <input type="email" id="useremail" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="tel_number">Phone Number:</label>
            <input type="text" id="tel_number" name="tel_number" maxlength="10" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <button type="submit">Register</button><br><br>
            <div><a href="agent.php">Agent Register...</a> | <a href="login.php">login...</a></div>
        </form>
    </div>
</body>
<script>
    // File: register.js
document.getElementById("registerForm").addEventListener("submit", function (e) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const tel_number = document.getElementById("tel_number").value.trim();
    const address = document.getElementById("address").value.trim();
    const email = document.getElementById("email").value.trim();

    if (!username || !password || !tel_number || !address || !email) {
        alert("All fields are required!");
        e.preventDefault(); // Prevent form submission
    } else if (tel_number.length !== 10 || isNaN(tel_number)) {
        alert("Phone number must be a valid 10-digit number!");
        e.preventDefault();
    }
});

</script>
</html>


<?php
// File: register.php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $tel_number = $conn->real_escape_string($_POST['tel_number']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);

    // Insert data into the `users` table
    $sql = "INSERT INTO users (username, password, tel_number, address,email) VALUES ('$username', '$password', '$tel_number', '$address', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
