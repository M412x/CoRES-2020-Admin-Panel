<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type'] == 0){
    header('location: login.php');
    exit;
}
if($_SESSION['tab'] != 'signup'){
    $_SESSION['tab'] = 'signup';
    header('location: index.php');
    exit;
}else {
    include "../connection.php";
    if(isset($_POST['submit'])){
        $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['username']))))) : "";
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['password']))))) : "";
        $type = isset($_POST['type']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['type']))))) : "";

        $q = "INSERT INTO tbladmin(username,password,`type`) VALUES('$username','$password','$type')";
        if(!mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbladmin WHERE username='$username'"))){
            $res = mysqli_query($conn, $q);
        }
    }
    ?>
    <h1 align="center">Admin Maker</h1>
    <form class="form-inline" method="post">
        <div class="col-md-12">
            <div class="col-md-4">
                <label for="username">Username</label>
                <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Username" id="username" name="username" minlength="4">
            </div>
            <div class="col-md-4">
                <label for="password">Password</label>
                <input type="password" class="form-control mb-2 mr-sm-2" placeholder="Password" id="password" name="password" min="4">
            </div>
            <div class="col-md-2">
                <label for="type">Admin Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="0">Admin</option>
                    <option value="1">Master Admin</option>
                </select>
            </div>
            <div class="col-md-2" style="margin-top: 2.3rem">
                <input type="submit" value="Submit" name="submit" class="btn btn-primary">
            </div>
        </div>
    </form>
    <?php if(isset($_POST['submit']) && $res){
        $admin = $_SESSION['username'];
        $action = 'make admin account';
        $user = $_POST['username'];
        $time = date("Y-m-d H:i:s", time()+28800);
        mysqli_query($conn, "INSERT INTO tbladminlogs(admin,action,user,time) VALUES('$admin','$action','$user','$time')");

        ?>
        <div class="alert alert-success" role="alert" style="margin-top: 10rem">
            <strong>Account Created Successfully!</strong> <?php echo $username;?> with <?php echo $type?'Master Admin':'Admin';?> Privileges.
        </div>
        <?php
    }else if(isset($_POST['submit'])){
        ?>
        <div class="alert alert-danger" role="alert" style="margin-top: 10rem">
            <strong>Failed Creating Account!</strong> Can't create <?php echo $username;?> with <?php echo $type?'Master Admin':'Admin';?> Privileges.
        </div>
        <?php
    }
    ?>
<?php }?>