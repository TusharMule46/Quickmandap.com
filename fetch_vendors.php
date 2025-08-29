<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weddinginfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive filter parameters
$category = $_POST['category'] ?? '';
$location = $_POST['location'] ?? '';
$price = $_POST['price'] ?? '';
$rating = $_POST['rating'] ?? '';
$contact = $_POST['contact'] ?? '';
$email = $_POST['email'] ?? '';

// Build query dynamically
$sql = "SELECT * FROM vendors WHERE 1=1";

if (!empty($category)) $sql .= " AND category = '$category'";
if (!empty($location)) $sql .= " AND location = '$location'";
if (!empty($price)) $sql .= " AND price_range = '$price'";
if (!empty($rating)) $sql .= " AND rating >= $rating";
if (!empty($contact)) $sql .= " AND contact_phone>= $contact";
if (!empty($email)) $sql .= " AND contact_email >= $email";

$result = $conn->query($sql);

$vendors = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $vendors[] = $row;
    }
}

echo json_encode($vendors);
$conn->close();
?>
