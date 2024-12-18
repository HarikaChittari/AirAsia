<?php
session_start(); // Start the session

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the session is not active
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php'; // Include the database connection file

// Handle card deletion
if (isset($_GET['delete_id'])) {
    $giftcard_id = $_GET['delete_id'];
    $query = "DELETE FROM GIFTCARD WHERE giftcard_id = $giftcard_id";
    $result = $conn->query($query);
    if (!$result) die("Database deletion failed: " . $conn->error);

    // Redirect to the card list after deleting
    header("Location: card-list.php");
    exit();
}

// Check if the form is submitted to update the card
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $giftcard_id = $_POST['id'];
    $name = $conn->real_escape_string($_POST['cardName']);
    $value = $conn->real_escape_string($_POST['cardValue']);
    $points_required = $conn->real_escape_string($_POST['points']);
    $type = $conn->real_escape_string($_POST['cardType']);
    
    // Handle file upload if a new image is uploaded
    if (isset($_FILES['cardImage']) && $_FILES['cardImage']['error'] == UPLOAD_ERR_OK) {
        $imageDirectory = './img/uploads/';
        if (!file_exists($imageDirectory)) {
            mkdir($imageDirectory, 0777, true); // Create the directory if it doesn't exist
        }
        $imagepath = $imageDirectory . basename($_FILES['cardImage']['name']);
        move_uploaded_file($_FILES['cardImage']['tmp_name'], $imagepath);
    } else {
        // If no new image uploaded, keep the existing image path
        $imagepath = $_POST['existingImage'];
    }

    // Update the gift card details in the database
    $query = "UPDATE GIFTCARD SET name='$name', value='$value', points_required='$points_required', type='$type', imagepath='$imagepath' WHERE giftcard_id=$giftcard_id";
    $result = $conn->query($query);
    if (!$result) die("Database update failed: " . $conn->error);

    // Redirect to the details page after updating
    header("Location: card-details.php?giftcard_id=" . $giftcard_id);
    exit();
} else {
    // Display the existing gift card details in the form
    $giftcard_id = $_GET['id'];
    $query = "SELECT * FROM GIFTCARD WHERE giftcard_id = $giftcard_id";
    $result = $conn->query($query);
    if (!$result) die("Database access failed: " . $conn->error);

    $row = $result->fetch_assoc();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Gift Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/card-update.css" />
    <script>
        function confirmDelete(giftcardId) {
            if (confirm("Are you sure you want to delete this gift card? This action cannot be undone.")) {
                window.location.href = 'card-update.php?delete_id=' + giftcardId; // Redirect to delete the card
            }
        }
    </script>
</head>
<body>
    <div class="card-update-main">
        <div class="back-icon">
            <a href="card-details.php?giftcard_id=<?php echo $giftcard_id; ?>">
                <img src="./img/icons/back.svg" alt="Back">
            </a>
        </div>
        <div class="card-update-form">
            <h1>Update Gift Card</h1>
            <form id="update-form" action="card-update.php" method="post" enctype="multipart/form-data">
                <!-- Hidden field to hold the gift card ID -->
                <input type="hidden" name="id" value="<?php echo $giftcard_id; ?>">
                <input type="hidden" name="existingImage" value="<?php echo $row['imagepath']; ?>">

                <label for="cardName">Gift Card Name</label>
                <input type="text" name="cardName" placeholder="Enter Card Name" id="cardName" value="<?php echo htmlspecialchars($row['name']); ?>" required />

                <label for="cardValue">Gift Card Value</label>
                <input type="number" step="0.01" name="cardValue" placeholder="0.0" id="cardValue" value="<?php echo htmlspecialchars($row['value']); ?>" required />

                <label for="points">Gift Card Points</label>
                <input type="number" name="points" placeholder="0" id="points" value="<?php echo htmlspecialchars($row['points_required']); ?>" required />

                <label for="cardType">Gift Card Type</label>
                <select name="cardType" id="cardType" required>
                    <option value="Wallet cash" <?php if ($row['type'] == 'Wallet cash') echo 'selected'; ?>>Wallet Cash</option>
                    <option value="Discount" <?php if ($row['type'] == 'Discount') echo 'selected'; ?>>Discount</option>
                    <option value="Voucher" <?php if ($row['type'] == 'Voucher') echo 'selected'; ?>>Voucher</option>
                </select>

                <label for="cardImage">Upload Card Image</label>
                <input type="file" name="cardImage" accept="image/png, image/gif, image/jpeg, image/jpg" />
                
                <div class="preview">
                    <img src="<?php echo $row['imagepath']; ?>" alt="Current image">
                </div>

                <div class="card-actions">
                    <a href="card-list.php" class="delete btn">Cancel</a>
                    <button type="submit" class="update btn">Update Card</button>
                    <button type="button" class="delete btn" onclick="confirmDelete(<?php echo $giftcard_id; ?>)">Delete Card</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
