<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sajilo Yatra</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <!-- Navigation -->
        <nav id="navigation_bar" class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> 
                        <span class="sr-only"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                    </button>
                    <div class="logo"> 
                        <a href="index.php">
                            <img src="assets/images/Logo.png" alt="Sajilo Yatra Logo">
                        </a>
                    </div>
                </div>
                <div class="header_wrap">
                    <div class="user_login move right" style="margin-top: 25px; margin-left:20px">
                        <ul>
                            <li class="dropdown"> 
                                <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i> 
                                    <?php 
                                        if(isset($_SESSION['login'])) {
                                            $email=$_SESSION['login'];
                                            $sql ="SELECT FullName FROM tblusers WHERE EmailId=:email ";
                                            $query= $dbh -> prepare($sql);
                                            $query-> bindParam(':email', $email, PDO::PARAM_STR);
                                            $query-> execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) {
                                                    echo '<span>'.htmlentities($result->FullName).'</span>';
                                                }
                                            }
                                        } else {
                                            echo 'Guest'; // Default text if user is not logged in
                                        }
                                    ?> 
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if(isset($_SESSION['login'])) { ?>
                                        <li><a href="profile.php">Profile Settings</a></li>
                                        <li><a href="update-password.php">Update Password</a></li>
                                        <li><a href="my-booking.php">My Booking</a></li>
                                        <li><a href="logout.php">Log Out</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="login_btn" style="margin-top: 25px; float: right;"> 
                        <?php if(!isset($_SESSION['login'])) { ?>
                            <a href="login.php" class="btn btn-xs btn-sm uppercase" style=" margin-right: 10px;">Login</a>
                            <a href="registration.php" class="btn btn-xs btn-sm uppercase">Sign Up</a>
                        <?php } ?> 
                    </div>

                    <div class="header_search" style="float: right; margin-top: 5px;">
                        <div id="search_toggle"><i class="fa fa-search" aria-hidden="true" style="color:blue"></i></div>
                        <form action="search.php" method="post" id="header-search-form">
                            <input type="text" placeholder="Search..." name="searchdata" class="form-control" required="true">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="car-listing.php">Vehicles</a></li>
                        <li><a href="page.php?type=aboutus">About Us</a></li>
                        <li><a href="contact-us.php">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Navigation end --> 
    </header>
</body>
</html>
