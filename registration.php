<?php
session_start(); // Start or resume the session

// Include database configuration
include('includes/config.php');

$registrationSuccess = false; // Variable to track registration success
$error_message = ""; // Variable to store error message

if(isset($_POST['signup'])) {
    $fname = $_POST['fullname'];
    $email = $_POST['emailid'];
    $mobile = $_POST['mobileno'];
    $password = md5($_POST['password']);
    $confirmPassword = md5($_POST['confirmpassword']);

    // Check if passwords match
    if($password !== $confirmPassword) {
        $error_message = "Passwords did not match.";
    } else {
        // Check if email is available
        $sql = "SELECT * FROM tblusers WHERE EmailId = :email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0) {
            $error_message = "Email not available for registration."; // Email already exists
        } else {
            $sql = "INSERT INTO tblusers (FullName, EmailId, ContactNo, Password) VALUES (:fname, :email, :mobile, :password)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            if($query->execute()) {
                $registrationSuccess = "Sign up completed. You can login now"; // Set registration success flag to true
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Sajilo Yatra</title>  
    <link rel="shortcut icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        /* CSS for the navbar and form */
        body {
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
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
        .signup_btn {
            float: right;
            margin-top: 16px;
            margin-right: 20px;
            background-color: #3863e3;
            border-radius: 4px;
        }
        .signup_btn a {
            color: #fff;
            padding: 8px 12px;
            font-size: 12px;
        }
        .signup_btn:hover {
            background-color: #23397d;
        }
        .signup_btn a:hover {
            color: white;
        }    
        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .signup-section {
            margin: 20px auto;
            width: 500px;
            height: auto;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 2px 2px 4px 4px grey;
        }
        .signup-section h2 {
            color: blue;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 15px;
            color: blue;
        }
        .input-group {
            margin-bottom: 15px;
            color: blue;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input[type="text"],
        .input-group input[type="email"],
        .input-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group.checkbox {
            margin-bottom: 15px;
        }
        .form-group.checkbox label {
            margin-left: 20px;
        }
        .signup-section p {
            text-align: center;
            margin-top: 15px;
        }
        .signup-section p a {
            color: blue;
            text-decoration: none;
        }
        .signup-section p a:hover {
            text-decoration: underline;
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
        }
        .input-group input[type="submit"]:hover {
            background-color: #23397d;
        }
        /* Styles for availability message */
        .availability-message {
            margin-top: 5px;
            text-align: center;
            font-weight: bold;
        }
        .availability-message.available {
            color: green;
        }
        .availability-message.unavailable {
            color: red;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <a href="login.php" class="btn btn-xs btn-sm uppercase">Log in</a>
        </div>
    </div>
    <div class="signup-section">
        <h2>Sign Up</h2>
        <?php if(isset($error_message) && !empty($error_message)): ?>
            <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
        <?php elseif(isset($registrationSuccess) && !empty($registrationSuccess)): ?>
            <div style="color: green; text-align: center;"><?php echo $registrationSuccess; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="input-group">
                <label for="fullname">Full Name*</label>
                <input type="text" name="fullname" placeholder="Enter your full name*" required="required">
            </div>
            <div class="input-group">
                <label for="mobileno">Mobile Number*</label>
                <input type="text" name="mobileno" maxlength="10" placeholder="Enter your mobile number*" required="required">
            </div>
            <div class="input-group">
                <label for="emailid">Email Address*</label>
                <input type="email" name="emailid" id="emailid" placeholder="Enter your email address*" required="required">
                <div class="availability-message"></div> <!-- Display availability message here -->
            </div>
            <div class="input-group">
                <label for="password">Password*</label>
                <input type="password" name="password" placeholder="Enter your password*" required="required">
            </div>
            <div class="input-group">
                <label for="confirmpassword">Confirm Password*</label>
                <input type="password" name="confirmpassword" placeholder="Confirm Password*" required="required">
            </div>
            <div class="form-group checkbox">
                <input type="checkbox" id="terms_agree" required="required" checked="">
                <label for="terms_agree">I Agree with <a href="terms.php">Terms and Conditions</a></label>
            </div>
            <div class="input-group">
                <input type="submit" class="btn btn-xs btn-sm uppercase" value="SIGN UP" name="signup">
            </div>
        </form>
    </div>
    <p style="text-align: center;">Already have an account? <a href="login.php">Login Here</a></p>
    <script>
    function checkEmailAvailability() {
        var email = $('#email').val();
        $.ajax({
            url:'check_availability.php',
            method:'POST',
            data:{email:email},
            success:function(data) {
                $('.availability-message').html(data);
            }
        });
    }
</script>

</body>
</html>
