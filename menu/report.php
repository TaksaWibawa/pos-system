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

// Get the company name from the database
$companyQuery = $conn->prepare("SELECT CompanyName FROM Company WHERE CompanyID = ?");
$companyQuery->bind_param("i", $companyID);
$companyQuery->execute();
$companyQuery->store_result();

// Define the default filter values
$currentYear = date('Y');
$currentMonth = date('m');

// Fetch sales report data with filters
$query = "SELECT sr.ReportID, sr.SaleDate, SUM(sr.Total) AS TotalSales
          FROM salesreport sr
          WHERE sr.CompanyID = $companyID
          GROUP BY sr.ReportID;";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Food Sales Report</title>
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/input.css">
</head>
<body>
<div class="container">
    <div class="filter">
      <label for="year">Year:</label>
      <input type="number" id="year" name="year" value="<?php echo $currentYear; ?>" min="2000" max="2100" oninput="filter()">
      <label for="month">Month:</label>
      <input type="number" id="month" name="month" value="<?php echo $currentMonth; ?>" min="1" max="12" oninput="filter()">
    </div>

    <table class="table" border="1px" width="70%">
        <thead>
        <tr>
            <th>No. Report</th>
            <th>Date</th>
            <th>Total Sales</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows == 0) { ?>
            <tr>
                <td colspan="3">No food sales report available</td>
            </tr>
        <?php } ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['ReportID']; ?></td>
                <td><?php echo $row['SaleDate']; ?></td>
                <td><?php echo $row['TotalSales']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
<script src="../js/filter.js"></script>
</html>
