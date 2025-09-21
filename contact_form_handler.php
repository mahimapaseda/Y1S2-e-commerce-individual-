<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thelocalcart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contact_form_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

// Set parameters and execute
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

if ($stmt->execute()) {
    // Redirect to index.php
    header("Location: index.php?success=true");
    exit(); // Make sure to stop script execution after redirection
} else {
    // Redirect with an error message
    header("Location: index.php?success=false");
    exit(); // Make sure to stop script execution after redirection
}

// Close connections
$stmt->close();
$conn->close();
?>
