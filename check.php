<?php 
require_once("includes/config.php");

// Check vehicle availability based on selected from and to dates
if(isset($_POST["fromdate"]) && isset($_POST["todate"])) {
    $fromdate = $_POST["fromdate"];
    $todate = $_POST["todate"];

    $vhid = $_POST["vhid"]; // Assuming you'll also post vehicle id

    $ret = "SELECT * FROM tblbooking 
            WHERE (:fromdate BETWEEN date(FromDate) AND date(ToDate) 
            OR :todate BETWEEN date(FromDate) AND date(ToDate) 
            OR date(FromDate) BETWEEN :fromdate AND :todate) 
            AND VehicleId = :vhid";

    $query = $dbh->prepare($ret);
    $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
    $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':todate', $todate, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        echo "<span style='color:red'>Vehicle not available for the selected dates.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Vehicle available for booking.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}
?>
