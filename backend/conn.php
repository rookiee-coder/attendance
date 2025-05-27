<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "masa2002";
$dbname = "attendance_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to ensure proper encoding
mysqli_set_charset($conn, "utf8mb4");
?>