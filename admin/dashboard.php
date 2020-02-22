<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username'])){
	header('location: login.php');
	exit;
}
if($_SESSION['tab'] != 'dashboard'){
	$_SESSION['tab'] = 'dashboard';
	header('location: index.php');
	exit;
}else{
    include "../connection.php";
    if(isset($_POST['action']) && $_POST['action'] == 'edit' && is_numeric($_POST['max']) && is_numeric($_POST['id'])){
        $id = $_POST['id'];
        $max = $_POST['max'];
        if($_POST['id'] < 100){
            $table = 'tblworkshopinfo';
            $col = 'workshopid';
        }else{
            $table = 'tblseminarinfo';
            $col = 'seminarid';
        }
        $q = "UPDATE ".$table." SET max='$max' WHERE ".$col."='$id'";
        $res = mysqli_query($conn, $q);
    }
    $u = mysqli_query($conn, "SELECT * FROM tblseminarinfo");
	$v = mysqli_query($conn, "SELECT * FROM tblworkshopinfo");
	$w = array();
	$s = array();
	while($row = mysqli_fetch_assoc($u)){
		$t = $row['seminarid'];
		$q = "SELECT * FROM tblseminars WHERE `11`='$t' OR `12`='$t' OR `21`='$t' OR `22`='$t' OR `31`='$t' OR `32`='$t' OR `41`='$t' OR `42`='$t'";
		$s = array_merge((array)$s, (array)array($row['name'] => mysqli_num_rows(mysqli_query($conn, $q))));
	}
	while($row = mysqli_fetch_assoc($v)){
		$t = $row['workshopid'];
		$q = "SELECT * FROM tblworkshops WHERE `1`='$t' OR `2`='$t' OR `3`='$t'";
		$w = array_merge((array)$w, (array)array($row['name'] => mysqli_num_rows(mysqli_query($conn, $q))));
	}
	?>
    <table class="table-stonks">
        <tr class="normal">
            <td class="stonks">
                <img src="stonks.jpg" alt="" style="width: 40%; border-radius: .5rem">
                <font size="6rem">Php
                    <?php
                    echo mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(payment) FROM tblpersonaldata WHERE status='Paid'"))[0];
                    ?>.00
                </font>
            </td>
            <td class="not-stonks">
                <img src="not%20stonks.jpg" alt="" style="width: 40%; border-radius: .5rem">
                <font size="6rem">Php
                    <?php
                    echo mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(payment) FROM tblpersonaldata WHERE status='Not Paid'"))[0];
                    ?>.00
                </font>
            </td>
        </tr>
    </table>
    <style>
        .normal:hover {
            background-color: transparent !important;
        }
        .table-stonks{
            border-collapse: separate;
            border-spacing: 3rem 0;
        }
        .stonks{
            background-color: rgb(74,163,181);
            color: whitesmoke;
            margin: 12px 12px 12px 12px;
            padding: 3px 3px 3px 3px;
        }
        .not-stonks{
            background-color: rgb(206,70,74);
            color: whitesmoke;
            margin: 12px 12px 12px 12px;
            padding: 3px 3px 3px 3px;
        }
    </style>
    <hr style="border: #5b6e84 solid 2px">
    <?php if(isset($_POST['action']) && $_POST['action'] == 'edit' && is_numeric($_POST['max']) && $res){
        ?>
        <div class="alert alert-success" role="alert" style="margin-top: 1rem">
            <strong>Successfully Updated Slot!</strong>
        </div>
        <?php
    }else if(isset($_POST['action']) && $_POST['action'] == 'edit' && is_numeric($_POST['max'])){
        ?>
        <div class="alert alert-danger" role="alert" style="margin-top: 1rem">
            <strong>Failed Updating Slot!</strong>
        </div>
        <?php
    }
    ?>
<?php
	echo '<h1 align="center">SEMINARS</h1>';
	echo '<table class="table" style="color: black">';
	echo '<thead><th>No.</th><th>Seminar Name</th><th>Registered Students</th></thead>';
	$i = 0;
	foreach($s as $name=>$count){
		$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblseminarinfo WHERE name='$name'"));
		echo '<td>'.++$i.'</td><td>'.$name.'</td><td id="edit'.$r['seminarid'].'">'.($_SESSION['type'] == 1 ? '<a href="javascript:edit('.$r['seminarid'].','.$r['max'].')">' : '').$count.'/'.$r['max'].($_SESSION['type'] == '1' ? '</a>' : '').'</td></tr>';
	}
	echo '</table>
	<h1 align="center">WORKSHOPS</h1>';
	echo '<table class="table" style="color: black">';
	echo '<thead><th>No.</th><th>Workshop Name</th><th>Registered Students</th></thead>
	<tbody>';
	$i = 0;
	foreach($w as $name=>$count){
		$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblworkshopinfo WHERE name='$name'"));
		echo '<tr><td>'.++$i.'</td><td>'.$name.'</td><td id="edit'.$r['workshopid'].'">'.($_SESSION['type'] == 1 ? '<a href="javascript:edit('.$r['workshopid'].','.$r['max'].')">' : '').$count.'/'.$r['max'].($_SESSION['type'] == '1' ? '</a>' : '').'</td></tr>';
	}
	echo '</tbody></table>';
}
?>
<script>
    function edit(id, init){
        document.getElementById('edit'+id).innerHTML = '<form method="post">' +
            '<input name="max" type="number" style="border-radius: 5px; margin-right: 1rem;" value="'+init+'">' +
            '<button name="action" value="edit" class="btn btn-primary" style="padding: 0 10px; margin-top: -.6rem"><i class="icon-edit"></i> Edit</button>' +
            '<input type="hidden" name="id" value="'+id+'">' +
            '</form>';
    }
</script>
