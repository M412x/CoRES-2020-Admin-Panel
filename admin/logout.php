<?php
session_start();
session_destroy();
header('location:login.php');
 ?>
<head>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
</head>
<div class="navbar navbar-expand-md">
    <a href="#" class="navbar-brand">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="collapsibleNavbar"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="navbar-item">
                <a href="#" class="nav-link">LInk</a>
            </li>
            <li class="navbar-item">
                <a href="#" class="nav-link">LInk</a>
            </li>
            <li class="navbar-item">
                <a href="#" class="nav-link">LInk</a>
            </li>
        </ul>

    </div>
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
