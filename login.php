<?php
session_start(); // Start session management

require 'db_connect.php'; // Include the database connection

// Initialize error message
$error_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user details
    $stmt = $conn->prepare("SELECT userId, username, password, role FROM USER WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the gift cards page
            header("Location: card-list.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Air Asia Rewards</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="login-main-holder">
        <div class="login-card">
            <div class="login-head">
                <h1>Login</h1>
            </div>

            <div class="login-form">
                <?php if (!empty($error_message)): ?>
                    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
