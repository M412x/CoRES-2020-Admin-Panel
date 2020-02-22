<?php
include "../connection.php";
$studNo = $_GET['studNo'];
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblpersonaldata WHERE studno='$studNo'"));
?>
<div class="card" style="width: 18rem;">
    <img src="http://chart.apis.google.com/chart?chs=300x300&cht=qr&chld=L|0&chl=<?php echo $studNo?>" alt="QR Shit" style="width:100%">
    <div class="card-body">
        <h3 class="card-title" align="center"><?php echo strtoupper($r['fname'].' '.$r['lname']);?></h3>
    </div>
</div>