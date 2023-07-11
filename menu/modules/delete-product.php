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
    header('Location: ../../dashboard.php?error=access_denied');
    exit();
}

// Check if the product ID is provided in the query string
if (!isset($_GET['product_id'])) {
    header('Location: ../../dashboard.php?error=product_id_missing');
    exit();
}

$productID = $_GET['product_id'];

// Delete the product from the database
$deleteQuery = $conn->prepare("DELETE FROM Product WHERE CompanyID = ? AND ProductID = ?");
$deleteQuery->bind_param("ii", $companyID, $productID);
$deleteQuery->execute();

if ($deleteQuery->affected_rows > 0) {
    header('Location: ../../dashboard.php?success=product_deleted');
    exit();
} else {
    header('Location: ../../dashboard.php?error=delete_failed');
    exit();
}
?>
