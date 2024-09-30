<?php
// /config/db.php

$host = '127.0.0.1';  // or 'localhost'
$db = 'marketplace_db';  // Your database name
$user = 'root';  // Your MySQL username
$pass = 'Danz@gmail2002';  // Your MySQL password

// Using MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";  // Optional: To test the connection
