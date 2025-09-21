<?php
$servername = "localhost";
$username = "root"; // change this if your MySQL user is different
$password = ""; // change this to your MySQL user's password
$dbname = "thelocalcart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
