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
if ($role !== 'Admin') {
    header('Location: ../dashboard.php?error=access_denied');
    exit();
}

// Fetch product sales data with product name
$query = "SELECT ps.SalesID, ps.ProductID, p.ProductName, ps.QuantitySold, ps.SalesAmount
          FROM ProductSales ps
          INNER JOIN Product p ON ps.ProductID = p.ProductID
          WHERE ps.CompanyID = $companyID";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Restaurant POS - Product Sales Report</title>
  <link rel="stylesheet" href="../css/table.css">
  <link rel="stylesheet" href="../css/input.css">
</head>
<body>
  <!-- Product Sales Report Content -->
  <div class="container">
    <div class="filter">
        <label for="product">Name Product:</label>
        <input type="text" id="product" name="product" value="" oninput="filterProduct()">
    </div>
    <table class="table" border="1px" width="70%">
      <thead>
        <tr>
          <th>No. Sales</th>
          <th>Product Name</th>
          <th>Quantity Sold</th>
          <th>Sales Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows == 0) { ?>
          <tr>
            <td colspan="4">No product sales report available</td>
          </tr>
        <?php } ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['SalesID']; ?></td>
            <td><?php echo $row['ProductName']; ?></td>
            <td><?php echo $row['QuantitySold']; ?></td>
            <td><?php echo $row['SalesAmount']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
<script src="../js/filter.js"></script>
</html>
