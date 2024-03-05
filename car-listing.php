<?php 
    session_start();
    include('includes/config.php');
    error_reporting(0);

    // Number of listings per page
    $limit = 20;

    // Determine the current page number
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculate the OFFSET value for the SQL query
    $offset = ($page - 1) * $limit;
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Sajilo Yatra | Vehicle Listing</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custome Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!--OWL Carousel slider-->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!--slick-slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!--bootstrap-slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!--FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/icon.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<body>

    <!-- Start Switcher -->
    <?php include('includes/colorswitcher.php');?>
    <!-- /Switcher -->  

    <!--Header--> 
    <?php include('includes/header.php');?>
    <!-- /Header --> 

    <!--Page Header-->
    <section class="page-header listing_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Vehicle Listing</h1>
                </div>
            </div>
        </div>
        <!-- Dark Overlay-->
        <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header--> 

    <!--Listing-->
    <section class="listing-page">
        <div class="container">
            <div class="row">
                <?php 
                    // Fetch listings for the current page
                    $sql = "SELECT tblvehicles.*, tblbrands.BrandName, tblbrands.id as bid FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand LIMIT :limit OFFSET :offset";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
                    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0) {
                        $counter = 0; // Initialize counter
                        foreach($results as $result) {  
                            if($counter % 2 == 0) { // Open row if counter is even
                                echo '<div class="row">';
                            }
                ?>
                <div class="col-md-6">
                    <div class="product-listing-m gray-bg">
                        <div class="product-listing-img">
                            <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="Image" /> 
                        </div>
                        <div class="product-listing-content">
                            <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                            <p class="list-price">Rs.<?php echo htmlentities($result->PricePerDay);?> Per Day</p>
                            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>" class="btn">Details</span></a>
                        </div>
                    </div>
                </div>
                <?php 
                            $counter++; // Increment counter
                            if($counter % 2 == 0) { // Close row if counter is even
                                echo '</div>';
                            }
                        } 
                        if($counter % 2 != 0) { // Close row if the last row is not complete
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
    </section>
    <!-- /Listing--> 

    <!-- Pagination -->
    <div class="text-center">
        <?php 
            // Count total number of listings
            $sql = "SELECT COUNT(*) AS total FROM tblvehicles";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_pages = ceil($row['total'] / $limit);

            // Display pagination links
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='index.php?page=".$i."' class='btn btn-primary'>$i</a> ";
            }
        ?>
    </div>

    <!--Footer -->
    <?php include('includes/footer.php');?>
    <!-- /Footer--> 

    <!--Back to top-->
    <div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
    <!--/Back to top--> 

    <!-- Scripts --> 
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
    <script src="assets/js/interface.js"></script> 
    <!--Switcher-->
    <script src="assets/switcher/js/switcher.js"></script>
    <!--bootstrap-slider-JS--> 
    <script src="assets/js/bootstrap-slider.min.js"></script> 
    <!--Slider-JS--> 
    <script src="assets/js/slick.min.js"></script> 
    <script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>
