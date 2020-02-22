<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['type']==0){
    header('location: login.php');
    exit;
}
include '../connection.php';
$sql = "SELECT
UPPER(CONCAT(fname,' ',mname,(IF(mname='','',' ')),lname)) AS `Full Name`,
status as `Payment Status`,
email as `Email`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`11`) AS `Day 1 Morning`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`12`) AS `Day 1 Afternoon`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`21`) AS `Day 2 Morning`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`22`) AS `Day 2 Afternoon`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`31`) AS `Day 3 Morning`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`32`) AS `Day 3 Afternoon`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`41`) AS `Day 4 Morning`,
(SELECT c.name FROM tblseminarinfo c WHERE c.seminarid=b.`42`) AS `Day 4 Afternoon`,
(SELECT e.name FROM tblworkshopinfo e WHERE e.workshopid=d.`1`) AS `Day 1`,
(SELECT e.name FROM tblworkshopinfo e WHERE e.workshopid=d.`2`) AS `Day 2`,
(SELECT e.name FROM tblworkshopinfo e WHERE e.workshopid=d.`3`) AS `Day 3`
FROM tblpersonaldata a JOIN tblseminars b ON a.studNo=b.studNo JOIN tblworkshops d ON a.studNo=d.studNo";
$result = mysqli_query($conn, $sql);
$filename = "cores2020";
$file_ending = "xls";
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");
$sep = "\t";
$head = mysqli_fetch_assoc($result);
foreach($head as $key=>$value){
    echo $key."\t";
}
echo "\n";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result))
{
    $schema_insert = "";
    for($j = 0; $j < 14;$j++)
    {
        if(!isset($row[$j]))
            $schema_insert .= $sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
