<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username'])){
	header('location: login.php');
	exit;
}
if($_SESSION['tab'] != 'payment'){
	$_SESSION['tab'] = 'payment';
	header('location: index.php');
}else{
	include "../connection.php";
    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    $no_of_records_per_page = 10;
    $offset = ($pageno-1) * $no_of_records_per_page;
    $search = isset($_GET['name']) ? '%'.stripslashes(trim($_GET['name'])).'%' : '%%';
    $total_pages_sql = "SELECT COUNT(*) FROM tblpersonaldata WHERE (fname LIKE '$search' OR lname LIKE '$search') ORDER BY status";
    $result = mysqli_query($conn,$total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $sql = "SELECT * FROM tblpersonaldata WHERE (fname LIKE '$search' OR lname LIKE '$search') ORDER BY status LIMIT $offset, $no_of_records_per_page";
    $res_data = mysqli_query($conn,$sql);
    ?>
    <form class="form-inline">
        <div class="form-group pull-right">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search Name" name="name">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><i class="fa icon-search"></i></button>
        </div>
    </form>

    <?php
    echo '<h1 align="center">PAYMENTS</h1>';
    echo '<table class="table" style="color: black">';
    echo '<thead><th>Full Name</th><th style="text-align: center">Amount to Pay</th><th>Registered Time</th><th style="text-align: center">Paid</th><th style="text-align: center">Date Paid</th></thead>
	<tbody>';
    while($row = mysqli_fetch_assoc($res_data)){
        echo '<tr><td style="padding-top:15px">'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td><td style="padding-top:15px; text-align: center">'.($row['status'] == 'Paid' ? '--' : $row['payment']).'</td><td style="padding-top:15px">'.$row['registrationtime'].'</td>';
        if($row['status'] == 'Not Paid')
            echo '<td style="text-align: center;"><button onclick="check(\''.$row['fname'].' '.$row['mname'].' '.$row['lname'].'\',\''.$row['studNo'].'\')" class="btn btn-primary"><i class="icon-ok"></i><span></span></button>';
        else
            echo '<td style="text-align: center; padding-top: 15px"><small>Verified by '.$row['verifiedby'].'</small>';
        echo '</td><td style="text-align: center; padding-top: 15px">'.$row['verifiedtime'].'</td></tr>';
    }
    echo '</tbody></table>';
}
?>
<ul class="pagination">
    <li><a href="?<?php echo isset($_GET['name']) ? 'name='.$_GET['name'].'&' : '';?>pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="?<?php if($pageno <= 1){ echo '#'; } else { echo (isset($_GET['name']) ? 'name='.$_GET['name'].'&' : '')."pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="?<?php if($pageno >= $total_pages){ echo '#'; } else { echo (isset($_GET['name']) ? 'name='.$_GET['name'].'&' : '')."pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?<?php echo isset($_GET['name']) ? 'name='.$_GET['name'].'&' : '';?>pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
<script>
    function check(name, id){
        a = confirm('Do you solemnly swear to your country and to its people, that '+name+' is already paid?');
        if(a){
            b = confirm('Pramis? Mamatay man?');
            if(b){
                window.location.href = 'paid.php?studNo='+id;
            }
        }
    }
</script>