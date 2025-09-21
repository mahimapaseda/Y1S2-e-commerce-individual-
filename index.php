<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Local Cart</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="script/script.js" defer></script>
</head>

<body>

    <header>
        <nav>
            <div class="logo">
                <h1>The Local Cart</h1>
            </div>
            <ul class="nav-links">
                <li><a href="#Home">Home</a></li>
                <li><a href="#Products">Products</a></li>
                <li><a href="#About">About</a></li>
                <li><a href="#Contact">Contact</a></li>
            </ul>
            <div class="search-bar">
    <form action="search.php" method="GET">
        <input type="text" name="query" placeholder="Search...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>
<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                <?php if ($_SESSION['email'] === 'admin@thelocalcart.com'): ?>
                    <li><a href="admin.php">Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>

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

    <div class="content-container">
        <main>

            <section class="home" id="Home">
                <div class="main-content">
                    <div class="welcome-discounts">
                        <h2>Welcome to The Local Cart</h2>
                        <h3>Up to 50% Off All Products in Our Store</h3>
                        <button class="button" onclick="openForm('signupForm')">Register on Our Page</button>
                    </div>
                    <div class="product-slider">
                        <div class="slider">
                            <div class="slide"><img src="images/product1.png" alt="Product 1"></div>
                            <div class="slide"><img src="images/product2.jpg" alt="Product 2"></div>
                            <div class="slide"><img src="images/product3.png" alt="Product 3"></div>
                            <div class="slide"><img src="images/product4.png" alt="Product 4"></div>
                        </div>
                    </div>
                </div>
            </section>
            </main>

            <section class="products" id="Products">
                <h2 class="section-title">Our Featured Products</h2>
                <div class="product-grid">
                <?php
                include 'db.php';
                $result = $conn->query("SELECT * FROM products LIMIT 9"); // Adjust query as needed
                while ($product = $result->fetch_assoc()):
                ?>
                <div class="product">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p><h2>Rs.<?php echo htmlspecialchars($product['price']); ?></h2></p>
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="button">View Details</a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
                </div>
            </section>
       
    </div>

    <section class="about-contact" id="About">
        <div class="about">
            <h2>About Us</h2>
            <p>Welcome to The Local Cart, your one-stop shop for the best products at the best prices. Our mission is to provide high-quality products with exceptional customer service. Browse through our collection and enjoy exclusive discounts and offers.</p>
            <p><b>E-mail us</b>: info@thelocalcart.com</p>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31674.915492163003!2d80.64665761647949!3d7.083681805231021!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae37a21e4a51a5d%3A0x20889f2b30be4487!2sAppachige%20Kade%20(The%20Tea%20Shop)!5e0!3m2!1sen!2slk!4v1720112753393!5m2!1sen!2slk" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="contact" id="Contact">
            <h2>Contact Us</h2>
            <!-- Contact Form -->
<form action="contact_form_handler.php" method="POST" class="contact-form">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject">
    
    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="4" required></textarea>
    
    <button type="submit">Send Message</button>
</form>

        </div>
    </section>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

    <!-- Sign Up Form Popup -->
    <div id="signupForm" class="form-popup">
        <form class="form-container" action="signup.php" method="post">
            <h2>Sign Up</h2>
            <label for="signupEmail"><b>Email</b></label>
            <input type="email" id="signupEmail" name="signupEmail" placeholder="Enter Email" required>
            <label for="password"><b>Create Password</b></label>
            <input type="password" name="password" placeholder="Create Password" required>
            <label for="confirm-password"><b>Confirm Password</b></label>
            <input type="password" name="confirm-password" placeholder="Confirm Password" required>
            <label for="signupPhone"><b>Phone Number</b></label>
            <input type="tel" id="signupPhone" name="signupPhone" placeholder="Enter Phone Number" required>
            <button type="submit" class="btn">Sign Up</button>
            <button type="button" class="btn cancel" onclick="closeForm('signupForm')">Close</button>
        </form>
    </div>

    <!-- Login Form Popup -->
    <div id="loginForm" class="form-popup">
        <form class="form-container" action="login.php" method="post">
            <h2>Login</h2>
            <label for="loginEmail"><b>Email</b></label>
            <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter Email" required>
            <label for="loginPassword"><b>Password</b></label>
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter Password" required>
            <button type="submit" class="btn">Login</button>
            <button type="button" class="btn cancel" onclick="closeForm('loginForm')">Close</button>
        </form>
    </div>

</body>

</html>
