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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the order ID from the submitted form
    $orderID = $_POST['order_id'];

    // Update the order status to 'Validated'
    $updateStatusQuery = $conn->prepare("UPDATE salesreport SET Status = 'Validated' WHERE ReportID = ? AND CompanyID = ?");
    $updateStatusQuery->bind_param("ii", $orderID, $companyID);
    $updateStatusQuery->execute();

    // Redirect to the same page to refresh the order list
    header('Location: ../dashboard.php?success=order_validated');
    exit();
}

// Fetch the orders from the database
$ordersQuery = $conn->prepare("SELECT ReportID, Total, SaleDate FROM salesreport WHERE CompanyID = ? AND Status = 'Ongoing'");
$ordersQuery->bind_param("i", $companyID);
$ordersQuery->execute();
$ordersResult = $ordersQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Order</title>
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="container">
        <table class="table" border="1px" width="70%">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Sale Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ordersResult->num_rows == 0) { ?>
                    <tr>
                        <td colspan="4">No orders available</td>
                    </tr>
                <?php } else { ?>
                    <?php while ($order = $ordersResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $order['ReportID']; ?></td>
                            <td><?php echo $order['Total']; ?></td>
                            <td><?php echo $order['SaleDate']; ?></td>
                            <td>
                                <form method="POST" action="menu/manage-order.php">
                                    <input type="hidden" name="order_id" value="<?php echo $order['ReportID']; ?>">
                                    <input type="submit" value="Validate">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
