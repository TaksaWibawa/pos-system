<?php
require '../database/connect.php';

if (isset($_POST["Log"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = $conn->prepare("SELECT * FROM user WHERE Username = ? AND Password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $query->store_result();
    if ($query->num_rows > 0) {
        $query->bind_result($userID, $companyID, $username, $password, $role);
        $query->fetch();

        session_start();
        $_SESSION['Identifier'] = $userID;
        $_SESSION['CompanyID'] = $companyID;
        $_SESSION['Username'] = $username;
        $_SESSION['Password'] = $password;
        $_SESSION['Role'] = $role;
        
        header('Location: ../dashboard.php');
        exit();
    } else {
        header('Location: ../login.php?error=invalid');
        exit();
    }
}
?>