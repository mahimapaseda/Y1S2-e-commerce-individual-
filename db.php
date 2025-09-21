<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = ""; // Change this if your MySQL server has a password
$dbname = "thelocalcart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to close the database connection
function closeConnection($conn) {
    $conn->close();
}

// Function to handle SQL errors
function handleSQLError($conn) {
    if ($conn->error) {
        echo "SQL Error: " . $conn->error;
    }
}
?>
