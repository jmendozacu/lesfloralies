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
$opbalance = $request->opbalance;
$clbalance = $request->clbalance;
$date=date("Y-m-d");
$query = "SELECT * FROM DrawerDetails";
$result = $conn->query($query);
if(empty($result)) {
				       $query = "CREATE TABLE DrawerDetails (
							 Id Integer auto_increment primary key,
							 username varchar(50),
							 date varchar(10),
							 opbalance integer,
							 clbalance integer
							  )";
					$conn->query($query);
}

$query="select * from DrawerDetails where username='$uname' and date='$date'";
$resultset=$conn->query($query);
if($resultset->num_rows > 0)
{
	while($row=$resultset->fetch_assoc())
	{
		 $query="update DrawerDetails set clbalance='$clbalance' where username='$uname' and date='$date'";
		 $conn->query($query);
		 echo 1;
 	}
}
else{
	$clbalance=0;
	$query="insert into DrawerDetails(username,date,opbalance,clbalance) VALUES('$uname','$date','$opbalance','$clbalance')";
	$conn->query($query);
	echo 1;
}
?>