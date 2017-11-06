<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
$date=date("Y-m-d");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$total=$request->cart[0]->total;
$taxamount=$request->taxamount;
$shipping=$request->shipping;
$discount=$request->discount;
$customerid=$request->customer->id;
$customerName=$request->customer->customerName;
$customerEmail=$request->customer->customerEmail;
$customerCellPhone=$request->customer->customerCellPhone;
$customerAddress=$request->customer->customerAddress;
$length=$request->length;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM pauseOrder";
$result = $conn->query($query);
if(empty($result)) {
					$query = "CREATE TABLE pauseOrder (
							 orderId Integer auto_increment primary key,
							 creation_date varchar(15),
							 tax integer,
							 discount integer,
							 shipping integer,
							 total integer,
							 cusid integer,
							 cust_name varchar(50),
							 cust_email varchar(50),
							 cust_no varchar(15),
							 cust_address varchar(255)
							  )";
				$conn->query($query);
				$query="create table pauseOrderProducts(
						 id integer auto_increment primary key,
						 product_id integer,
						 product_quantity integer,
						 product_image varchar(255),
						 product_name varchar(50),
						 product_price varchar(50),
						 box varchar(50),
						 boxAddon varchar(50),
						 boxColor varchar(50),
						 size varchar(50),
						 sizeAddon varchar(50),
						 orderId Integer,
						 Foreign key(orderId) References pauseOrder(orderId)
				)";
				$conn->query($query);
				}
				$query="Insert into pauseOrder(creation_date,tax,discount,shipping,total,cusid,
				cust_name,cust_email,cust_no,cust_address)
				VALUES ('$date','$taxamount','$discount','$shipping','$total','$customerid','$customerName','$customerEmail','$customerCellPhone','$customerAddress')";
				$conn->query($query);
				$orderid=0;
				$sql="SELECT MAX(orderId) FROM pauseOrder";
				$resultset=$conn->query($sql);
				if($resultset->num_rows > 0)
				{
					while($row=$resultset->fetch_assoc())
					{
						$orderid=$row["MAX(orderId)"];
					}
				}
				for($i=0;$i<$length;$i++)
				{
				 $quantity=$request->cart[$i]->quantity;
				 $proid=$request->cart[$i]->product->ID;
				 $Name=$request->cart[$i]->product->Name;
				 $Image=$request->cart[$i]->product->Image;
				 $price=$request->cart[$i]->product->price;
				 $box=$request->cart[$i]->box;
				 $boxAddon=$request->cart[$i]->boxAddon;
				 $boxColor=$request->cart[$i]->boxColor;
				 $size=$request->cart[$i]->size;
				 $sizeAddon=$request->cart[$i]->sizeAddon;
				$query="Insert into pauseOrderProducts(product_id,product_quantity,product_image,product_name,product_price,box,boxAddon,boxColor,size,sizeAddon,orderId)
				VALUES ('$proid','$quantity','$Image','$Name',' $price','$box','$boxAddon','$boxColor','$size','$sizeAddon','$orderid')";
				$conn->query($query);
				}			
				echo 1;
				$conn->close();
?>