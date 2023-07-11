<?php
require '../database/connect.php';
session_start();

// Check if the user is logged in and has the necessary role
if (!isset($_SESSION['Identifier']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Add Cashier</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="container">
        <form method="POST" action="menu/modules/add-cashier.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Cashier</button>
            </div>
            <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
        </form>
    </div>
</body>
</html>
