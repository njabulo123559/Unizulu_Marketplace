<?php

include __DIR__ . '/../../../config/db.php'; // Ensure correct path to the database connection file

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the home page or login page after logging out
header('Location: home.php'); // Change this to the correct path of your homepage or login page
exit();

?>
