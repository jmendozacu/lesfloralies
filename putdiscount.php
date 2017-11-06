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
 $disountamount = $request->discountamount;
 $discountchk = $request->check;
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM discountDetails";
$result = $conn->query($query);

if(empty($result)) {
                $query = "CREATE TABLE discountDetails (
                         id integer primary key,
					     discountvalue integer,
						 discountkey integer
                          )";
                $result = $conn->query($query);
				$result = $conn->query("Insert into discountDetails(id,discountvalue,discountkey) values (1,'$disountamount','$discountchk')");
}
if($discountchk==1)
{
$sql="update discountDetails SET discountvalue='$disountamount',discountkey='$discountchk' where id=1";	
$conn->query($sql);
echo 1;
}
else{
$sql="update discountDetails SET discountkey=0 where id=1";	
$conn->query($sql);	
echo 1;
}

?>