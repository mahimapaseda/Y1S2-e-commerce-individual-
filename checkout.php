<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "thelocalcart";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart is set and not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit();
}

// Calculate the total amount
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];
    $cardNumber = $_POST['card-number'];
    $expiryDate = $_POST['expiry-date'];
    $cvv = $_POST['cvv'];

    // Insert order into 'orders' table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, city, zip_code, country) VALUES (?, ?, ?, ?, ?, ?)");
    $userId = 1; // Assuming the user is logged in, replace with the actual user ID from your session
    $stmt->bind_param("idssss", $userId, $total, $address, $city, $zip, $country);
    $stmt->execute();
    
    // Get the last inserted order ID
    $orderId = $stmt->insert_id;

    // Insert each item in the cart into 'order_items' table
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    // Insert payment details into 'payments' table
    $stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, card_number, expiry_date, cvv) VALUES (?, ?, ?, ?, ?)");
    $paymentMethod = "Card"; // Assuming payment is done by card
    $stmt->bind_param("issss", $orderId, $paymentMethod, $cardNumber, $expiryDate, $cvv);
    $stmt->execute();

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to order success page
    header("Location: order_success.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - The Local Cart</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>The Local Cart</h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.php#HOME">Home</a></li>
                <li><a href="index.php#Products">Products</a></li>
                <li><a href="index.php#About">About</a></li>
                <li><a href="index.php#Contact">Contact</a></li>
            </ul>
            
        </nav>
    </header>

    <div class="checkout-container">
        <h2>Checkout</h2>
        <form class="checkout-form" action="checkout.php" method="POST">
            <h3>Shipping Details</h3>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
            
            <label for="zip">ZIP Code:</label>
            <input type="text" id="zip" name="zip" required>
            
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>
            
            <h3>Payment Information</h3>
            <label for="card-number">Card Number:</label>
            <input type="text" id="card-number" name="card-number" required>
            
            <label for="expiry-date">Expiry Date:</label>
            <input type="text" id="expiry-date" name="expiry-date" required>
            
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            
            <div class="checkout-summary">
                <p><strong>Total Amount:</strong> Rs.<?php echo number_format($total, 2); ?></p>
                <button type="submit" class="checkout-button">Proceed to Payment</button>
            </div>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

    <!-- Include JavaScript for form handling -->
    <script src="script/script.js"></script>
</body>
</html>
