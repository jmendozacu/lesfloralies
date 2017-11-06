<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';
Mage::app();
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
$date=date("Y-m-d");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$cardNumber=$request->cardNumber;
$total=$request->cart[0]->total;
$taxamount=$request->taxamount;
$shipping=$request->shipping;
$discount=$request->discount;
$customerid=$request->customer->id;
$customerName=$request->customer->customerName;
$customerEmail=$request->customer->customerEmail;
$customerCellPhone=$request->customer->customerCellPhone;
$customerAddress=$request->customer->customerAddress;
$ShippmentMethod=$request->ShippmentMethod;
$shipNamee=$request->shippmentObject->shipName;
$shipPhonee=$request->shippmentObject->shipPhone;
$shipAddresss=$request->shippmentObject->shipAddress;
$toshipdatee=$request->shippmentObject->toshipdate;
$length=$request->length;


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM GiftCardOrderDetails";
$result = $conn->query($query);
if(empty($result)) {
					$query = "CREATE TABLE GiftCardOrderDetails (
							 orderId Integer auto_increment primary key,
							 creation_date varchar(40),
							 card_no varchar(255),
							 tax integer,
							 discount integer,
							 shipping integer,
							 total integer,
							 payment_type varchar(20),
							 cust_name varchar(50),
							 cust_email varchar(50),
							 cust_no varchar(15),
							 cust_phone varchar(15),
							 cust_address varchar(255),
							 toshipName varchar(50),
							 toShipPhone varchar(15),
							 toshipAddress varchar(255),
							 toshipdate varchar(40)
							  )";
				$conn->query($query);
				$query="create table GiftCardProducts(
						 id integer auto_increment primary key,
						 product_id integer,
						 product_quantity integer,
						 product_image varchar(255),
						 product_name varchar(50),
						 product_price varchar(50),
						 orderId Integer
				)";
				$conn->query($query);
}

				
				$query="Select * from cardDetails where cardno='$cardNumber'";
				$result=$conn->query($query);
				$currentbalance=0;
				$flag=false;
				if($result->num_rows>0)
				{
				while($rowdata=$result->fetch_assoc())
				{
				$cid=$rowdata['cus_id'];
				if($cid==$customerid)
				{
					$flag=true;
				$currentbalance=$rowdata['balance'];
				$currentbalance=$currentbalance-$total;
				$query="update cardDetails set balance=$currentbalance where cardno='$cardNumber'";
				$conn->query($query);
				}
					else
					{
						echo -2;
						exit;
					}
				}
				}
				else
				{
					echo -1;
					exit;
				}	
$datepieces=explode(" ",$toshipdatee);
$maindate=$datepieces[0].$datepieces[1];
				$query="Insert into GiftCardOrderDetails(creation_date,card_no,tax,discount,shipping,total,payment_type,
				cust_name,cust_email,cust_no,cust_phone,cust_address,toshipName,toShipPhone,toshipAddress,toshipdate) VALUES ('$date','$cardNumber','$taxamount','$discount','$shipping','$total',
				'$ShippmentMethod','$customerName','$customerEmail','$customerid','$customerCellPhone','$customerAddress','$shipNamee',
				'$shipPhonee','$shipAddresss','$datepieces[0]')";
				 $conn->query($query);
				$orderid=0;
				$sql="SELECT orderId FROM GiftCardOrderDetails where cust_email='$customerEmail'";
				$resultset=$conn->query($sql);
				if($resultset->num_rows > 0)
				{
					while($row=$resultset->fetch_assoc())
					{
						$orderid=$row["orderId"];
					}
				}			
				for($i=0;$i<$length;$i++)
				{
				 $quantity=$request->cart[$i]->quantity;
				 $proid=$request->cart[$i]->product->ID;
				 $Name=$request->cart[$i]->product->Name;
				 $Image=$request->cart[$i]->product->Image;
				 $price=$request->cart[$i]->product->price;
				 if($proid!=1111)
				 {
				$quantityy=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($proid)->getQty();
				 if($quantityy<$quantity)
				  {
					  echo -1;
					  exit;
				  }
				   $newQty=$quantityy-$quantity;
				   Mage::getModel('cataloginventory/stock_item')
								   ->loadByProduct($proid)
								   ->setQty($newQty)
								   ->save();	  
				 }
				$query="Insert into GiftCardProducts(product_id,product_quantity,product_image,product_name,product_price,orderId)
				VALUES ('$proid','$quantity','$Image','$Name',' $price','$orderid')";
				$conn->query($query);
				}			
				echo json_encode(array("OrderID"=>$orderid,"Code"=>"200","DeliveryDate"=>$toshipdatee));
				$conn->close();
				?>