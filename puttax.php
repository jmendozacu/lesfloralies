<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
 require_once 'app/Mage.php';
 Mage::app();
 Mage::setIsDeveloperMode(true);
 Mage::app('admin'); 

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$taxamount = $request->taxamount;
$taxchk = $request->check;
 $servername = "localhost";
 $username = "phpmyadmin";
 $password = "abcd1234";
 $dbname = "phpmyadmin";
//$servername = "localhost";
//$username = "root";
//$password = "";
//$dbname = "magento";
$proid=array();
$mainobj=new stdClass();
$output=array();
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM taxdetail";
$result = $conn->query($query);

if(empty($result)) {
                $query = "create table taxdetail(
					id integer primary key,
					taxvalue integer,
					taxkey integer
				)";
                $result = $conn->query($query);
				$result = $conn->query("Insert into taxdetail(id,taxvalue,taxkey) values (1,'$taxamount','$taxchk')");
}
if($taxchk==1)
{
$sql="update taxdetail SET taxvalue='$taxamount',taxkey='$taxchk' where id=1";	
$conn->query($sql);
echo 1;
}
else{
$sql="update taxdetail SET taxkey=0 where id=1";	
$conn->query($sql);	
echo 1;
}
?>