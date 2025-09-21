<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['signupEmail'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $phone = $_POST['signupPhone'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO users (email, password, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $phone);

    if ($stmt->execute()) {
        echo "New record created successfully";
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
