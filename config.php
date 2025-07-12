<?php

// Database credentials for localhost
$servername = "localhost";
$username = "root";
$password = "";
$database = "kloth";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
