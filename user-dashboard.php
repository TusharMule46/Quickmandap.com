<?php
include 'db.php'; // Make sure this matches your actual database connection file
session_start();

// Check if user is logged in and has correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'header.php';

$user_id = $_SESSION['user_id'];

// Fetch user profile - using correct column names from your database
$sql_user = "SELECT name, email, mobile_no FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $name = $user_data['name'];
    $email = $user_data['email'];
    $contact = $user_data['mobile_no']; // or whatever your column name is
} else {
    // Handle case where user not found
    $name = "Unknown User";
    $email = "N/A";
    $contact = "N/A";
}
$stmt->close();

// Fetch user orders
$sql_orders = "SELECT order_id, order_date, status FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt_orders = $conn->prepare($sql_orders);
if ($stmt_orders) {
    $stmt_orders->bind_param("i", $user_id);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
} else {
    $result_orders = null;
}

// Fetch stats - total orders, pending orders
$sql_stats = "SELECT 
    COUNT(*) AS total_orders,
    SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending_orders
    FROM orders WHERE user_id = ?";
$stmt_stats = $conn->prepare($sql_stats);
if ($stmt_stats) {
    $stmt_stats->bind_param("i", $user_id);
    $stmt_stats->execute();
    $result_stats = $stmt_stats->get_result();
    if ($result_stats->num_rows > 0) {
        $stats = $result_stats->fetch_assoc();
        $total_orders = $stats['total_orders'];
        $pending_orders = $stats['pending_orders'];
    } else {
        $total_orders = 0;
        $pending_orders = 0;
    }
    $stmt_stats->close();
} else {
    $total_orders = 0;
    $pending_orders = 0;
}
?>

<h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>

<div class="profile-section">
    <h3>Your Profile</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact); ?></p>
</div>

<div class="stats-section">
    <div class="stats-box">
        Total Orders<br><?php echo $total_orders; ?>
    </div>
    <div class="stats-box">
        Pending Orders<br><?php echo $pending_orders; ?>
    </div>
</div>

<div class="orders-section">
    <h3>Your Orders</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_orders && $result_orders->num_rows > 0) {
                while ($row = $result_orders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                    echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div> <!-- Close container from header.php -->

<?php
if (file_exists('footer.php')) {
    include 'footer.php';
} else {
    echo '</body></html>';
}
?>