<?php 
    session_start();
    include('includes/config.php');
    error_reporting(0);

    /*Fetching full name*/
    $email = $_SESSION['login'];
    $sql = "SELECT FullName FROM tblusers WHERE EmailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Sajilo Yatra </title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php';?>
    <!-- /Header --> 

    <!-- Recent vehicles -->
    <section class="section-padding gray-bg">
        <div class="container">
            <div class="section-header text-center">
                <?php if(isset($_SESSION['login'])) { ?>
                    <h2>Welcome, <?php echo htmlentities($result->FullName);?> !</h2>
                <?php } else { ?>
                    <h3>Login/Sign up to book the vehicles</h3>
                <?php } ?>
                <?php if(isset($_SESSION['login'])) { ?>
                    <p>Choose the perfect ride for your journey. We offer cars and motorbikes.</p>
                <?php } ?>
                
            </div>
            <div class="row"> 
                <!-- Recently Listed new vehicles -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="resentnewcar">
                        <?php 
                            $sql = "SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.VehiclesOverview,tblvehicles.Vimage1 from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand limit 12";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $result) {  
                        ?>  
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="car-info-box">
                                <div class="recent-car-list">
                                    <div class="car-info-box"> 
                                        <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>">
                                            <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image">
                                        </a>
                                    </div>
                                    <div class="car-title-m">
                                        <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"> <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
                                        <span class="price">Rs.<?php echo htmlentities($result->PricePerDay);?> /Day</span> 
                                    </div>
                                    <div class="inventory_info_m">
                                        <p><?php echo substr($result->VehiclesOverview,0,30);?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Resent vehicles --> 
    
    <!-- Footer -->
    <?php include('includes/footer.php');?>
    <!-- /Footer --> 

    <!-- Back to top -->
    <div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
    <!-- /Back to top --> 

    <!-- Scripts --> 
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
    <script src="assets/js/interface.js"></script> 
    <!-- Switcher -->
    <script src="assets/switcher/js/switcher.js"></script>
    <!-- bootstrap-slider-JS --> 
    <script src="assets/js/bootstrap-slider.min.js"></script> 
    <!-- Slider-JS --> 
    <script src="assets/js/slick.min.js"></script> 
    <script src="assets/js/owl.carousel.min.js"></script>
</body>
</html>
