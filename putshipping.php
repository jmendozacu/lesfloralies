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
 $shippingamount = $request->shippingamount;
  $shippingchk  = $request->shippingcheck;
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM shippingDetails";
$result = $conn->query($query);

if(empty($result)) {
                $query = "CREATE TABLE shippingDetails (
                         id integer primary key,
					     shippingvalue integer,
						 shippingkey integer
                          )";
                $result = $conn->query($query);
				$result = $conn->query("Insert into shippingDetails(id,shippingvalue,shippingkey) 
				values (1,'$shippingamount','$shippingchk')");
}
if($shippingchk==1)
{
$sql="update shippingDetails SET shippingvalue='$shippingamount',shippingkey='$shippingchk' where id=1";	
$conn->query($sql);
echo 1;
}
else{
$sql="update shippingDetails SET shippingkey=0 where id=1";	
$conn->query($sql);	
echo 1;
}

?>