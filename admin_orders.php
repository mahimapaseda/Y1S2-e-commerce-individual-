<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || $_SESSION['email'] != 'admin@thelocalcart.com') {
    header("Location: index.php");
    exit();
}

// Fetch all orders with user details
$query = "
    SELECT orders.id, users.email AS user_email, orders.total_amount, orders.shipping_address, orders.city, orders.zip_code, orders.country, orders.order_date, orders.status
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.order_date DESC
";
$result = $conn->query($query);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update the order status
    $update_query = "UPDATE orders SET status='$status' WHERE id=$order_id";
    if ($conn->query($update_query)) {
        echo "<p>Status updated successfully!</p>";
    } else {
        echo "<p>Error updating status: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - The Local Cart</title>
    <link rel="stylesheet" href="css/admin_orders.css">
</head>
<body>

    <header>
        <nav>
            <div class="logo">
                <h1>The Local Cart - Admin Panel</h1>
            </div>
            <ul class="nav-links">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="admin_products.php">Manage Products</a></li>
                <li><a href="admin_orders.php">View Orders</a></li>
                <li><a href="admin_contacts.php">Manage Contacts</a></li>
                <li><a href="logout.php"><button class="button">Logout</button></a></li>
            </ul>
        </nav>
    </header>

    <div class="content-container">
        <main>
            <h2>Order Management</h2>

            <!-- Display all orders -->
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Email</th>
                        <th>Total Amount</th>
                        <th>Shipping Address</th>
                        <th>City</th>
                        <th>Zip Code</th>
                        <th>Country</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
                        <td><?php echo htmlspecialchars($order['city']); ?></td>
                        <td><?php echo htmlspecialchars($order['zip_code']); ?></td>
                        <td><?php echo htmlspecialchars($order['country']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <!-- Status Dropdown -->
                            <form action="admin_orders.php" method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="admin_orders.php?id=<?php echo htmlspecialchars($order['id']); ?>">Update</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </main>
    </div>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

</body>
</html>
