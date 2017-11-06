<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$cardno=$request->cardNumber;
$cusid=$request->customer;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM cardDetails where cardno='$cardno'";
$result = $conn->query($query);
if($result->num_rows>0)
{

	$balance=0;
	while($row=$result->fetch_assoc()){ 


if($row["cus_id"]==$cusid)
{

		$balance=$row['balance'];
echo $balance;
}
else
{
echo -2;
}
	}
}
else
{
	echo -1;
}
?>