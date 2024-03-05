<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {	
    header('location:index.php');
} else {
    if(isset($_REQUEST['eid'])) {
        $eid=intval($_GET['eid']);
        $status="2";
        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Booking Successfully Cancelled');</script>";
        echo "<script type='text/javascript'> document.location = 'canceled-bookings.php; </script>";
    }

    if(isset($_REQUEST['aeid'])) {
        $aeid=intval($_GET['aeid']);
        $status=1;

        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Booking Successfully Confirmed');</script>";
        echo "<script type='text/javascript'> document.location = 'confirmed-bookings.php'; </script>";
    }
}
 ?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <!-- Meta tags -->
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Booking Details</h2>
                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Bookings Info</div>
                            <div class="panel-body">
                                <div id="print">
                                    <!-- Table code goes here -->
                                </div>
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
    <script language="javascript" type="text/javascript">
        function f3() {
            window.print(); 
        }
    </script>
</body>
</html>
