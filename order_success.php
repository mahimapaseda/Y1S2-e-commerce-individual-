<?php
session_start();

// Optionally clear the cart after successful order
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - The Local Cart</title>
    <link rel="stylesheet" href="css/order_success.css">
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

    <div class="success-container">
        <h2>Order Successful!</h2>
        <p>Thank you for your order. Your payment has been processed successfully.</p>
        <p><a href="index.php" class="home-button">Return to Home</a></p>
    </div>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>
</body>
</html>
