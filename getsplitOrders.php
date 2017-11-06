<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";

$orderslist=array();
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM splitorderDetails";
$result = $conn->query($query);
if(empty($result)) {
	echo -1;
}
else
{
$sql="SELECT * from splitorderDetails ORDER BY orderId DESC";
$resultset = $conn->query($sql);
if($resultset->num_rows > 0)
		{
					while($row=$resultset->fetch_assoc())
					{
						$Orderid=$row["orderId"];
						$creation_date=$row["creation_date"];
						$tax=$row["tax"];
						$discount=$row["discount"];
						$shipping=$row["shipping"];
						$total=$row["total"];
						$customerid=$row["cust_id"];
						$cust_name=$row["cust_name"];
						$cust_email=$row["cust_email"];
						$cust_no=$row["cust_phone"];
						$cust_address=$row["cust_address"];
$customerarray=array("OrderID"=>$Orderid,"Cusid"=>$customerid,"CusPhone"=>$cust_no,"Date"=>$creation_date,"Email"=>$cust_email,"Name"=>$cust_name,"Address"=>$cust_address,
"Tax"=>$tax,"Discount"=>$discount,"Shipping"=>$shipping);					
$productssql="Select * from splitorderProducts where orderId='$Orderid'";
$result=$conn->query($productssql);	
		if($result->num_rows>0)
		{
			$productlist=array();
			while($rowdata=$result->fetch_assoc())
					{
							$product_id=$rowdata["product_id"];
							$product_quantity=$rowdata["product_quantity"];
							$product_name=$rowdata["product_name"];
							$product_image=$rowdata["product_image"];
							$product_price=$rowdata["product_price"];
							$toshipname=$rowdata["toshipname"];
							$toshipAddress=$rowdata["toshipAddress"];
							$toshipphone=$rowdata["toshipphone"];
							$toshipdate=$rowdata["toshipdate"];
							$proli=	new stdClass;
							$proli->ID=$product_id;
							$proli->Quantity=$product_quantity;
							$proli->Name=$product_name;
							$proli->Image=$product_image;
							$proli->Price=$product_price;
							$proli->toshipname=$toshipname;
							$proli->toshipAddress=$toshipAddress;
							$proli->toshipphone=$toshipphone;
							$proli->toshipdate=$toshipdate;							
							$productlist[]=array("product"=>$proli);
					}
				$orderslist[]=array("Customer"=>$customerarray,"Products"=>$productlist);
		}
		}
		}
				 echo json_encode($orderslist);
}
?>