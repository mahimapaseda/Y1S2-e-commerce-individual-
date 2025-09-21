<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
            break;
        }
    }
}

header("Location: cart.php");
exit();
?>
