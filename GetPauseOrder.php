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
$query = "SELECT * FROM pauseOrder";
$result = $conn->query($query);
if(empty($result)) {
	echo -1;
}
else
{
$sql="SELECT orderId,cusid,creation_date,tax,discount,shipping,total,cust_name,cust_email,cust_no,cust_address from pauseOrder";
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
						$customerid=$row["cusid"];
						$cust_name=$row["cust_name"];
						$cust_email=$row["cust_email"];
						$cust_no=$row["cust_no"];
						$cust_address=$row["cust_address"];
$customerarray=array("OrderID"=>$Orderid,"Cusid"=>$customerid,"Date"=>$creation_date,"Email"=>$cust_email,"Name"=>$cust_name,"Address"=>$cust_address,
"Tax"=>$tax,"Discount"=>$discount,"Shipping"=>$shipping);					
$productssql="Select * from pauseOrderProducts where orderId=$Orderid";
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
							$size=$rowdata["size"];
							$sizeAddon=$rowdata["sizeAddon"];
							$boxColor=$rowdata["boxColor"];
							$boxAddon=$rowdata["boxAddon"];
							$box=$rowdata["box"];	
							$proli=	new stdClass;
							$proli->ID=$product_id;
							$proli->Quantity=$product_quantity;
							$proli->Name=$product_name;
							$proli->Image=$product_image;
							$proli->price=$product_price;			
						/*	
						$pro = new stdClass;		
							$pro->box=$box;
							$pro->boxAddon=$boxAddon;
							$pro->boxColor=$boxColor;
							$pro->size=$size;					
							$pro->sizeAddon=$sizeAddon;
							$pro->total=$total;
						$productlist[]=array(
							"ID"=>$product_id,
							"Quantity"=>$product_quantity,
							"Name"=>$product_name,
							"Image"=>$product_image,
							"Price"=>$product_price);
						*/
						$productlist[]=array("product"=>$proli,"quantity"=>$product_quantity,"box"=>$box,"boxAddon"=>$boxAddon,
							"boxColor"=>$boxColor,"size"=>$size,"sizeAddon"=>$sizeAddon,"total"=>$total);
					}
				$orderslist[]=array("Customer"=>$customerarray,"Products"=>$productlist);
		}
		}
		}
				echo json_encode($orderslist);
}
?>