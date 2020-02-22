<?php
error_reporting(0);
session_start();
require ('../fpdf.php');
class PDF_reciept extends FPDF {
    function Header() {
        $this->Image('../bg.png',0,0,216,144);
    }
}
if(!isset($_SESSION['username'])){
    header('location: login.php');
    exit;
}

$names = array();
function show($id){
    global $names;
    echo '<span style="display: block"><input type="checkbox" id="'.$id.'" name="'.intval($id/10).'" value="'.$id.'">'.$names[$id].'</span>';
}

if($_SESSION['tab'] != 'bulk'){
    $_SESSION['tab'] = 'bulk';
    header('location: index.php');
    exit;
}else {
    include "../connection.php";
    $allseminars = mysqli_query($conn, 'SELECT * FROM tblseminarinfo');
    $allworkshops = mysqli_query($conn, 'SELECT * FROM tblworkshopinfo');
    $error = false;
    while($row = mysqli_fetch_assoc($allseminars)){
        $t = $row['seminarid'];
        $names[$t] = $row['name'];
    }
    while($row = mysqli_fetch_assoc($allworkshops)){
        $t = $row['workshopid'];
        $names[$t] = $row['name'];
    }

    if(isset($_POST['submit'])){
        $time = date("Y-m-d H:i:s", time()+28800);
        $today = date("Y-m-d H:i:s");
        $date = "2020-02-05 07:00:00";

        if ($today < $date) {
            $seminaMinCost = 200;
        }else{
            $seminaMinCost = 250;
        }
        $seminarCost = 50;
        $_11 = isset($_POST['11']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['11']))))) : "";
        $_12 = isset($_POST['12']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['12']))))) : "";
        $_21 = isset($_POST['21']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['21']))))) : "";
        $_22 = isset($_POST['22']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['22']))))) : "";
        $_31 = isset($_POST['31']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['31']))))) : "";
        $_32 = isset($_POST['32']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['32']))))) : "";
        $_41 = isset($_POST['41']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['41']))))) : "";
        $_42 = isset($_POST['42']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['42']))))) : "";

        $_1 = isset($_POST['1']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['1']))))) : "";
        $_2 = isset($_POST['2']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['2']))))) : "";
        $_3 = isset($_POST['3']) ? mysqli_real_escape_string($conn, trim(stripslashes(htmlentities(htmlspecialchars($_POST['3']))))) : "";

        $seminars = array($_11,$_12,$_21,$_22,$_31,$_32,$_41,$_42);
        $seminarcount = 0;

        $freeCount = 0;
        if($_11 == '111'){
            $freeCount++;
        }
        if($_12 == '122'){
            $freeCount++;
        }
        if($_41 == '411'){
            $freeCount++;
        }
        if($_42 == '421'){
            $freeCount++;
        }

        foreach($seminars as $seminar){
            if($seminar !== ""){
                $seminarcount++;
            }
        }

        $price = 0;
        $notFreeCount = $seminarcount - $freeCount;
        if($notFreeCount > 0){
            $price = $seminaMinCost;
            if($notFreeCount > 2)
                $price += ($notFreeCount-2)*50;
        }else if($freeCount > 0){
            $price = $seminaMinCost;
        }else{
            $price = 0;
        }

        $workshops = array($_1,$_2,$_3);

        foreach($workshops as $workshop){
            if($workshop !== ""){
                $price += 100;
            }
        }

        $lines = preg_split('/\r\n|[\r\n]/', $_POST['info']);
        foreach ($lines as $line){
            $info = explode('|', $line);
            $studNo = '2020-'.(mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM tblpersonaldata ORDER BY id DESC limit 1'))['id']+1).'-cores';
            $lname = htmlentities(stripslashes(trim($info[0])));
            $fname = htmlentities(stripslashes(trim($info[1])));
            $mname = htmlentities(stripslashes(trim($info[2])));
            $age = htmlentities(stripslashes(trim($info[3])));
            $number = htmlentities(stripslashes(trim($info[4])));
            $email = htmlentities(stripslashes(trim($info[5])));
            $school = htmlentities(stripslashes(trim($info[6])));
            $course = htmlentities(stripslashes(trim($info[7])));
            $result1 = mysqli_query($conn, "INSERT INTO tblpersonaldata(studNo,fname,mname,lname,age,email,number,school,course,status,registrationtime,payment) VALUES ('$studNo','$fname','$mname','$lname','$age','$email','$number','$school','$course','Not Paid','$time','$price')");
            $result2 = mysqli_query($conn, "INSERT INTO tblseminars(studNo,`11`,`12`,`21`,`22`,`31`,`32`,`41`,`42`) VALUES('$studNo','$_11','$_12','$_21','$_22','$_31','$_32','$_41','$_42')");
            $result3 = mysqli_query($conn, "INSERT INTO tblworkshops(studNo,`1`,`2`,`3`) VALUES('$studNo','$_1','$_2','$_3')");
            if(!$result1 || !$result2 || !$result3){
                echo '<script>alert("Failed to register.")</script>';
                $error = true;
            }
        }
        $rawsem = mysqli_query($conn, "SELECT * FROM tblseminarinfo");
        $rawwork = mysqli_query($conn, "SELECT * FROM tblworkshopinfo");

        $seminars = array();
        while($row = mysqli_fetch_assoc($rawsem)){
            if(in_array($row['seminarid'], array($_11,$_12,$_21,$_22,$_31,$_32,$_41,$_42))){
                array_push($seminars, $row['name']);
            }
        }

        $workshops = array();
        while($row = mysqli_fetch_assoc($rawwork)){
            if(in_array($row['workshopid'], array($_1,$_2,$_3))){
                array_push($workshops, $row['name']);
            }
        }
        $info = explode('|', $lines[0]);
        $lname = htmlentities(stripslashes(trim($info[0])));
        $fname = htmlentities(stripslashes(trim($info[1])));
        $mname = htmlentities(stripslashes(trim($info[2])));
        $age = htmlentities(stripslashes(trim($info[3])));
        $number = htmlentities(stripslashes(trim($info[4])));
        $email = htmlentities(stripslashes(trim($info[5])));
        $school = htmlentities(stripslashes(trim($info[6])));
        $course = htmlentities(stripslashes(trim($info[7])));

        $pdf = new PDF_reciept();
        $pdf->AddFont('Metropolis','','metropolis.php');
        $pdf->AddPage('P','half');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetFont('Metropolis', '', 12);
        $pdf->SetY(30);
        $pdf->SetX(6);
        $pdf->Cell(20, 7, "Name: ".$lname.', '.$fname.' '.$mname.' (BULK with '.count($lines).' slots)', 0, 1);
        $pdf->SetX(6);
        $pdf->Cell(20, 7, "School: ".$school, 0, 1);
        $pdf->SetX(6);
        $pdf->Cell(20, 7, "Date: ".date('F j, Y'), 0, 1);
        $pdf->Cell(200, 5, '', 0, 1);

        $pdf->SetFont('Metropolis', '', 11);
        $pdf->SetX(6);
        $pdf->Cell(122, 7, 'Thank you for registering for CoRES 2020! Here are your chosen ');
        $pdf->SetTextColor(46,116,181);
        $pdf->Cell(16, 7, 'seminars');
        $pdf->SetTextColor(0);
        $pdf->Cell(9, 7, ' and ');
        $pdf->SetTextColor(83,129,53);
        $pdf->Cell(21, 7, 'workshops');
        $pdf->SetTextColor(0);
        $pdf->Cell(17, 7, ':', 0, 1);

        $all = array_merge($seminars, $workshops);
        $y = 63;
        foreach ($all as $key=>$value){
            if($key < 5){
                $pdf->SetTextColor(0);
                $pdf->Cell(5, 5, ($key+1).'.', 0);
                if($key < count($seminars))
                    $pdf->SetTextColor(46,116,181);
                else
                    $pdf->SetTextColor(83,129,53);
                $pdf->Cell(16, 5, $value, 0, 1);
            }else{
                $pdf->SetXY(120,$y);
                $pdf->SetTextColor(0);
                $pdf->Cell(5, 5, ($key+1).'.', 0);
                if($key < count($seminars))
                    $pdf->SetTextColor(46,116,181);
                else
                    $pdf->SetTextColor(83,129,53);
                $pdf->Cell(16, 5, $value, 0, 1);
                $y+=5;
            }
        }
        if(count($all) < 5){
            $pdf->SetY(63+(count($all)*5));
        }else{
            $pdf->SetY(88);
        }
        $pdf->SetX(6);
        $pdf->SetTextColor(0);
        $pdf->Cell(48, 7,'Your total amount will be: ');
        $pdf->SetTextColor(255,0,0);
        $pdf->Cell(10, 7, ($price*count($lines)).'.00', 0, 1);
        $pdf->SetTextColor(0);
        $pdf->SetX(6);
        $pdf->Cell(70, 7,'Please proceed to any of the BDO branches near you for your payment. ' , 0, 1, 'L');
        $pdf->SetX(6);
        $pdf->Cell(68, 7,'Here is the account number needed: ' , 0, 0, 'L');
        $pdf->SetTextColor(255,0,0);
        $pdf->Cell(30,7, '0021 1270 6264',0,0);
        $pdf->SetTextColor(0);
        $pdf->Cell(0,7,'(Aleta Khaye Demano)',0,1);
        $pdf->SetX(6);
        $pdf->SetTextColor(0);
        $pdf->Cell(70, 7,'Please send your BANK TRANSACTION RECEIPT at our Facebook page (facebook.com/coresofficial) for' , 0, 1, 'L');
        $pdf->SetXY(6,114);
        $pdf->Cell(70, 5,'the validation of your slots. For inquiries and other concerns, please visit our page.' , 0, 1, 'L');
        $pdf->SetFontSize(18);
        $pdf->Cell(200,10,'THANK YOU AND SEE YOU AT CORES 2020!',0,0,'C');
        $pdf->Output('../receipts/'.$fname.' '.$mname.' '.$lname.'.pdf', 'F');
    }

    ?>
    <?php
    if(isset($_POST['submit']) && !$error){
        ?>
        <div class="alert alert-success" role="alert" style="margin-top: 1rem">
            <strong>Successfully Registered <?php echo count($lines);?> users</strong> in care of <?php echo $fname.' '.$mname.' '.$lname;?>.
            <a href="<?php echo '../receipts/'.$fname.' '.$mname.' '.$lname.'.pdf';?>" target="_blank">
                Please download this auto-generated <u>receipt</u>.
            </a>
        </div>
        <?php
    }elseif(isset($_POST['submit'])){
        ?>
        <div class="alert alert-danger" role="alert" style="margin-top: 1rem">
            <strong>May error pero di ko alam saan</strong>
        </div>
        <?php
    }
    ?>
    <h1 align="center">Bulk Registration</h1>
    <form class="form-inline" method="post">
        <div class="col-md-12">
            <label for="info">
                Format: <span style="font-weight: normal">Last Name|First Name|M.I.|Age|Contact Number|Email Address|School|Course</span>
                <br>
                Sample: <br>
                <span style="font-weight: normal">Dela Cruz|Juan|A|19|09123456789|juandelacruz@gmail.com|PUP-Manila|BS CpE</span><br>
                <span style="font-weight: normal">Dela Cruz|Andrea||25|09123456789|andreadelacruz@gmail.com|PUP-Manila|BS CpE</span><br>
                <span style="font-weight: normal">Dela Cruz|John|A|18|09123456789|johndelacruz@gmail.com|PUP-Manila|BS CpE</span>
            </label>
            <textarea name="info" id="info" rows="10" class="form-control"></textarea>
        </div>
        <br>
        <div class="col-md-12">
            <div class="col-md-6" style="margin-bottom: 5rem">
                <b>Seminars</b>
                <?php show(111);?>
                <?php show(121);?>
                <?php show(122);?>
                <?php show(123);?>
                <?php show(211);?>
                <?php show(212);?>
                <?php show(221);?>
                <?php show(222);?>
                <?php show(311);?>
                <?php show(321);?>
                <?php show(411);?>
                <?php show(421);?>
            </div>
            <div class="col-md-6">
                <b>Workshops</b>
                <?php show(11);?>
                <?php show(12);?>
                <?php show(21);?>
                <?php show(22);?>
                <?php show(31);?>
                <?php show(32);?>
                <br>
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    </form>
<?php }?>