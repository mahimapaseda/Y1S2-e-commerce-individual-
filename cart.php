<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
if (!$isLoggedIn) {
    // Include JavaScript for alert and redirection
    echo '<script>
            alert("You need to login first.");
            window.location.href = "index.php";
          </script>';
    exit();
}

// Handle Remove Item action
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];

    // Remove item from cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
            break;
        }
    }
}

// Handle Update Quantity action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

// Calculate cart total
$cart_total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - The Local Cart</title>
    <link rel="stylesheet" href="css/cart.css">
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
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <a href="cart.php"><button class="cart-button">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</button></a>
            <ul class="nav-buttons">
                <?php if ($isLoggedIn): ?>
                    <li><a href="account.php"><button class="button">Account</button></a></li>
                    <li><a href="logout.php"><button class="button">Logout</button></a></li>
                <?php else: ?>
                    <li><button class="button" onclick="openForm('signupForm')">Sign Up</button></li>
                    <li><button class="button" onclick="openForm('loginForm')">Login</button></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="cart-container">
            <div class="cart-header">
                <h2>Your Shopping Cart</h2>
            </div>

            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <form method="POST" action="">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <tr>
                                    <td><img src="images/<?php echo htmlspecialchars($item['id']); ?>.png" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                    <td class="item-name"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="item-price">Rs.<?php echo number_format($item['price'], 2); ?></td>
                                    <td class="item-quantity">
                                        <input type="number" name="quantity[<?php echo htmlspecialchars($item['id']); ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                                    </td>
                                    <td class="item-total">Rs.<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td class="item-actions">
                                        <a href="?remove=<?php echo htmlspecialchars($item['id']); ?>" class="remove-item">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-summary">
                        <h3>Cart Summary</h3>
                        <p><strong>Total Amount:</strong> Rs.<?php echo number_format($cart_total, 2); ?></p>
                        <button type="submit" name="update_cart" class="btn">Update Cart</button>
                        <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
                    </div>
                </form>
            <?php else: ?>
                <p>Your cart is currently empty.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

    <script src="script/script.js"></script>
</body>

</html>
