<html>
<head>
    <title>Pagination</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include '../connection.php';
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 10;
$offset = ($pageno-1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM tblpersonaldata";
$result = mysqli_query($conn,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

$sql = "SELECT * FROM tblpersonaldata LIMIT $offset, $no_of_records_per_page";
$res_data = mysqli_query($conn,$sql);
echo '<h1 align="center">PERSONAL INFORMATIONS</h1>';
echo '<table class="table" style="color: black">';
echo '<thead><th>Full Name</th><th>Age</th><th>School</th><th>Course</th><th>Email</th><th>Phone Number</th><th style="text-align: center">Paid</th></thead>';
while($row = mysqli_fetch_array($res_data)){
    echo '<tr><td>'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td><td>'.$row['age'].'</td><td>'.$row['school'].'</td><td>'.$row['course'].'</td><td>'.$row['email'].'</td><td>'.$row['number'].'</td><td style="text-align: center"><i class="'.($row['status']=='Paid' ? 'icon-check' : 'icon-check-empty').'"></i></td></tr>';
}
?>
<br>
<ul class="pagination">
    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
</body>
</html>