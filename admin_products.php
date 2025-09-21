<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@thelocalcart.com') {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding or updating products
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];

    // Handle file upload
    $image = $_FILES['product_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    
    // Ensure the uploads directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory with permissions
    }
    
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // If image upload is successful
        if ($product_id) {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            $stmt->bind_param("ssdsi", $name, $description, $price, $image, $product_id);
        } else {
            // Add new product
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $image);
        }

        if ($stmt->execute()) {
            echo "Product " . ($product_id ? "updated" : "added") . " successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Fetch the product image for deletion
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Delete product record
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // Delete the image file if exists
        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }
        echo "Product deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing products for display
$result = $conn->query("SELECT * FROM products");

// Check if editing a specific product
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$product = null;

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/admin_products.css">

</head>
<body>
<header>
        <nav>
            <a href="admin.php">Admin Home</a>
            <a href="admin_products.php">Manage Products</a>
            <a href="index.php">Customer interface</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h1>Manage Products</h1>

        <!-- Display existing products -->
        <h2>Product List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><img src="uploads/<?php echo $row['image']; ?>" alt="Product Image" width="100"></td>
                        <td>
                            <a href="admin_products.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="admin_products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Form to add or update product -->
        <h2><?php echo $edit_id ? 'Edit Product' : 'Add New Product'; ?></h2>
        <form method="POST" action="admin_products.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $edit_id; ?>">

            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo $edit_id ? htmlspecialchars($product['name']) : ''; ?>" required>

            <label for="product_description">Description:</label>
            <textarea id="product_description" name="product_description" required><?php echo $edit_id ? htmlspecialchars($product['description']) : ''; ?></textarea>

            <label for="product_price">Price:</label>
            <input type="number" id="product_price" name="product_price" step="0.01" value="<?php echo $edit_id ? htmlspecialchars($product['price']) : ''; ?>" required>

            <label for="product_image">Image:</label>
            <input type="file" id="product_image" name="product_image">

            <?php if ($edit_id && $product['image']): ?>
                <p>Current Image: <img src="uploads/<?php echo $product['image']; ?>" alt="Product Image" width="100"></p>
            <?php endif; ?>

            <button type="submit"><?php echo $edit_id ? 'Update Product' : 'Add Product'; ?></button>
        </form>
    </main>
</body>
</html>

<?php $conn->close(); ?>
