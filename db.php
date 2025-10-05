<?php
$host = "localhost";      // or 127.0.0.1
$user = "root";           // default in XAMPP/WAMP
$pass = "";               // default is empty, change if you set a password
$db   = "beantage_db";    // name from your SQL file

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set UTF-8 encoding
$conn->set_charset("utf8mb4");
?>
