<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] == 0){
    header('location: login.php');
    exit;
}
if($_SESSION['tab'] != 'records'){
    $_SESSION['tab'] = 'records';
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
    echo '<h1 align="center">Records</h1>';
    while($row = mysqli_fetch_assoc($res_data)){?>
        <div class="well well-lg">
              <div class="row">
                  <div class="col-md-4">
                      <b>First Name: </b> <?php echo $row['fname'];?><br>
                      <b>M.I.: </b> <?php echo $row['mname'];?><br>
                      <b>Last Name: </b> <?php echo $row['lname'];?><br>
                      <b>Number: </b> <?php echo $row['number'];?><br>
                      <b>Email: </b> <?php echo $row['email'];?><br>
                      <b>School: </b> <?php echo $row['school'];?><br>
                      <b>Course: </b> <?php echo $row['course'];?><br>
                  </div>
                  <div class="col-md-4">
                      <b>Seminars: </b><br>
<?php
        $rawsem = mysqli_query($conn, "SELECT * FROM tblseminarinfo");
        $rawwork = mysqli_query($conn, "SELECT * FROM tblworkshopinfo");
        $studNo = $row['studNo'];
        while($r = mysqli_fetch_assoc($rawsem)){
            if(in_array($r['seminarid'], mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblseminars WHERE studNo='$studNo'")))){
                echo '<div style="margin-left: 1rem">'.$r['name'].'</div>';
            }
        }
?>
                  </div>
                  <div class="col-md-4">
                      <b>Workshops: </b><br>
<?php
        while($r = mysqli_fetch_assoc($rawwork)){
            if(in_array($r['workshopid'], mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblworkshops WHERE studNo='$studNo'")))){
                echo '<div style="margin-left: 1rem">'.$r['name'].'</div>';
            }
        }
        if($row['status']=='Not Paid')
            echo '<font color="red">';
        else
            echo '<font color="#39ff14">';
        echo '<b>'.$row['status'].'</b></font>';
        echo ' :'.$row['payment'].'<br>';
?>
                  </div>
              </div>
            <btn class="btn btn-danger pull-right" style="margin-top: -3rem" onclick="check('<?php echo $row['fname'].' '.$row['mname'].' '.$row['lname'];?>','<?php echo $row['studNo'];?>')">Delete</btn>
        </div>
<?php
    }
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
        a = confirm('Are You Sure You Want to delete '+name);
        if(a){
            b = confirm('Are You Really Really Sure??');
            if(b){
                window.location.href = 'delete.php?studNo='+id;
            }
        }
    }
</script>