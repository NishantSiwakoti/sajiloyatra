<?php
session_start();
include('includes/config.php');

if(isset($_POST['login'])) {
    $email = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT UserName, Password FROM admin WHERE UserName=:email AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sajilo Yatra | Admin Login</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-page bk-img">
        <div class="form-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h1 class="text-center text-bold mt-4x" style="color:Black;">Admin | Sign in</h1>
                        <div class="well row pt-2x pb-3x bk-light">
                            <div class="col-md-8 col-md-offset-2">
                                <form method="post">
                                    <label for="" class="text-uppercase text-sm" style="color:#4B49AC;">
                                        <i class="fas fa-user" style="color:#4B49AC;"></i> Username
                                    </label>
                                    <input type="text" placeholder="Enter your username" name="username" class="form-control mb">
                                    <label for="" class="text-uppercase text-sm" style="color:#4B49AC;">
                                        <i class="fas fa-lock" style="color:#4B49AC;"></i> Password
                                    </label>
                                    <input type="password" placeholder="Enter your password" name="password" class="form-control mb">
                                    <?php if(isset($error_message)): ?>
                                        <p style="color: red; margin-bottom: 10px;"><?php echo $error_message; ?></p>
                                    <?php endif; ?>
                                    <button class="btn btn-primary btn-block" name="login" type="submit">Login</button>
                                </form>
                                <p style="margin-top: 5%; text-align: center;"><a href="../index.php">Back to Home</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
