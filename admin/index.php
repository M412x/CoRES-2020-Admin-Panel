<?php
session_start();
if(!isset($_SESSION['username'])){
	header('location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="../favicon.png">

    <title>CoRES Admin Panel</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<section id="container">
    <header class="header white-bg" style="background: snow">
        <div class="sidebar-toggle-box">
            <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
        </div>
        <a href="index.php" class="logo"><img src="../assets/images/logo1.png" style="width:25%;"></a>
    </header>
    <aside>
        <div id="sidebar"  class="nav-collapse " style="z-index:1">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
					<?php echo '<a '.($_SESSION['tab'] == 'dashboard' ? 'class="active" href="#"' : ' href="dashboard.php"').'>'?>
                        <i class="icon-dashboard"></i><span>Dashboard</span>
					</a>
                </li>
                <?php if($_SESSION['type'] == 1 || $_SESSION['type'] == 0):?>
				<li>
					<?php echo '<a '.($_SESSION['tab'] == 'payment' ? 'class="active" href="#"' : ' href="payment.php"').'>'?>
                        <i class="icon-warning-sign"></i><span>Payment</span>
                    </a>
                </li>
                <?php endif;?>
				<li>
					<?php echo '<a '.($_SESSION['tab'] == 'details' ? 'class="active" href="#"' : ' href="details.php"').'>'?>
                        <i class="icon-user"></i><span>Personal Informations</span>
					</a>
                </li>
                <?php if($_SESSION['type'] == 1 || $_SESSION['type'] == 0):?>
				<li>
					<?php echo '<a '.($_SESSION['tab'] == 'id' ? 'class="active" href="#"' : ' href="id.php"').'>'?>
                        <i class="icon-print"></i><span>ID Printing</span>
					</a>
                </li>
                <?php endif;?>
                <?php if($_SESSION['type'] == 1):?>
                <li>
                    <?php echo '<a '.($_SESSION['tab'] == 'bulk' ? 'class="active" href="#"' : ' href="bulk.php"').'>'?>
                    <i class="icon-group"></i><span>Bulk Registration</span>
                    </a>
                </li>
                <li>
                    <?php echo '<a '.($_SESSION['tab'] == 'signup' ? 'class="active" href="#"' : ' href="signup.php"').'>'?>
                    <i class="icon-plus-sign"></i><span>Admin Maker</span>
                    </a>
                </li>
                <li>
                    <?php echo '<a '.($_SESSION['tab'] == 'records' ? 'class="active" href="#"' : ' href="records.php"').'>'?>
                    <i class="icon-search"></i><span>Records</span>
                    </a>
                </li>
				<li>
                    <?php echo '<a '.($_SESSION['tab'] == 'logs' ? 'class="active" href="#"' : ' href="logs.php"').'>'?>
                    <i class="icon-list"></i><span>Admin Logs</span>
                    </a>
                </li>
                <?php endif;?>
                <?php if($_SESSION['type'] == 2 || $_SESSION['type'] == 1):?>
                    <li>
                        <?php echo '<a href="excel.php">'?>
                        <i class="icon-download-alt"></i><span>Download Infos</span>
                        </a>
                    </li>
                <?php endif;?>
				<li><a href="logout.php"><i class="icon-key"></i> Log Out (<?php echo $_SESSION['username']?>)</a></li>
            </ul>
        </div>
    </aside>
    <section id="main-content" >
        <section class="wrapper">
            <!-- page start-->
			<div>
				<?php if($_SESSION['tab'] == 'dashboard') include"dashboard.php";?>
				<?php if($_SESSION['tab'] == 'payment') include"payment.php";?>
				<?php if($_SESSION['tab'] == 'details') include"details.php";?>
                <?php if($_SESSION['tab'] == 'id') include"id.php";?>
                <?php if($_SESSION['tab'] == 'signup') include"signup.php";?>
                <?php if($_SESSION['tab'] == 'records') include"records.php";?>
				<?php if($_SESSION['tab'] == 'logs') include"logs.php";?>
				<?php if($_SESSION['tab'] == 'bulk') include"bulk.php";?>
			</div>
			<br>

            <!-- page end-->
        </section>
    </section>
    <footer class="site-footer">
        <div class="text-center">
            <?php echo date('Y')?> &copy; R3d N0rth
            <a href="#" class="go-top">
                <i class="icon-angle-up"></i>
            </a>
        </div>
    </footer>
</section>

</body>
</html>
<style>
tbody tr:hover{
	background:snow;
}
.site-footer{
	position:fixed;
	left:0;
	bottom:0;
	width:100%;
}

</style>
