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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$oid=$request->orderId;
$query = "delete FROM pauseOrder where orderId='$oid'";
$result = $conn->query($query);
$query = "delete FROM pauseOrderProducts where orderId='$oid'";
$result = $conn->query($query);
echo 1;
?>