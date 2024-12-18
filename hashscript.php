<?php

// Admin. Username = bsmith Password = mysecret
// User. Username = pjones Password = acrobat

require_once 'db_connect.php';

// Create a new database connection
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Fetch all records from the USER table
$user_result = $conn->query("SELECT * FROM USER");
if ($user_result->num_rows > 0) {
    while ($row = $user_result->fetch_assoc()) {
        // Hash the password
        $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
        
        // Update the USER table with the hashed password
        $stmt = $conn->prepare("UPDATE USER SET password = ? WHERE userId = ?");
        $stmt->bind_param("si", $hashed_password, $row['userId']);
        $stmt->execute();
        $stmt->close();
    }
    echo "Passwords in the USER table updated successfully!";
} else {
    echo "No users found in the USER table.";
}

// Close the database connection
$conn->close();

?>
