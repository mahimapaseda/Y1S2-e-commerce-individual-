<?php
session_start();

// Handle Add to Cart action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Add product to session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product already exists in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            $product_exists = true;
            break;
        }
    }

    // If product does not exist in the cart, add it
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1 // Default quantity is 1
        ];
    }

    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Fetch product details from the database
include 'db.php';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();

// Check if the user is logged in and if they are an admin
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - The Local Cart</title>
    <link rel="stylesheet" href="css/productstyle.css">
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
        <section class="product-page">
            <div class="product">
                <div class="product-image">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <?php if (!empty($product['brand'])): ?>
                        <p><b>Brand:</b> <?php echo htmlspecialchars($product['brand']); ?></p>
                    
                    <?php endif; ?>
                    <p class="price"><strong>Price:</strong> Rs.<?php echo htmlspecialchars(number_format($product['price'], 2)); ?>/=</p>
                    <p class="description"><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <section class="about-contact" id="About">
        <div class="about">
            <h2>About Us</h2>
            <p>Welcome to The Local Cart, your one-stop shop for the best products at the best prices. Our mission is to provide high-quality products with exceptional customer service. Browse through our collection and enjoy exclusive discounts and offers.
            </p>
            <p><b>E-mail us</b>: info@thelocalcart.com</p>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31674.915492163003!2d80.64665761647949!3d7.083681805231021!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae37a21e4a51a5d%3A0x20889f2b30be4487!2sAppachige%20Kade%20(The%20Tea%20Shop)!5e0!3m2!1sen!2slk!4v1720112753393!5m2!1sen!2slk"
                    width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="contact" id="Contact">
            <h2>Contact Us</h2>
            <form class="contact-form">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <button type="submit" class="btn">Submit</button>
            </form>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

    <!-- Sign Up Form -->
    <div id="signupForm" class="form-popup">
        <form class="form-container">
            <h2>Sign Up</h2>
            <label for="signupEmail"><b>Email</b></label>
            <input type="email" id="signupEmail" placeholder="Enter Email" required>
            <label for="password"><b>Create Password</b></label>
            <input type="password" placeholder="Create Password" name="password" required>
            <label for="confirm-password"><b>Confirm Password</b></label>
            <input type="password" placeholder="Confirm Password" name="confirm-password" required>
            <label for="signupPhone"><b>Phone Number</b></label>
            <input type="tel" id="signupPhone" placeholder="Enter Phone Number" required>
            <button type="submit" class="btn">Sign Up</button>
            <button type="button" class="btn cancel" onclick="closeForm('signupForm')">Close</button>
            <p>Already have an account? <a href="#" onclick="switchForm('signupForm', 'loginForm')">Login</a></p>
        </form>
    </div>

    <!-- Login Form -->
    <div id="loginForm" class="form-popup">
        <form class="form-container">
            <h2>Login</h2>
            <label for="loginEmail"><b>Email</b></label>
            <input type="email" id="loginEmail" placeholder="Enter Email" required>
            <label for="loginPassword"><b>Password</b></label>
            <input type="password" id="loginPassword" placeholder="Enter Password" required>
            <button type="submit" class="btn">Login</button>
            <button type="button" class="btn cancel" onclick="closeForm('loginForm')">Close</button>
            <p>Don't have an account? <a href="#" onclick="switchForm('loginForm', 'signupForm')">Sign up</a></p>
        </form>
    </div>

    <script src="script.js"></script>
</body>

</html>
