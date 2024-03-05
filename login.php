<?php 
session_start(); // Start or resume the session

// Include database configuration
include('includes/config.php');

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT EmailId, Password, FullName FROM tblusers WHERE EmailId=:email and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        $_SESSION['login'] = $_POST['email'];
        $_SESSION['fname'] = $results[0]->FullName; // Assuming you only fetch one row
        
        // Redirect to index.php
        header('Location: index.php');
        exit; // Ensure script execution stops after redirection
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sajilo Yatra</title>  
    <link rel="shortcut icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        /* CSS for the navbar */
        .error-message {
            color: red;
            text-align: center;
        }
body{
    background-color: whitesmoke;
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif; /* Change font family */

}
.navbar {
    color: #fff;
    overflow: hidden;
    background-color: whitesmoke;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add shadow */
    margin-bottom: 10px;

}
.navbar-logo {
    float: left;
    margin-left: 180px;
}

.navbar-logo img {
    height: 60px; /* Adjust height as needed */
    width: auto;
}

.navbar a {
    font-family: Arial, Helvetica, sans-serif;
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 22px 16px;
    font-size: 13px;
    text-decoration: none;
    font-weight: bold;
    text-transform: uppercase;
}

.navbar a:hover {
    color: blue;
}

.search-container {
    float: right;
    margin-right: 400px;
    margin-top: 8px;
}

.search-container input[type=text] {
    padding: 6px;
    margin-top: 8px;
    font-size: 15px;
    border: 1px solid blue;
    border-radius: 3px;
}
.search-container form {
    display: flex;
    align-items: center;
}
.signup_btn{
    float: right;
    margin-top: 16px;
    margin-right: 20px;
    background-color: #3863e3;
    border-radius: 4px;
}
.signup_btn a{
    font-family: Arial, Helvetica, sans-serif;
    color: #fff;
    padding: 8px 12px;
    font-size: 12px;
}
.signup_btn:hover{
    background-color: #23397d;
}
.signup_btn a:hover{
    color:white;
}    
/* Clear floats */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
    font-family: Arial, Helvetica, sans-serif;

}
.login-section {
    margin: 20px auto;
    width: 500px;
    height: 400px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 2px 2px 4px 4px grey;
}

.login-section h2 {
    font-family: Arial, Helvetica, sans-serif;
    color: blue;
    text-align: center;
    margin-bottom: 20px;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 10px;
    color: blue;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif; /* Apply specified font */
}

.input-group input[type="email"],
.input-group input[type="password"] {
    width: 100%;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    width: calc(100% - 30px); /* Adjust width */
    padding-left: 30px;
}

.input-group input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #3863e3;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;

}

.input-group input[type="submit"]:hover {
    background-color: #23397d;
}

.login-section p {
    text-align: center;
    margin-top: 15px;
    font-family: Arial, Helvetica, sans-serif;

}

.login-section p a {
    color: blue;
    text-decoration: none;
}

.login-section p a:hover {
    text-decoration: underline;
}
.input-group input[type="email"],
.input-group input[type="password"],
.login-section h2 {
    font-family: Arial, Helvetica, sans-serif;
}
.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #777; /* Adjust color as needed */
    z-index: 1;
}


    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-logo">
            <img src="assets/images/logo.png" alt="logo">
        </div>
        <div class="navbar-links">
            <a href="index.php">Home</a>
            <a href="car-listing.php">Vehicles</a>
            <a href="page.php?type=aboutus">About Us</a>
            <a href="contact-us.php">Contact Us</a>
        </div>
            <div class="search-container">
                <form action="search.php">
                    <input type="text" placeholder="Search" name="search">
                </form>
            </div>
            <div class="signup_btn">
                <a href="registration.php" class="btn btn-xs btn-sm uppercase">Sign Up</a>
            </div>
        </div>
        <div class="login-section">
            <h2>LOGIN</h2>
            <?php if(isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post">
        <div class="input-group">
            <label for="email">Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i> 
                <input type="email" class="form-control" name="email" placeholder="Enter your email">
            </div>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <div class="input-icon">
                <i class="fas fa-lock"></i> 
                <input type="password" class="form-control" name="password" placeholder="Enter your password">
            </div>
        </div>
        <div class="input-group">
            <input type="submit" name="login" value="Login" class="btn">
        </div>
    </form>
    <p>New to Sajilo Yatra? <a href="registration.php">Sign up Here</a></p>
    <p><a href="forgotpassword.php">Forgot Password?</a></p>
</div>


</body>
</html>

