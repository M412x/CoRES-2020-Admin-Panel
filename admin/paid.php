<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location: login.php');
    exit;
}
include "../connection.php";
$studNo = htmlspecialchars(stripslashes($_GET['studNo']));
$res = mysqli_query($conn, "SELECT * FROM tblpersonaldata WHERE studNo='$studNo'");
if(!isset($_GET['studNo']) || mysqli_num_rows($res) == 0){
    header('location: ../404.php');
    exit;
}
$res = mysqli_fetch_assoc($res);
$time = date("Y-m-d H:i:s", time()+28800);
$user = $_SESSION['username'];
$r = mysqli_query($conn, "UPDATE tblpersonaldata SET status='Paid', verifiedtime='$time', verifiedby='$user' WHERE studNo='$studNo'");

$admin = $_SESSION['username'].$_SERVER['REMOTE_ADDR'];
$action = 'verified payment';
$user = $res['fname'].' '.$res['mname'].' '.$res['lname'];
mysqli_query($conn, "INSERT INTO tbladminlogs(admin,action,user,time) VALUES('$admin','$action','$user','$time')");

if($r){
    mysqli_query($conn, "INSERT INTO tblidprinting(studno,status) VALUES('$studNo','Not Printed') ");
    header('location: index.php');
}else{
    header('location: ../404.php');
}
?>