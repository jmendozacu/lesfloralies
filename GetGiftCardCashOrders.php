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
$query = "SELECT * FROM GiftCardOrderDetails";
$result = $conn->query($query);
if(empty($result)) {
	echo -1;
}
else
{
$sql="SELECT orderId,creation_date,card_no,tax,discount,shipping,total,payment_type,cust_name,cust_email,cust_no,cust_address, toShipName,toShipPhone,toshipAddress,toshipdate from GiftCardOrderDetails order by orderId DESC";
$resultset = $conn->query($sql);
if($resultset->num_rows > 0)
				{
					while($row=$resultset->fetch_assoc())
					{
						$Orderid=$row["orderId"];
						$creation_date=$row["creation_date"];
						$card_no=$row["card_no"];
						$tax=$row["tax"];
						$discount=$row["discount"];
						$shipping=$row["shipping"];
						$total=$row["total"];
						$payment_type=$row["payment_type"];
						$cust_name=$row["cust_name"];
						$cust_email=$row["cust_email"];
						$cust_no=$row["cust_no"];
						$cust_address=$row["cust_address"];
 						$cust_phone=$row["cust_phone"];
						$toShipName=$row["toShipName"];
						$toShipPhone=$row["toShipPhone"];
						$toshipAddress=$row["toshipAddress"];
						$toshipdate=$row["toshipdate"];
                                            $customerarray=array("OrderID"=>$Orderid,"Date"=>$creation_date,"Email"=>$cust_email,"Name"=>$cust_name,
"Tax"=>$tax,"Total"=>$total);
$shippingarray=array("Name"=>$toShipName,"Phone"=>$toShipPhone,"Address"=>$toshipAddress,"ShippingDate"=>$toshipdate);
$billingingarray=array("Name"=>$cust_name,"Phone"=>$cust_phone,"Address"=>$cust_address);
$productssql="Select product_id, product_quantity, product_image, product_name, product_price from GiftCardProducts where orderId='$Orderid'";
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
							$productlist[]=array(
							"Id"=>$product_id,
							"Quantity"=>$product_quantity,
							"ProductName"=>$product_name,
							"Image"=>$product_image,
							"ProductPrice"=>$product_price);
					}
					$orderslist[]=array("Customer"=>$customerarray,"Shipping"=>$shippingarray,"Billing"=>$billingingarray,"Products"=>$productlist);
	}
	}
					
				}
				echo json_encode($orderslist);
}


;
?>