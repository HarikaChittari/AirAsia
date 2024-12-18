<?php
// Start session and check if the user is logged in as a customer
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php'; // Include the database connection


// Fetch user ID from session
$user_id = intval($_SESSION['user_id']);

// Check if giftcard_id is provided in the URL
if (!isset($_GET['giftcard_id'])) {
    echo "<p class='error'>No gift card selected for redemption. <a href='./card-list.php'>Go back to card list</a>.</p>";
    exit();
}

$giftcard_id = intval($_GET['giftcard_id']);

// Fetch user's account details
$stmt = $conn->prepare("SELECT accountId, points FROM Account WHERE userId = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='error'>Account not found. Please contact support.</p>";
    exit();
}

$user_account = $result->fetch_assoc();
$account_id = $user_account['accountId'];
$available_points = $user_account['points'];

// Fetch gift card details
$giftcard_stmt = $conn->prepare("SELECT * FROM GiftCard WHERE giftcard_id = ?");
$giftcard_stmt->bind_param("i", $giftcard_id);
$giftcard_stmt->execute();
$giftcard_result = $giftcard_stmt->get_result();

if ($giftcard_result->num_rows === 0) {
    echo "<p class='error'>Gift card not found. Please select a valid gift card.</p>";
    exit();
}

$giftcard = $giftcard_result->fetch_assoc();
$points_required = $giftcard['points_required'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Gift Card</title>
    <link rel="stylesheet" href="./css/redeem.css">
</head>
<body>
    <div class="container">
        <h1>Redeem Gift Card</h1>
        <p><strong>Gift Card:</strong> <?php echo htmlspecialchars($giftcard['name']); ?></p>
        <p><strong>Points Required:</strong> <?php echo htmlspecialchars($points_required); ?></p>
        <p><strong>Available Points:</strong> <?php echo $available_points; ?></p>

        <?php if ($points_required <= $available_points): ?>
            <form action="redeem-process.php" method="POST">
                <input type="hidden" name="card_id" value="<?php echo $giftcard_id; ?>">
                <input type="hidden" name="points_required" value="<?php echo $points_required; ?>">
                <button type="submit" class="btn-redeem">Redeem</button>
            </form>
        <?php else: ?>
            <p class="error">You do not have enough points to redeem this gift card.</p>
        <?php endif; ?>

        <a href="./card-list.php" class="btn-back">Back to Card List</a>
    </div>
</body>
</html>
