<?php
require 'database/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Identifier'])) {
    header('Location: login.php?error=not_logged_in');
    exit();
}

// Get the company ID from the session
$userID = $_SESSION['Identifier'];

// Get the user data from the database
$userQuery = $conn->prepare("SELECT CompanyID FROM User WHERE UserID = ?");
$userQuery->bind_param("i", $userID);
$userQuery->execute();
$userQuery->store_result();

$userQuery->bind_result($companyID);
$userQuery->fetch();

// Get the company name from the database
$companyQuery = $conn->prepare("SELECT CompanyName FROM Company WHERE CompanyID = ?");
$companyQuery->bind_param("i", $companyID);
$companyQuery->execute();
$companyQuery->store_result();

// Get the alert message from the URL parameters
$alertMessage = isset($_GET['error']) ? $_GET['error'] : (isset($_GET['success']) ? $_GET['success'] : '');

?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .container {
            margin: 20px;
        }
        .alert {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: #fff;
            border-radius: 4px;
            font-size: 14px;
            z-index: 9999;
        }
        .alert.success {
            background-color: #4caf50;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="header">
                <p>
                    <?php
                    $companyQuery->bind_result($companyName);
                    $companyQuery->fetch();
                    echo $companyName;
                    ?>
                </p>
                <div class="line"></div>
            </div>
            <ul class="menu">
                <?php if ($_SESSION['Role'] == 'Admin') { ?>
                    <li class="menu-link">
                        <a href="menu/add-product.php">Add New Product</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/manage-product.php">Manage Products</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/add-category.php">Add New Category</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/manage-category.php">Manage Category</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/report.php">Sales Report</a>
                    </li>
                    <!-- <li class="menu-link">
                        <a href="menu/product-sales.php">Product Sales Report</a>
                    </li> -->
                    <li class="menu-link">
                        <a href="menu/add-cashier.php">Add Cashier</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/manage-cashier.php">Manage Cashiers</a>
                    </li>

                <?php } elseif ($_SESSION['Role'] == 'Cashier') { ?>
                    <li class="menu-link">
                        <a href="menu/create-order.php">Create Order</a>
                    </li>
                    <li class="menu-link">
                        <a href="menu/manage-order.php">Manage Orders</a>
                    </li>
                    <!-- <li class="menu-link">
                        <a href="menu/manage-seats.php">Manage Seats</a>
                    </li> -->
                <?php } ?>

                <li class="menu-link logout">
                    <a href="auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="navbar">
                <h2 id="menu-title"></h2>
            </div>
            <div class="content"></div>
        </div>
    </div>

    <?php if ($alertMessage !== '') { ?>
        <script>
            // Function to create an alert element
            function createAlert(message, type) {
                const alertElement = document.createElement('div');
                alertElement.className = `alert ${type}`;
                alertElement.innerHTML = message;
                document.body.appendChild(alertElement);
                // Remove the alert after 3 seconds
                setTimeout(() => {
                    document.body.removeChild(alertElement);
                }, 3000);
            }
            // Check if the alert is an error or success
            const isError = <?php echo isset($_GET['error']) ? 'true' : 'false'; ?>;
            const alertMessage = "<?php echo $alertMessage; ?>";
            // Create the corresponding alert based on the type
            if (isError) {
                createAlert(alertMessage, 'error');
            } else {
                createAlert(alertMessage, 'success');
            }
        </script>
    <?php } ?>

    <script src="js/dashboard.js"></script>
    <script src="js/filter.js"></script>
</body>
</html>
