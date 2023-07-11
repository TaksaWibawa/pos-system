<?php
require 'database/connect.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Restaurant POS - Register</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>
  <div class="container">
    <div class="jumbotron">
      <div class="logo">
        <h1>Register Page</h1>
      </div>
      <form role="form" action="auth/register.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Company Name" name="companyName" required>
        </div>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Username" name="username" required>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit" name="Register">Register</button>
      </form>
      <div class="text-center register">
        <p>Already have an account? <a href="login.php" class="btn btn-link">Login</a></p>
      </div>
      <?php
            if (isset($_GET['error']) && $_GET['error'] === 'user_exists') {
                echo '<div class="alert alert-danger fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Account Exist. Please try again!</div>';
                echo '</div>';
            }
      ?>
    </div>
  </div>
</body>
</html>
