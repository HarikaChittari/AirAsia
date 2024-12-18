<?php
session_start(); // Start session management

// Initialize error and success messages
$error_message = "";
$success_message = "";

// Check if the user is logged in and has a valid userId in session
if (!isset($_SESSION['user_id'])) {
    // Redirect if not logged in
    header("Location: login.php");
    exit();
}

require 'db_connect.php'; // Include the database connection

// Fetch the user role from the database based on userId
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM USER WHERE userId = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and retrieve their role
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $role = $user['role'];

    // If the role is neither 'admin' nor 'Admin', redirect to a different page
    if (strtolower($role) !== 'admin') {
        header("Location: login.php"); // Redirect to the homepage or a no-access page
        exit();
    }
} else {
    // If user doesn't exist in the database, redirect to login page
    header("Location: login.php");
    exit();
}

$stmt->close();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    // Validate input
    if (empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
        $error_message = "Please fill in all fields.";
    } else {
        // Hash the password using password_hash
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO USER (userName, password, firstName, lastName, role) VALUES (?, ?, ?, ?, ?)");
        $role = 'Customer'; // New users are added with the 'user' role by default
        $stmt->bind_param("sssss", $username, $hashed_password, $firstName, $lastName, $role);

        if ($stmt->execute()) {
            $success_message = "Customer added successfully!";
            // Redirect to card-list.php after success
            header("Location: card-list.php");
            exit(); // Make sure to call exit after the header redirect
        } else {
            $error_message = "Error adding customer. Please try again.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer | Air Asia Rewards</title>
    <link rel="stylesheet" href="./css/addnewCustomer.css">
</head>

<body>
    <div class="addnewCustomer-main">
        <!-- Back Button with Icon -->
        <div class="back-icon">
            <a href="card-list.php">
                <img src="./img/icons/back.svg" alt="Back">
            </a>
        </div>

        <h1>Add New Customer</h1>

        <div class="addnewCustomer-form">
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="cust-add.php" method="POST" id="update-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" required>

                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required>

                <div class="card-actions">
                    <button type="submit" class="btn update">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>