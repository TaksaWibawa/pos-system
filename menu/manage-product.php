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

// Fetch the list of products from the database
$productQuery = $conn->prepare("SELECT ProductID, ProductName, Price, Stock FROM Product WHERE CompanyID = ?");
$productQuery->bind_param("i", $companyID);
$productQuery->execute();
$productResult = $productQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Manage Products</title>
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/button.css">
</head>
<body>
    <!-- Manage Products Content -->
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($productResult->num_rows === 0) { ?>
                    <tr>
                        <td colspan="5">No products available</td>
                    </tr>
                <?php } else { ?>
                    <?php while ($row = $productResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['ProductID']; ?></td>
                            <td><?php echo $row['ProductName']; ?></td>
                            <td><?php echo $row['Price']; ?></td>
                            <td><?php echo $row['Stock']; ?></td>
                            <td>
                                <a href="menu/modules/edit-product.php?product_id=<?php echo $row['ProductID']; ?>" class="button edit-button">Edit</a>
                                <a href="menu/modules/delete-product.php?product_id=<?php echo $row['ProductID']; ?>" class="button delete-button">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
