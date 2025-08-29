<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$package = isset($_GET['package']) ? htmlspecialchars($_GET['package']) : 'Unknown';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>Booking Successful!</h2>
    <p>You booked the <strong><?php echo ucfirst($package); ?></strong> package.</p>
    <a href="index.html">Go to Home</a>
</body>
</html>
