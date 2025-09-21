<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || $_SESSION['email'] != 'admin@thelocalcart.com') {
    header("Location: index.php");
    exit();
}

// Handle deletion request
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM contact_form_submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Redirect back to admin_contacts.php after deletion
        header("Location: admin_contacts.php?deleted=true");
        exit();
    } else {
        // Handle error
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch all contact form submissions
$result = $conn->query("SELECT * FROM contact_form_submissions ORDER BY submitted_at DESC");
$contacts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts - The Local Cart Admin</title>
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
            <h2>Manage Contact Form Submissions</h2>

            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
                <p class="success-message">Contact form submission deleted successfully.</p>
            <?php endif; ?>

            <!-- Display all contact form submissions -->
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                        <td><?php echo htmlspecialchars($contact['message']); ?></td>
                        <td><?php echo htmlspecialchars($contact['submitted_at']); ?></td>
                        <td>
                            <a href="admin_contacts.php?id=<?php echo $contact['id']; ?>" class="button">Delete</a>
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
