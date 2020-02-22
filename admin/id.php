<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username'])){
	header('location: login.php');
	exit;
}
if($_SESSION['tab'] != 'id'){
	$_SESSION['tab'] = 'id';
	header('location: index.php');
	exit;
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
    $total_pages_sql = "SELECT COUNT(*) FROM tblidprinting a LEFT JOIN tblpersonaldata b ON a.studno=b.studno WHERE (fname LIKE '$search' OR lname LIKE '$search')";
    $result = mysqli_query($conn,$total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $sql = "SELECT a.*, b.studNo, b.fname, b.lname, b.mname FROM tblidprinting a LEFT JOIN tblpersonaldata b ON a.studno=b.studno WHERE (fname LIKE '$search' OR lname LIKE '$search') ORDER BY a.status LIMIT $offset, $no_of_records_per_page";
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
    echo '<h1 align="center">Pending IDs</h1>';
    echo '<table class="table" style="color: black">';
    echo '<thead><th>Full Name</th><th style="text-align: center">Download</th><th style="text-align: center">Printed</th><th style="text-align: center">Date Printed</th></thead>';
    while($row = mysqli_fetch_assoc($res_data)){
        echo '<tr><td style="padding-top: 15px">'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td><td style="text-align: center; padding-top: 15px"><a href="download.php?studNo='.$row['studNo'].'" target="_blank"><i class="icon-download-alt"></i></a></td><td style="text-align: center;">';
        if($row['status'] == 'Not Printed') {
            echo '<a href="printed.php?studNo=' . $row['studNo'] . '"><button class="btn btn-primary">Done</button></a>';
        }else{
            echo '<small>Printed By '.$row['printedby'].'</small>';
        }
        echo '</td><td style="text-align: center">'.$row['printedtime'].'</td></tr>';
    }
    echo '</table>';
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
