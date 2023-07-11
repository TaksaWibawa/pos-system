<!DOCTYPE html>
<html>
<head>
    <title>Restaurant POS - Login</title>
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
                <h1>Login Page</h1>
            </div>
            <form role="form" action="auth/login.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button class="btn btn-primary btn-block" type="submit" name="Log">Log In</button>
            </form>
            <div class="text-center register">
                <p>Don't have an account? <a href="register.php" class="btn btn-link">Register</a></p>
            </div>
            <?php
            if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
                echo '<div class="alert alert-danger fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid Login. Please try again!</div>';
                echo '</div>';
            } elseif (isset($_GET['success']) && $_GET['success'] === 'registered') {
                echo '<div class="alert alert-success fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Account Created. Please Login!</div>';
                echo '</div>';
            } elseif (isset($_GET['error']) && $_GET['error'] === 'not_logged_in') {
                echo '<div class="alert alert-danger fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please Login!</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
