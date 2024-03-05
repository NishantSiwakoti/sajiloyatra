<?php 
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Sajilo Yatra | Vehicle Listing</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/icon.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <style>
        body {
            background-color: whitesmoke;
        }
    </style>
</head>
<body>

    <!-- Header --> 
    <?php include('includes/header.php');?>
    <!-- /Header --> 

    <!-- Page Header -->
    <section class="page-header listing_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Search Result for "<?php echo $_POST['searchdata'];?>"</h1>
                </div>
            </div>
        </div>
        <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header --> 

    <!-- Listing -->
    <section class="listing-page">
        <div class="container">
            <div class="row">
                <?php 
                $search = "%".$_POST['searchdata']."%"; // Wrap the search term with %
                $sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid  
                        FROM tblvehicles 
                        JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand 
                        WHERE tblvehicles.VehiclesTitle LIKE :search 
                        OR tblvehicles.FuelType LIKE :search 
                        OR tblbrands.BrandName LIKE :search 
                        OR tblvehicles.ModelYear LIKE :search";
                $query = $dbh->prepare($sql);
                $query->bindParam(':search', $search, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;

                // Display "not found" message if no vehicles are found
                if (count($results) == 0) {
                    echo '<div class="col-md-12">';
                    echo '<p>No listing found for ' . $_POST['searchdata'] . '</p>';
                    echo '</div>';
                } else {
                    foreach ($results as $result) {
                        ?>
                        <div class="col-md-6">
                            <div class="product-listing">
                                <div class="product-listing-img">
                                    <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="Image" />
                                </div>
                                <div class="product-listing-content">
                                    <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                                    <p class="list-price">Rs. <?php echo htmlentities($result->PricePerDay);?> Per Day</p>
                                    <ul>
                                        <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
                                        <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
                                        <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
                                    </ul>
                                    <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>" class="btn">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                        if ($cnt % 2 == 0) {
                            echo '</div><div class="row">';
                        }
                        $cnt++;
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- /Listing --> 

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
</body>
</html>
