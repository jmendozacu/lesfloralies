<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
Mage::app();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname =$request->uname;
$date=date("Y-m-d");
$query="select * from DrawerDetails where username='$uname' and date='$date'";
$resultset = $conn->query($query);
if(empty($resultset)) {
	echo -1;
}
else{
$resultset=$conn->query($query);
if($resultset->num_rows > 0)
{
	while($row=$resultset->fetch_assoc())
	{
		 $opbalance=$row["opbalance"];
		 $clbalance=$row["clbalance"];
		 echo json_encode(array("Code"=>"200","opbalance"=>$opbalance,"clbalance"=>$clbalance));
		 exit;
 	}
}	
}
?>