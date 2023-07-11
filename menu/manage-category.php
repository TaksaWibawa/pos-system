<?php
require '../database/connect.php';
session_start();

// Check if the user is logged in and has the necessary role
if (!isset($_SESSION['Identifier']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

// Delete cashier if requested
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Delete the cashier from the database
    $query = "DELETE FROM category WHERE CategoryID = ? and CompanyID = ".$_SESSION['CompanyID'];
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        header('Location: ../dashboard.php?success=category_deleted');
        exit();
    } else {
        header('Location: ../dashboard.php?error=unknown_error');
        exit();
    }
}

// Fetch the list of cashiers from the database
$query = "SELECT CategoryID, CategoryName FROM category where CompanyID = ".$_SESSION['CompanyID'];
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Manage Category</title>
    <link rel="stylesheet" href="../css/table.css">
</head>
<body>
    <div class="container">
        <table class="table" border="1px" width="70%">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['CategoryName']; ?></td>
                                <td>
                                    <a href="menu/manage-category.php?delete_id=<?php echo $row['CategoryID']; ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No category available</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>
