<?php
session_start(); // Start or resume the session

// Include database configuration
include('includes/config.php');

if(isset($_POST['update'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);

    $sql = "UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email AND ContactNo=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $query->execute();
    $result = $query->rowCount();

    if($result > 0) {
        $success_message = "Password changed. You can now login.";
    } else {
        $error_message = "Invalid email or mobile number. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Include any CSS stylesheets here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/icon.png">

    <style>
        /* Your CSS styles */
        /* Navbar styles */
        body{
            font-family: Arial, Helvetica, sans-serif; /* Change font family */
        }
        .navbar {
            color: #fff;
            overflow: hidden;
            background-color: whitesmoke;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add shadow */
            display: flex; /* Use flexbox */
            justify-content: space-between; /* Align items to the left and right */
            align-items: center; /* Vertically center items */
        }
        .navbar-logo img {
            height: 60px;
            width: auto;
        }
        .navbar-links {
            display: flex; /* Use flexbox for navbar links */
            align-items: center; /* Vertically center items */
        }
        .navbar a {
            font-family: Arial, Helvetica, sans-serif;
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
        .login-section {
            margin: 20px auto;
            width: 500px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 4px 4px grey;
        }
        .login-section h2 {
            color: blue;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 10px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: blue;
            font-weight: bold;
        }
        .input-group input[type="email"],
        .input-group input[type="text"],
        .input-group input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .input-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #3863e3;;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        .input-group input[type="submit"]:hover {
            background-color: #23397d;
        }
        .login-section p {
            text-align: center;
            margin-top: 15px;
        }
        .login-section p a {
            color: blue;
            text-decoration: none;
        }
        .login-section p a:hover {
            text-decoration: underline;
        }
        .login_btn,
        .signup_btn {
            margin-left: auto;
            display:block;
            margin-right: auto; /* Push buttons to the right */
        }
        .login_btn a,
        .signup_btn a {
            background-color: #3863e3;
            color: white;
            padding: 8px 16px;
            border-radius: 3px;
            text-decoration: none;
        }
        .login_btn a:hover,
        .signup_btn a:hover {
            background-color: #23397d;
            color: white;
        }
        .login_btn{
            margin-right: 10px;
        }
        .signup_btn{
            margin-right: 150px;
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
        <div class="login_btn">
            <a href="index.php" class="btn btn-xs btn-sm uppercase">Log in</a>
        </div>
    </div>
    <div class="login-section">
        <h2>Change Password</h2>
        <?php if(isset($error_message)): ?>
            <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
        <?php elseif(isset($success_message)): ?>
            <div style="color: green; text-align: center;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="post" onSubmit="return valid();">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Your Email address*" required>
            </div>
            <div class="input-group">
                <label for="mobile">Mobile No</label>
                <input type="text" name="mobile" class="form-control" placeholder="Mobile No*" required>
            </div>
            <div class="input-group">
                <label for="newpassword">New Password</label>
                <input type="password" name="newpassword" class="form-control" placeholder="New Password*" required>
            </div>
            <div class="input-group">
                <label for="confirmpassword">Confirm Password</label>
                <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password*" required>
            </div>
            <div class="input-group">
                <input type="submit" value="Reset Password" name="update" class="btn btn-block" style="background-color: blue; color: white;">
            </div>
        </form>
        <p style="text-align: center;">Remember your password? <a href="login.php">Login here</a></p>
    </div>

    <!-- Include any JavaScript scripts here -->
    <script type="text/javascript">
        function valid() {
            if(document.querySelector('[name="newpassword"]').value != document.querySelector('[name="confirmpassword"]').value) {
                alert("New Password and Confirm Password Field do not match!!");
                document.querySelector('[name="confirmpassword"]').focus();
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
