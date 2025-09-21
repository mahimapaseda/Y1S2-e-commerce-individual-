<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || $_SESSION['email'] != 'admin@thelocalcart.com') {
    header("Location: index.php");
    exit();
}

// Fetch summary data for dashboard
// Total users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $result->fetch_assoc()['total_users'];

// Total products
$result = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$total_products = $result->fetch_assoc()['total_products'];

// Total orders
$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $result->fetch_assoc()['total_orders'];

// Recent contact form submissions
$result = $conn->query("SELECT * FROM contact_form_submissions ORDER BY submitted_at DESC LIMIT 5");
$recent_contacts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - The Local Cart</title>
    <link rel="stylesheet" href="css/admin.css">
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
            <h2>Admin Dashboard</h2>

            <!-- Display summary data -->
            <div class="dashboard-summary">
                <div class="summary-box">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="summary-box">
                    <h3>Total Products</h3>
                    <p><?php echo $total_products; ?></p>
                </div>
                <div class="summary-box">
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
            </div>

            <!-- Display recent contact form submissions -->
            <h3>Recent Contact Form Submissions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                        <td><?php echo htmlspecialchars($contact['message']); ?></td>
                        <td><?php echo htmlspecialchars($contact['submitted_at']); ?></td>
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
