<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($id && password_verify($password, $hashed_password)) {
        // Login success
        $_SESSION['loggedin'] = true;
        $_SESSION['userid'] = $id;
        $_SESSION['email'] = $email;

        // Check if the user is admin
        if ($email == 'admin@thelocalcart.com') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit(); // Make sure to stop the script after redirecting
    } else {
        echo "Invalid email or password";
    }

    $stmt->close();
    $conn->close();
}
