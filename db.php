<?php
// db.php - Database connection file using MySQLi (matching your current setup)

$host = "localhost"; 
$user = "root";
$password = "";
$dbname = "weddinginfo"; // Your actual database name

// Create MySQLi connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to prevent character encoding issues
$conn->set_charset("utf8");
?>