<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username'])){
	header('location: login.php');
	exit;
}
if($_SESSION['tab'] != 'details'){
	$_SESSION['tab'] = 'details';
	header('location: index.php');
	exit;
}else{
	include "../connection.php";
	$r = mysqli_query($conn, "SELECT * FROM tblpersonaldata ORDER BY status DESC");
    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    $no_of_records_per_page = 10;
    $offset = ($pageno-1) * $no_of_records_per_page;
    $search = isset($_GET['name']) ? '%'.stripslashes(trim($_GET['name'])).'%' : '%%';
    $total_pages_sql = "SELECT COUNT(*) FROM tblpersonaldata WHERE fname LIKE '$search' OR lname LIKE '$search'";
    $result = mysqli_query($conn,$total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $sql = "SELECT * FROM tblpersonaldata WHERE fname LIKE '$search' OR lname LIKE '$search' ORDER BY status DESC LIMIT $offset, $no_of_records_per_page";
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
    echo '<h1 align="center">PERSONAL INFORMATIONS</h1>';
    echo '<table class="table" style="color: black">';
    echo '<thead><th>Full Name</th><th>Age</th><th>School</th><th>Course</th><th>Email</th><th>Phone Number</th><th style="text-align: center">Paid</th></thead>';
    while($row = mysqli_fetch_array($res_data)){
        echo '<tr><td>'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td><td>'.$row['age'].'</td><td>'.$row['school'].'</td><td>'.$row['course'].'</td><td>'.$row['email'].'</td><td>'.$row['number'].'</td><td style="text-align: center"><i class="'.($row['status']=='Paid' ? 'icon-check' : 'icon-check-empty').'"></i></td></tr>';
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
