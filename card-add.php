<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variable
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the session is not active
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form fields are set
    $name = isset($_POST['cardName']) ? $conn->real_escape_string($_POST['cardName']) : '';
    $value = isset($_POST['cardValue']) ? $conn->real_escape_string($_POST['cardValue']) : '0.00';
    $points_required = isset($_POST['points']) ? $conn->real_escape_string($_POST['points']) : '0';
    $type = isset($_POST['cardType']) ? $conn->real_escape_string($_POST['cardType']) : '';

    // Handle file upload for the image
    $imagepath = '';
    if (isset($_FILES['cardImage']) && $_FILES['cardImage']['error'] == UPLOAD_ERR_OK) {
        $imageDirectory = './img/uploads/';
        if (!file_exists($imageDirectory)) {
            mkdir($imageDirectory, 0777, true); // Create the directory if it doesn't exist
        }
        $imagepath = $imageDirectory . basename($_FILES['cardImage']['name']);
        if (!move_uploaded_file($_FILES['cardImage']['tmp_name'], $imagepath)) {
            die("Failed to upload image.");
        }
    }

    // Ensure all required fields are filled
    if (empty($name) || empty($value) || empty($points_required) || empty($type) || empty($imagepath)) {
        die("Please fill in all fields and upload an image.");
    }

    if ($_FILES['cardImage']['size'] > 20 * 1024 * 1024) { // 20MB limit
        die("The uploaded file is too large. Maximum size allowed is 20MB.");
    }

    // Insert data into the database
    $query = "INSERT INTO GIFTCARD (name, value, points_required, type, imagepath) VALUES ('$name', '$value', '$points_required', '$type', '$imagepath')";
    $result = $conn->query($query);
    if (!$result) die("Database insertion failed: " . $conn->error);

    // Redirect to the card list after adding
    header("Location: card-list.php");
    exit();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Gift Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/card-update.css" />
</head>

<body>
    <div class="card-update-main">
        <div class="back-icon">
            <a href="./card-list.php">
                <img src="./img/icons/back.svg" alt="Back">
            </a>
        </div>
        <div class="card-update-form">
            <h1>Add Gift Card</h1>
            <form id="update-form" action="card-add.php" method="post" enctype="multipart/form-data">
                <label for="cardName">Gift Card Name</label>
                <input type="text" name="cardName" placeholder="Enter Card Name" id="cardName" required />

                <label for="cardValue">Gift Card Value</label>
                <input type="number" step="0.01" name="cardValue" placeholder="0.0" id="cardValue" required />

                <label for="points">Gift Card Points</label>
                <input type="number" name="points" placeholder="0" id="points" required />

                <label for="cardType">Gift Card Type</label>
                <select name="cardType" id="cardType" required>
                    <option value="Wallet cash">Wallet Cash</option>
                    <option value="Discount">Discount</option>
                    <option value="Voucher">Voucher</option>
                </select>

                <label for="cardImage">Upload Card Image</label>
                <input type="file" name="cardImage" accept="image/png, image/gif, image/jpeg" required />

                <div class="card-actions">
                    <a href="./card-list.php" class="delete btn">Cancel</a>
                    <button type="submit" class="update btn">Add Card</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>
