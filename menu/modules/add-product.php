<?php
require '../../database/connect.php';
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

// Check if the user has the necessary role to access this page
if ($role !== 'Admin') {
    header('Location: ../dashboard.php?error=access_denied');
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $categoryID = $_POST['category'];

    // Insert the new product into the database
    $insertQuery = $conn->prepare("INSERT INTO Product (CompanyID, CategoryID, ProductName, Price, Description) VALUES (?, ?, ?, ?, ?)");
    $insertQuery->bind_param("iisds", $companyID, $categoryID, $productName, $price, $description);
    $insertQuery->execute();

    if ($insertQuery->affected_rows > 0) {
        header('Location: ../../dashboard.php?success=product_added');
        exit();
    } else {
        header('Location: ../../dashboard.php?error=insert_failed');
        exit();
    }
}
?>