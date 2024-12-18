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

// Check if giftcard_id and points_required are provided in the POST data
if (!isset($_POST['card_id']) || !isset($_POST['points_required'])) {
    echo "<p class='error'>Invalid request. <a href='./card-list.php'>Go back to card list</a>.</p>";
    exit();
}

$giftcard_id = intval($_POST['card_id']);
$points_required = intval($_POST['points_required']);

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

if ($available_points < $points_required) {
    echo "<p class='error'>You do not have enough points to redeem this gift card. <a href='./redeem.php?giftcard_id=" . $giftcard_id . "'>Try again</a>.</p>";
    exit();
}

// Deduct points from user's account
$new_points = $available_points - $points_required;
$update_stmt = $conn->prepare("UPDATE Account SET points = ? WHERE accountId = ?");
$update_stmt->bind_param("ii", $new_points, $account_id);
$update_stmt->execute();

// Record the redemption in the Redemption table
$redeem_date = date("Y-m-d H:i:s");
$insert_stmt = $conn->prepare("INSERT INTO Redemption (date, accountId, cardId, pointsRedeemed) VALUES (?, ?, ?, ?)");
$insert_stmt->bind_param("siii", $redeem_date, $account_id, $giftcard_id, $points_required);
$insert_stmt->execute();

// Redirect to a success page
header("Location: redemption-success.php");
exit();
?>
