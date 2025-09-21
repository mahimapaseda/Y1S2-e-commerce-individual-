<?php
session_start();


if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: index.php"); 
    exit();
}


include('config/db.php');

$userId = $_SESSION['user_id'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm-password'];


if ($password !== $confirmPassword) {
    echo "Passwords do not match.";
    exit();
}


$sql = "UPDATE users SET email = ?, phone = ?";
$params = [$email, $phone];


if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql .= ", password = ?";
    $params[] = $hashedPassword;
}

$sql .= " WHERE id = ?";
$params[] = $userId;

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

if ($stmt->execute()) {
    echo "Profile updated successfully.";
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
