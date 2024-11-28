<?php
include 'db_connection.php';
// File: login.php
session_start();

// File: login.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['useremail']);
    $password = trim($_POST['password']);

    // Fetch the user from the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $roleid = $user['role_id'];

        $sql2 = "SELECT name FROM role WHERE id = '$roleid'";
        $result2 = $conn->query($sql2);
        $role = $result2->fetch_assoc();

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role['name'];

            header('Location:index.php');
            exit();
        } else {
            $message ="Invalid password!";
        }
    } else {
        $message= "Invalid email!";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form id="loginForm" method="POST" action="login.php">
            <label for="useremail">Useremail:</label>
            <input type="text" id="useremail" name="useremail" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <div class="link">
                <a href="register.php">Register</a>
            </div>
        </form>
    </div>
    <?php if (!empty($message)): ?>
        <script>
            alert("<?php echo $message; ?>");
        </script>
    <?php endif; ?>
</body>
<script>
// File: login.js
document.getElementById("loginForm").addEventListener("submit", function (e) {
    const useremail = document.getElementById("useremail").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!useremail || !password) {
        alert("Please fill out all fields!");
        e.preventDefault(); // Prevent form submission
    }
});
</script>

</html>
