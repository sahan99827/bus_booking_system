<?php
session_start(); // Start the session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to the login page
exit();

?>