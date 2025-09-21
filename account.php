<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

$userid = $_SESSION['userid'];

// Update account details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $stmt = $conn->prepare("UPDATE users SET email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $email, $phone, $address, $userid);

    if ($stmt->execute()) {
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch user details
$stmt = $conn->prepare("SELECT email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($email, $phone, $address);
$stmt->fetch();
$stmt->close();

// Fetch user's orders
$orderQuery = $conn->prepare("SELECT id, total_amount, order_date, status FROM orders WHERE user_id = ?");
$orderQuery->bind_param("i", $userid);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$orderQuery->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="css/account.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <h1>The Local Cart</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php#Home">Home</a></li>
            <li><a href="index.php#Products">Products</a></li>
            <li><a href="index.php#About">About</a></li>
            <li><a href="index.php#Contact">Contact</a></li>
        </ul>
        
        <a href="cart.php"><button class="cart-button">Cart</button></a>
        <ul class="nav-buttons">
            <li><a href="account.php"><button class="button">Account</button></a></li>
            <li><a href="logout.php"><button class="button">Logout</button></a></li>
        </ul>
    </nav>
</header>

<div class="content-container">
    <main>
        <section class="account-details">
            <h2>Account Details</h2>
            <form action="account.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($address); ?></textarea>
                
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </section>

        <!-- My Orders Section -->
        <section class="my-orders">
            <h2>My Orders</h2>
            <?php if ($orderResult->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orderResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="button">View</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No orders found for this user.</p>
<?php endif; ?>

        </section>
    </main>
</div>

<footer>
    <p>&copy; 2024 The Local Cart. All rights reserved.</p>
</footer>

</body>
</html>
