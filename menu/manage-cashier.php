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
    $query = "DELETE FROM user WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        // Redirect to the cashier list page
        header('Location: ../dashboard.php?success=cashier_deleted');
        exit();
    } else {
        header('Location: ../dashboard.php?error=unknown_error');
        exit();
    }
}

// Fetch the list of cashiers from the database
$query = "SELECT UserID, Username, Password FROM user WHERE Role = 'Cashier'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Manage Cashiers</title>
    <link rel="stylesheet" href="../css/table.css">
</head>
<body>
    <div class="container">
        <?php if ($result->num_rows > 0) { ?>
            <table class="table" border="1px" width="70%">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Password']; ?></td>
                            <td>
                                <a href="menu/manage-cashier.php?delete_id=<?php echo $row['UserID']; ?>" onclick="return confirm('Are you sure you want to delete this cashier?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No cashiers found.</p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>
