<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location: login.php');
    exit;
}
include "../connection.php";
$studNo = htmlspecialchars(stripslashes($_GET['studNo']));
function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
if(!isset($_GET['studNo']) || mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblpersonaldata WHERE studNo='$studNo'")) == 0){
//    header('location: ../404.php');
//    exit;
}else{
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblpersonaldata WHERE studNo='$studNo'"));
    //header('Content-Type: image/png');
    //header('Content-Disposition: attachment; filename="'.$r['fname'].' '.$r['mname'].' '.$r['lname'].'.png"');
    //$image = file_get_contents_curl("http://chart.apis.google.com/chart?chs=300x300&cht=qr&chld=L|0&chl=".urlencode(strrev(base64_encode($studNo))));
    //header('Content-Length: ' . strlen($image));
    //echo $image;
    header("location: http://chart.apis.google.com/chart?chs=300x300&cht=qr&chld=L|0&chl=".urlencode(strrev(base64_encode($studNo))));
}
?>