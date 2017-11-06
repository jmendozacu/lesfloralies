<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';
Mage::app();
$postdata = file_get_contents("php://input");
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
$date=date("Y-m-d");
$request = json_decode($postdata);
$splitcardlength=count($request->splitCart);
//echo "SP Length ".$splitcardlength;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM splitorderDetails";
$result = $conn->query($query);
if(empty($result)) {
					$query = "CREATE TABLE splitorderDetails (
							 orderId Integer auto_increment primary key,
							 creation_date varchar(15),
							 tax integer,
							 discount integer,
							 shipping integer,
							 total integer,
							 cust_id varchar(20),
							 cust_name varchar(50),
							 cust_email varchar(50),
							 cust_phone varchar(15),
							 cust_address varchar(255)
							 )";
				$conn->query($query);
				$query="create table splitorderProducts(
						 id integer auto_increment primary key,
						 product_id integer,
						 product_quantity integer,
						 product_image varchar(255),
						 product_name varchar(50),
						 product_price varchar(50),
						 toshipname varchar(50),
						 toshipAddress varchar(50),
						 toshipphone varchar(50),
						 toshipdate varchar(50),
						 orderId Integer
				)";
				$conn->query($query);
				$query="Create table splitorderpaymentOptions(
						id integer auto_increment primary key,
						paymenttype varchar(50),
						amount varchar(50),
						orderId Integer
				)";
				$conn->query($query);
}
$cash=$request->cashAmountForSplit;
$giftAmountForSplit=$request->giftAmountForSplit;
$giftCardNumberForSplit=$request->giftCardNumberForSplit;
$creditAmountForSplit=$request->creditAmountForSplit;
$discount=$request->discount;
$shipping=$request->shipping;
$tax=$request->taxamount;
$customerid=$request->splitCart[0]->customer->id;
$customerName=$request->splitCart[0]->customer->customerName;
$customerEmail=$request->splitCart[0]->customer->customerEmail;
$customerCellPhone=$request->splitCart[0]->customer->customerCellPhone;
$customerAddress=$request->splitCart[0]->customer->customerAddress;
$totalAmount=$request->totalAmount;
//echo $discount." ".$shipping." ".$tax." ".$customerid."".$customerName."".$customerEmail." ".$customerCellPhone." ".$customerAddress;
$query="Insert into splitorderDetails(creation_date,tax,discount,shipping,total,cust_id,
cust_name,cust_email,cust_phone,cust_address) VALUES ('$date','$tax','$discount','$shipping','$totalAmount',
'$customerid','$customerName','$customerEmail','$customerCellPhone','$customerAddress')";
$conn->query($query);
$sql="SELECT orderId FROM splitorderDetails where cust_email='$customerEmail'";
$resultset=$conn->query($sql);
$orderid=0;
		if($resultset->num_rows > 0)
		{
			while($row=$resultset->fetch_assoc())
			{
			$orderid=$row["orderId"];
			}
		}
		if($giftCardNumberForSplit!="")
		{
			$query="Select * from cardDetails where cardno='$giftCardNumberForSplit'";
				$result=$conn->query($query);
				$currentbalance=0;
				if($result->num_rows>0)
				{
				while($rowdata=$result->fetch_assoc())
					{
					 $currentbalance=$rowdata['balance'];
					}
				}	
				$currentbalance=$currentbalance-$giftAmountForSplit;
				$query="update cardDetails set balance=$currentbalance where cardno='$giftCardNumberForSplit'";
				$conn->query($query);

		}			   for($i=0;$i<$splitcardlength;$i++)
				{
					$cartincardlength=count($request->splitCart[$i]->cart);
					//echo "cart length ".$cartincardlength;
					for($j=0;$j<$cartincardlength;$j++)
					{
					$quantity=$request->splitCart[$i]->cart[$j]->quantity;
					$price=$request->splitCart[$i]->cart[$j]->total;
					$proid=$request->splitCart[$i]->cart[$j]->product->ID;
					$Name=$request->splitCart[$i]->cart[$j]->product->Name;
					$Image=$request->splitCart[$i]->cart[$j]->product->Image;
					$shipName=$request->splitCart[$j]->shippment->shipName;
					$shipPhone=$request->splitCart[$j]->shippment->shipPhone;
					$shipAddress=$request->splitCart[$j]->shippment->shipAddress;
					$toshipdate=$request->splitCart[$j]->shippment->toshipdate;
					//echo $quantity." ".$price." ".$proid." ".$Name."".$Image."".$shipName." ".$shipPhone." ".$shipAddress." ".$toshipdate;
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

					$query="Insert into splitorderProducts(product_id,product_quantity,product_image,product_name,product_price,toshipname,toshipAddress,toshipphone,toshipdate,orderId)
					VALUES ('$proid','$quantity','$Image','$Name',' $price','$shipName','$shipAddress','$shipPhone','$toshipdate','$orderid')";
				$conn->query($query);
				}
				}
		echo json_encode(array("OrderID"=>$orderid,"Code"=>"200"));
?>