<?php
require '../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = $_POST['companyName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'Admin'; // Set the default role as Admin

    // Check if the company name already exists
    $checkCompanyQuery = $conn->prepare("SELECT CompanyID FROM Company WHERE CompanyName = ?");
    $checkCompanyQuery->bind_param("s", $companyName);
    $checkCompanyQuery->execute();
    $checkCompanyQuery->store_result();

    if ($checkCompanyQuery->num_rows > 0) {
        $checkCompanyQuery->bind_result($companyID);
        $checkCompanyQuery->fetch();
    } else {
        // Insert data into the Company table
        $companyQuery = $conn->prepare("INSERT INTO Company (CompanyName) VALUES (?)");
        $companyQuery->bind_param("s", $companyName);
        $companyQuery->execute();
        $companyID = $companyQuery->insert_id;
    }

    // Check if the username already exists
    $checkUserQuery = $conn->prepare("SELECT UserID FROM User WHERE Username = ?");
    $checkUserQuery->bind_param("s", $username);
    $checkUserQuery->execute();
    $checkUserQuery->store_result();

    if ($checkUserQuery->num_rows > 0) {
        header('Location: ../register.php?error=user_exists');
        exit();
    }

    // Insert data into the User table
    $userQuery = $conn->prepare("INSERT INTO User (CompanyID, Username, Password, Role) VALUES (?, ?, ?, ?)");
    $userQuery->bind_param("isss", $companyID, $username, $password, $role);
    $userQuery->execute();

    if ($userQuery->affected_rows > 0) {
        header('Location: ../login.php?success=registered');
        exit();
    } else {
        header('Location: ../register.php?error=failed');
        exit();
    }
}
?>
