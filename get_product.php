<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT name, description, price, category, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $description, $price, $category, $image);
    $stmt->fetch();
    echo json_encode([
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'category' => $category,
        'image' => $image
    ]);
    $stmt->close();
}

$conn->close();
?>
