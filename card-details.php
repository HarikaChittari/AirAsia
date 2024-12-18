<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variable
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the session is not active
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php'; // Connect to the database

// Check if the gift card ID is provided in the URL
if (isset($_GET['giftcard_id'])) {
    $giftcard_id = intval($_GET['giftcard_id']);

    // Query to fetch gift card details
    $query = "SELECT * FROM giftcard WHERE giftcard_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $giftcard_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Gift card not found.");
    }

    $stmt->close();
} else {
    die("No gift card ID provided.");
}

// Fetch the user role from the session
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM user WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Card Details</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/card-details.css">
</head>
<body>
    <div class="card-details-main">
        <div class="back-icon">
            <a href="./card-list.php">
                <img src="./img/icons/back.svg" alt="Back">
            </a>
        </div>
        <h2 class="dm-sans">Gift Card Details</h2>
        <div class="card">
            <div class="card-image" style="background-image: url('<?php echo $row['imagepath']; ?>');"></div>
            <div class="card-info">
                <p class="card-name"><?php echo $row['name']; ?></p>
                <h3 class="card-points"><?php echo $row['points_required']; ?> pts</h3>
                <p class="desc">
                    <b>Description:</b> Redeem this gift card for <?php echo $row['value']; ?>
                </p>
            </div>
            <div class="card-actions">
                <!-- Hide delete and update buttons if the logged-in user is a customer -->
                <?php if ($user_role !== 'Customer'): ?>
                    <a href="./card-update.php?id=<?php echo $row['giftcard_id']; ?>" class="update btn">Update</a>
                <?php endif; ?>
                <a href="./redeem.php?giftcard_id=<?php echo $row['giftcard_id']; ?>" class="redeem btn">Redeem</a>
            </div>
        </div>
    </div>
</body>
</html>
