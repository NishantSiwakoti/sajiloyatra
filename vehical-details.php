<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['submit'])) {
    $fromdate=$_POST['fromdate'];
    $todate=$_POST['todate']; 
    $message=$_POST['message'];
    $useremail=$_SESSION['login'];
    $status=0;
    $vhid=$_GET['vhid'];
    $bookingno=mt_rand(1000, 9999);
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "admin/img/Licenses/".$filename;

    if (move_uploaded_file($tempname, $folder)) {
      // Move the uploaded file to the destination folder
      // File moved successfully, continue with database insertion
      $ret="SELECT * FROM tblbooking where (:fromdate BETWEEN date(FromDate) and date(ToDate) || :todate BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN :fromdate and :todate) and VehicleId=:vhid";
      $query1 = $dbh->prepare($ret);
      $query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
      $query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
      $query1->bindParam(':todate',$todate,PDO::PARAM_STR);
      $query1->execute();
      $results1=$query1->fetchAll(PDO::FETCH_OBJ);
      if($query1->rowCount()==0) {
        // Insert data into tblbooking table with $filename variable
        $sql="INSERT INTO tblbooking(BookingNumber, userEmail, VehicleId, FromDate, ToDate, image, message, Status) VALUES(:bookingno, :useremail, :vhid, :fromdate, :todate, :filename, :message, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
        $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
        $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
        $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
        $query->bindParam(':todate',$todate,PDO::PARAM_STR);
        $query->bindParam(':message',$message,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':filename',$filename,PDO::PARAM_STR); // Bind $filename instead of $folder
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $success_message = "Status: Booking successful. Check my booking for confirmation.";
        } else {
            $error_message = "Status: Something went wrong. Please try again.";
        }
    } else {
        $error_message = "Status: Vehicle already booked for these days"; 
    }
} else {
    $error_message = "Status: Failed to move uploaded file. Please try again.";
}}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Sajilo Yatra | Vehicle Details</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custom Style -->
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link rel="shortcut icon" href="assets/images/Logo.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

</head>
<body> 

    <!--Header-->
    <?php include('includes/header.php');?>
    <!-- /Header --> 

    <!--Listing-Image-Slider-->
    <?php 
    $vhid=intval($_GET['vhid']);
    $sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid  from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query->rowCount() > 0) {
        foreach($results as $result) {  
            $_SESSION['brndid']=$result->bid;  
            ?>  

          <section id="listing_img_slider" style="border-bottom: 2px solid #ccc;">
              <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" width="900" height="560"></div>
              <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" class="img-responsive" alt="image" width="900" height="560"></div>
              <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" class="img-responsive" alt="image" width="900" height="560"></div>
              <?php if($result->Vimage4!="") { ?>
                <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage4);?>" class="img-responsive" alt="image" width="900" height="560"></div>
            <?php } ?>
            
            
        </section>
        <!--/Listing-Image-Slider-->

        <!-- Booking Status Message-->
        <div class="booking-status-message" style="margin-top: 40px">
          <?php if(isset($success_message)): ?>
            <p style="font-weight: bold; font-size: 30px; color: green; text-align:center;"><?php echo $success_message; ?></p>
          <?php endif; ?>
          <?php if(isset($error_message)): ?>
            <p style="font-weight: bold; font-size: 30px; color: red; text-align: center;" ><?php echo $error_message; ?></p>
          <?php endif; ?>
        </div>


        <!--Listing-detail-->
        <section class="listing-detail">
          <div class="container">
            <div class="listing_detail_head row">
              <div class="col-md-9">
                <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
            </div>
            <div class="col-md-3">
                <div class="price_info">
                  <p>Rs.<?php echo htmlentities($result->PricePerDay);?> </p>Per Day
              </div>
          </div>
      </div>

      <div class="row">
        <div class="col-md-9">
            <div class="main_features">
              <ul>
                <li> <i class="fa-solid fa-gas-pump" style="color:black"></i>
                  <h5><?php echo htmlentities($result->FuelType);?></h5>
                  <p>Fuel</p>
              </li>

              <li> <i class="fas fa-user-friends" aria-hidden="true" style="color:black"></i>
                  <h5><?php echo htmlentities($result->SeatingCapacity);?></h5>
                  <p>Seats</p>
              </li>
          </ul>
      </div>

      <div class="listing_more_info">
        <div class="listing_detail_wrap"> 
            <!-- Nav tabs -->
            <ul class="nav nav-tabs gray-bg" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview </a></li>
          </ul>

            <!-- Tab panes -->
            <div class="tab-content"> 
              <!-- vehicle-overview -->
              <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                  <p><?php echo htmlentities($result->VehiclesOverview);?></p>
              </div>
          </div>
      </div>
  </div>

</div>

<!--Side-Bar-->
<aside class="col-md-3">
    <div class="sidebar_widget">
      <div class="widget_heading">
        <h5 style="color:blue">Book Now</h5>
    </div>
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>From Date:</label>
        <input type="date" class="form-control" name="fromdate" id="fromdate" placeholder="From Date" required>
    </div>
    <div class="form-group">
        <label>To Date:</label>
        <input type="date" class="form-control" name="todate" id="todate" placeholder="To Date" required>
    </div>
    <div id="availabilityMessage"></div>

    <div class="form-group">
      <label for="license_photo">Upload Your License Photo:</label>
      <input type="file" class="form-control-file" id="license_photo" name="uploadfile" accept=".jpeg, .jpg, .png" required>
    
      
    </div>
    <div class="form-group">
        <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
    </div>
    <?php if($_SESSION['login']) { ?>
        <div class="form-group">
          <input type="submit" class="btn"  name="submit" value="Book Now">
      </div>
    <?php } else { ?>
            <button onclick="window.location.href='login.php'" class="input-group">Login For Booking</button>
<?php } ?>
</form>
</div>
</aside>
<!--/Side-Bar--> 
</div>
<style>
  
  .input-group{
    background-color: #3863e3;
    color: white;
    text-transform:none;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    width: 100%;
    height: 30px;

  }
  .input-group:hover{
    background-color: #23397d;
  }
  </style>
<div class="space-20"></div>
<div class="divider"></div>

<!--Similar-Cars-->
<div class="similar_cars">
  <h3>You may also like</h3>
  <div class="row">
    <?php 
    $bid=$_SESSION['brndid'];
    $sql="SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.VehiclesOverview,tblvehicles.Vimage1 from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.VehiclesBrand=:bid";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':bid',$bid, PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query->rowCount() > 0) {
        foreach($results as $result) { ?>      
            <div class="col-md-3 grid_listing">
              <div class="product-listing-m gray-bg">
                <div class="product-listing-img"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" /> </a>
                </div>
                <div class="product-listing-content">
                  <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                  <p class="list-price">Rs.<?php echo htmlentities($result->PricePerDay);?></p>
                  <ul class="features_list">
                    <li><i class="fas fa-user-friends" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
                    <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
                    <li><i class="fas fa-gas-pump" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
                </ul>
            </div>
        </div>
    </div>
<?php }}}} ?>       
</div>
</div>
<!--/Similar-Cars--> 
</div>
</section>
<!--/Listing-detail--> 

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  $(document).ready(function(){
    $('#todate').on('change', function(){
        var fromdate = $('#fromdate').val();
        var todate = $(this).val();
        var vhid = <?php echo $vhid; ?>; // Assuming you have vehicle id available here

        console.log('From Date:', fromdate);
        console.log('To Date:', todate);
        console.log('Vehicle ID:', vhid);

        $.ajax({
            type: 'POST',
            url: 'check.php',
            data: {
                fromdate: fromdate,
                todate: todate,
                vhid: vhid
            },
            success: function(response){
                console.log('Response:', response); // Log the response for debugging
                $('#availabilityMessage').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log any errors for debugging
            }
        });
    });
});


</script>

</body>
</html>
<?php

?>