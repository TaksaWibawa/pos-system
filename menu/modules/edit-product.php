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

// Fetch the product data from the database
$productQuery = $conn->prepare("SELECT ProductName, Price, Stock, Description FROM Product WHERE CompanyID = ? AND ProductID = ?");
$productQuery->bind_param("ii", $companyID, $productID);
$productQuery->execute();
$productResult = $productQuery->get_result();

// Check if the product exists
if ($productResult->num_rows == 0) {
    header('Location: ../../dashboard.php?error=product_not_found');
    exit();
}

// Retrieve the product data
$productData = $productResult->fetch_assoc();

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProductName = $_POST['product_name'];
    $newPrice = $_POST['price'];
    $newStock = $_POST['stock'];
    $newDescription = $_POST['description'];

    // Update the product in the database
    $updateQuery = $conn->prepare("UPDATE Product SET ProductName = ?, Price = ?, Stock = ?, Description = ? WHERE CompanyID = ? AND ProductID = ?");
    $updateQuery->bind_param("ssdsii", $newProductName, $newPrice, $newStock, $newDescription, $companyID, $productID);
    $updateQuery->execute();

    if ($updateQuery->affected_rows > 0) {
        header('Location: ../../dashboard.php?success=product_updated');
        exit();
    } else {
        header('Location: ../../dashboard.php?error=update_failed');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Edit Product</title>
    <link rel="stylesheet" href="../../css/input.css">
    <link rel="stylesheet" href="../../css/form.css">
    <style>
        body {
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        form {
            width: 30%;
        }
    </style>
</head>
<body>
    <!-- Edit Product Form -->
    <h2>Edit Product</h2>
    <form method="POST" action="">
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="product_name" value="<?php echo $productData['ProductName']; ?>" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $productData['Price']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $productData['Stock']; ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo $productData['Description']; ?></textarea>

        <button type="submit" class="custom-button">Update</button>
    </form>
</body>
</html>
