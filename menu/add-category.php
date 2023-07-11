<?php
require '../database/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Identifier'])) {
    header('Location: ../login.php?error=not_logged_in');
    exit();
}

// Get the company ID from the session
$companyID = $_SESSION['CompanyID'];
$role = $_SESSION['Role'];

// Check if the user has the necessary role to access this page
if ($role !== 'Admin') {
    header('Location: ../dashboard.php?error=access_denied');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the category name from the submitted form
    $categoryName = $_POST['category_name'];

    // Check if the category name already exists
    $checkQuery = $conn->prepare("SELECT CategoryID FROM Category WHERE CategoryName = ? AND CompanyID = ?");
    $checkQuery->bind_param("si", $categoryName, $companyID);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        // Category name already exists
        header('Location: ../dashboard.php?error=category_exists');
        exit();
    }

    // Insert the category into the database
    $insertQuery = $conn->prepare("INSERT INTO Category (CategoryName, CompanyID) VALUES (?, ?)");
    $insertQuery->bind_param("si", $categoryName, $companyID);
    $insertQuery->execute();

    // Redirect to the manage categories page with success message
    header('Location: ../dashboard.php?success=category_added');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Add Category</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'category_exists') { ?>
            <div class="error">Category name already exists. Please choose a different name.</div>
        <?php } ?>
        <form role="form" action="menu/add-category.php" method="post">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
