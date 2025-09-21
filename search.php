<?php
include 'db.php'; 

// Get the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';


$searchQuery = $conn->real_escape_string($query);


$sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            
        </nav>
    </header>

    <main>
        <section class="search-results">
            <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>

            <?php
            if ($result->num_rows > 0) {
               
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h4>' . htmlspecialchars($row['name']) . '</h4>';
                    echo '<h2>Rs.' . htmlspecialchars($row['price']) . '/=</h2>';
                    echo '<a href="product' . htmlspecialchars($row['id']) . '.php" class="btn">View Product</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No results found</p>';
            }
            ?>

        </section>
    </main>

    <footer>
        <p>&copy; 2024 The Local Cart. All rights reserved.</p>
    </footer>

</body>
</html>

<?php

$conn->close();
?>
