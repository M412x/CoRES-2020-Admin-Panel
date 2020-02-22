<?php
include '../connection.php';
error_reporting(0);
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] == 0){
    header('location: login.php');
    exit;
}
if(!isset($_GET['studNo']) || trim($_GET['studNo']) == ''){
    header('location: login.php');
    exit;
}
if($_SESSION['tab'] != 'records'){
    header('location: login.php');
    exit;
}
$studNo = trim($_GET['studNo']);
if(!preg_match('^2020-[0-9]{1,11}-cores*$^', $studNo)){
    header('location: login.php');
    exit;
}
$admin = $_SESSION['username'];
$action = 'delete';
$user = $studNo;
$time = date("Y-m-d H:i:s", time()+28800);
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblpersonaldata a JOIN tblseminars b ON a.studNo=b.studNo JOIN tblworkshops c ON a.studNo=c.studNo WHERE a.studNo='$studNo'"));
$data = json_encode($data);
mysqli_query($conn, "INSERT INTO tbladminlogs(admin,action,user,time,comments) VALUES('$admin','$action','$user','$time','$data')");

mysqli_query($conn, "DELETE FROM tblpersonaldata WHERE studNo='$studNo'");
mysqli_query($conn, "DELETE FROM tblidprinting WHERE studNo='$studNo'");
mysqli_query($conn, "DELETE FROM tblseminars WHERE studNo='$studNo'");
mysqli_query($conn, "DELETE FROM tblworkshops WHERE studNo='$studNo'");

header('location: index.php');
exit;
