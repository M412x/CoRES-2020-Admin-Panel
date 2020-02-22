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
$time = date("Y-m-d H:i:s", time()+28800);
$user = $_SESSION['username'];
$r = mysqli_query($conn, "UPDATE tblidprinting SET status='Printed', printedtime='$time', printedby='$user' WHERE studNo='$studNo'");
$res = mysqli_fetch_assoc($res);
$admin = $_SESSION['username'].$_SERVER['REMOTE_ADDR'];
$action = 'printed id';
$user = $res['fname'].' '.$res['mname'].' '.$res['lname'];
mysqli_query($conn, "INSERT INTO tbladminlogs(admin,action,user,time) VALUES('$admin','$action','$user','$time')");

if($r){
    header('location: index.php');
}else{
    header('location: ../404.php');
}
?>