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

$card_no=$request->cardNumber;
$balance=$request->balance;
$date=date("Y-m-d");
$var_id=$request->customerId;
$var_name = $request->customerName;
$var_email = $request->customerEmail;
$var_phone=$request->customerPhone;
$var_address=$request->customerAddress;

/*
$card_no='10';
 $balance='2500';
 $date='2016-10-10';
 $var_id='80';
 $var_name = 'cubian';
 $var_email = 'cubian@gmail.com';
 $var_phone='0909';
 $var_address='jaapan road';
*/
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM cardDetails";
$result = $conn->query($query);

if(empty($result)) {
                $query = "CREATE TABLE cardDetails (
                         cardno varchar(255) primary key,
						 creation_date varchar(255),
						 balance integer,
						 cus_id integer,
						 cust_name varchar(255),
						 cust_email varchar(255),
						 cust_no varchar(255),
						 cust_address varchar(255)
                          )";
                $result = $conn->query($query);
				$query = "CREATE TABLE cardRenewal (
                         id integer auto_increment,
						 cardno varchar(15),
						 renewaldate varchar(10),
						 balance integer,
						 cust_id integer,
						 primary key(id)
                          )";
                $result = $conn->query($query);

$query="Insert into cardDetails(cardno,creation_date,balance,cus_id,cust_name,cust_email,cust_no,cust_address) 
VALUES ('$card_no','$date','$balance','$var_id','$var_name','$var_email','$var_phone','$var_address')";
$conn->query($query);
echo 1;
exit;
}
else
{
$query = "SELECT * FROM cardDetails where cardno='$card_no'";
$result = $conn->query($query);
if($result->num_rows>0)
{      while($row=$result->fetch_assoc())
	   {
       	if($row["cus_id"]==$var_id){
		$currentbalance=0;
	    $currentbalance=$row["balance"];
       	$balance=$currentbalance+$balance;
		 $query="update cardDetails set balance='$balance' where cardno='$card_no'";
	$conn->query($query);
	    echo 1;
	    exit;
	   }
	 else{
		   echo -1;
	    	   exit;
	   }
	   
	   } 
	  		
}
	else{
		$query="Insert into cardDetails(cardno,creation_date,balance,cus_id,cust_name,cust_email,cust_no,cust_address) 
		VALUES ('$card_no','$date','$balance','$var_id','$var_name','$var_email','$var_phone','$var_address')";
		$conn->query($query);
		echo 1;	
exit;
	}
}

?>