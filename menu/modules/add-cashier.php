<?php
require '../../database/connect.php';
session_start();

// Check if the user is logged in and has the necessary role
if (!isset($_SESSION['Identifier']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: ../../login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the form data
    if (empty($username) || empty($password)) {
        $error = "Please fill in all the fields.";
    } else {
        // Insert the new cashier into the database
        $query = "INSERT INTO user (CompanyID, Username, Password, Role)
                  VALUES (?, ?, ?, 'Cashier')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $_SESSION['CompanyID'], $username, $password);

        if ($stmt->execute()) {
            // Redirect to the cashier list page
            header('Location: ../../dashboard.php?success=cashier_added');
            exit();
        } else {
            header('Location: ../../dashboard.php?error=unknown_error');
            exit();
        }
    }
}
?>