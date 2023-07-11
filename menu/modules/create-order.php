<?php
require '../../database/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Identifier'])) {
    header('Location: ../../login.php');
    exit();
}

// Get the company ID from the session
$companyID = $_SESSION['CompanyID'];
$role = $_SESSION['Role'];

// Check if the user has the necessary role to access this page
if ($role !== 'Cashier') {
    header('Location: ../../dashboard.php?error=access_denied');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $productID = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get the product information from the database
    $productQuery = $conn->prepare("SELECT Price, Stock, CategoryID FROM product WHERE ProductID = ?");
    $productQuery->bind_param("i", $productID);
    $productQuery->execute();
    $productQuery->bind_result($price, $stock, $categoryID);

    if ($productQuery->fetch()) {
        // Calculate the total amount
        $totalAmount = $price * $quantity;

        // Check if there is enough stock
        if ($stock >= $quantity) {
            try {
                // Close the product query result set
                $productQuery->close();

                // Start the transaction
                $conn->begin_transaction();

                // Check if there is an ongoing order for the cashier
                $ongoingOrderQuery = $conn->prepare("SELECT ReportID FROM salesreport WHERE CompanyID = ? AND Status = 'Ongoing' LIMIT 1");
                $ongoingOrderQuery->bind_param("i", $companyID);
                $ongoingOrderQuery->execute();
                $ongoingOrderQuery->store_result();

                // Check if there is an ongoing order for the cashier
                if ($ongoingOrderQuery->num_rows > 0) {
                    $ongoingOrderQuery->bind_result($reportID);
                    $ongoingOrderQuery->fetch();

                    // Update the existing ongoing order
                    $updateOrderQuery = $conn->prepare("UPDATE salesreport SET Total = Total + ?, SaleDate = NOW() WHERE ReportID = ?");
                    $updateOrderQuery->bind_param("di", $totalAmount, $reportID);
                    $updateOrderQuery->execute();
                } else {
                    // Insert a new salesreport for the cashier
                    $salesReportQuery = $conn->prepare("INSERT INTO salesreport (CompanyID, ProductID, Total, SaleDate, Status) VALUES (?, ?, ?, NOW(), 'Ongoing')");
                    $salesReportQuery->bind_param("idd", $companyID, $productID, $totalAmount);
                    $salesReportQuery->execute();

                    // Get the generated ReportID
                    $reportID = $salesReportQuery->insert_id;
                }

                // Insert into salesreport_details table
                $salesReportDetailsQuery = $conn->prepare("INSERT INTO salesreport_details (ReportID, CategoryID, Quantity) VALUES (?, ?, ?)");
                $salesReportDetailsQuery->bind_param("iii", $reportID, $categoryID, $quantity);
                $salesReportDetailsQuery->execute();

                // Update the product stock
                $newStock = $stock - $quantity;
                $updateStockQuery = $conn->prepare("UPDATE product SET Stock = ? WHERE ProductID = ?");
                $updateStockQuery->bind_param("ii", $newStock, $productID);
                $updateStockQuery->execute();

                // Commit the transaction
                $conn->commit();

                // Redirect to success page or show success message
                header('Location: ../../dashboard.php?success=order_created');
                exit();
            } catch (Exception $e) {
                // Rollback the transaction
                $conn->rollback();

                // Redirect to error page or show error message
                header('Location: ../../dashboard.php?error=order_failed');
                exit();
            }
        } else {
            header('Location: ../../dashboard.php?error=insufficient_stock');
            exit();
        }
    } else {
        header('Location: ../../dashboard.php?error=product_not_found');
        exit();
    }
}
?>