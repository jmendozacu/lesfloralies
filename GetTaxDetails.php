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
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$output=array();
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$sql = "SELECT * FROM taxdetail where id=1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // echo "id: " .$row["id"]. "Amount".$row["taxvalue"]. "Key".$row["taxkey"]. "<br>";
		$result=$row["taxvalue"];
		$key=$row["taxkey"];
    $output=array("Amount"=>$result,"TaxCheck"=>$key);
    echo json_encode($output);	
	exit;
	// array_push($proid,$row["product_id"]);
    }
} else {
    echo -1;
	exit;
}
}
?>