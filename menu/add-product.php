<?php
require '../database/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Identifier'])) {
    header('Location: login.php?error=not_logged_in');
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

// Fetch the categories from the database
$categoriesQuery = $conn->prepare("SELECT CategoryID, CategoryName FROM Category WHERE CompanyID = ?");
$categoriesQuery->bind_param("i", $companyID);
$categoriesQuery->execute();
$categoriesResult = $categoriesQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Restaurant POS - Add Product</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form.css">
</head>

<body>
  <div class="container">
    <form role="form" action="menu/modules/add-product.php" method="post">
      <div class="form-group">
        <label for="product_name">Product Name:</label>
        <input type="text" class="form-control" id="product_name" name="product_name" required>
      </div>
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label for="category">Category:</label>
        <select class="form-control" id="category" name="category" required>
          <option value="">Select Category</option>
          <?php
          while ($categoryData = $categoriesResult->fetch_assoc()) {
            echo '<option value="' . $categoryData['CategoryID'] . '">' . $categoryData['CategoryName'] . '</option>';
          }
          ?>
        </select>
      </div>
      <button type="submit">Add Product</button>
    </form>
  </div>
</body>
</html>
