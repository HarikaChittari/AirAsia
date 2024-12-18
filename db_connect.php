<?php
// Database credentials
$hn = 'localhost:8889'; // or adjust the port if different, e.g., 'localhost:3308' or 'localhost'
$db = 'rewards';
$un = 'root';           // Replace with your MySQL username if different
$pw = 'root';               // Replace with your MySQL password; for MAMP, use 'root'

// Create a new MySQLi connection
$conn = new mysqli($hn, $un, $pw, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>