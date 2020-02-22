<?php
session_start();
include "../connection.php";
if(isset($_SESSION['username'])){
    header('location:index.php');
	exit;
}
$a = false;
if(isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']) && trim($_POST['username']) != '' && trim($_POST['password']) != ''){
    $username = mysqli_real_escape_string($conn, htmlspecialchars(trim(stripslashes($_POST['username']))));
    $password = mysqli_real_escape_string($conn, htmlspecialchars(trim(stripslashes($_POST['password']))));
    $result = mysqli_query($conn, "SELECT * FROM tbladmin WHERE username='$username' AND password='$password'");
    if(mysqli_num_rows($result) == 1){
        $_SESSION['username'] = $username;
        $_SESSION['type'] = mysqli_fetch_assoc($result)['type'];
		$_SESSION['tab'] = 'dashboard';
		header('location:index.php');
    }else{
        $a = true;
        echo '<script>alert("What are u doin step bro?")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CoRES Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style-responsive.css" rel="stylesheet" />
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
</head>

<body class="login-body">
<div class="container">
    <h1 class="sign-up-title"></h1>
    <form class="form-signin" method="post">
        <h2 class="form-signin-heading"><img src="../assets/images/logo1.png" /></h2>
        <div class="login-wrap">
            <input type="text" name="username" class="form-control username" placeholder="Username" autocomplete="off" autofocus required>
            <input type="password" name="password" class="form-control password" placeholder="Password" autocomplete="off" required>
            <input type="submit" name="submit" value="Login" class="btn btn-lg btn-login btn-block login_submit">
        </div>
    </form>
</div>
</body>
</html>
<?php if($a):?>
<style>
.login-wrap{
    background-image: url(https://i.giphy.com/media/fVVYV2SPnwYNH9OOZ5/giphy.webp);
}
.form-control{
    background-color: transparent;
}
</style>
<?php endif;?>