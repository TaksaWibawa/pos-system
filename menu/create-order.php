<?php
require '../database/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Identifier'])) {
    header('Location: ../login.php');
    exit();
}

// Get the company ID from the session
$companyID = $_SESSION['CompanyID'];
$role = $_SESSION['Role'];

// Check if the user has the necessary role to access this page
if ($role !== 'Cashier') {
    header('Location: ../dashboard.php?error=access_denied');
    exit();
}

// Fetch the products from the database
$productsQuery = $conn->prepare("SELECT ProductID, ProductName, Price, Stock FROM product WHERE CompanyID = ?");
$productsQuery->bind_param("i", $companyID);
$productsQuery->execute();
$productsResult = $productsQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="container">
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST" action="menu/modules/create-order.php">
            <label for="product">Product:</label>
            <select name="product_id" id="product">
                <?php while ($product = $productsResult->fetch_assoc()) { ?>
                    <option value="<?php echo $product['ProductID']; ?>">
                        <?php echo $product['ProductName']; ?> - <?php echo $product['Price']; ?> (Stock: <?php echo $product['Stock']; ?>)
                    </option>
                <?php } ?>
            </select>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" required>
            <br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
