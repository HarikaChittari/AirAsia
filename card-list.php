<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php'; // Connect to the database

// Query to fetch the user's role
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM user WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

// Query to fetch all gift cards
$query = "SELECT * FROM giftcard";
$result = $conn->query($query);
if (!$result) {
    die("Database access failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Cards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/card-list.css"> 
</head>
<body>
    <div class="card-list-main">
        <!-- Page Links -->
        <div class="page-links">
            
            <!-- Only show "New Card" button if the logged-in user is not a customer -->
            <?php if ($user_role !== 'Customer'): ?>
                <a href="./card-add.php" class="link-btn">
                    <img src="./img/icons/plus.svg" alt="+">
                    <div>New Card</div>
                </a> 
            <?php endif; ?>
            
            <!-- Only show "New Customer" button if the logged-in user is not a customer -->
            <?php if ($user_role !== 'Customer'): ?>
                <a href="./cust-add.php" class="link-btn">
                    <img src="./img/icons/plus.svg" alt="+">
                    <div>New Customer</div>
                </a>
            <?php endif; ?>
            
            <a href="?action=logout" class="link-btn logout-btn">
                <div>Logout</div>
            </a>
        </div>

        <!-- Gift Cards List -->
        <h2 class="dm-sans">Gift Cards</h2>
        <div class="card-list">
            <?php
            // Loop through each gift card in the result set
            while ($row = $result->fetch_assoc()) {
                // Dynamically render each card with a clickable link to card-details.php
                echo "<div class='card'>
                        <a href='card-details.php?giftcard_id={$row['giftcard_id']}' style='background-image: url({$row['imagepath']});' class='card-image'></a>
                        <div class='card-info'>
                            <p class='card-name'><a href='card-details.php?giftcard_id={$row['giftcard_id']}'>{$row['name']}</a></p>
                            <h3 class='card-points'>{$row['points_required']} pts</h3>
                        </div>
                      </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
