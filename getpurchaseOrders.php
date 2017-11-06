<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';
Mage::app();

$orders = Mage::getModel('sales/order')->getCollection()
   ->addFieldToFilter(status,"new")
   ->addAttributeToSort('increment_id',"DSC");
$mainarray=array();
foreach ($orders as $order) {
	$billingarray=array();
	$shippingarray=array();
	$customerarray=array();	
$orderdetail = Mage::getModel('sales/order')->loadByIncrementId($order["increment_id"]);
$customerarray=array("OrderID"=>$order["increment_id"],
	"Date"=>$orderdetail['created_at'],
	"Name"=>$order["customer_firstname"],
	"Tax"=>$orderdetail['tax_amount'],
	                     "Email"=>$order["customer_email"],
						 "Total"=>$order["grand_total"],
                                                     "Status"=>"Pending");
$oid=$order["increment_id"];
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query="Select * from DeliveryData where orderid='$oid'";
$result=$conn->query($query);
$deliverydata="";
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$deliverydata=$row["DeliveryDate"];
}
}
$shippingaddress=$orderdetail->getShippingAddress();

$shippingarray=array("Name"=>$shippingaddress['firstname'],
                     "Address"=>$shippingaddress['street'],
					 "Phone"=>$shippingaddress['telephone'],
					  "DeliveryDate"=>$deliverydata);
$billingaddress=$orderdetail->getBillingAddress();
$billingarray=array("Name"=>$billingaddress['firstname'],
                     "Address"=>$billingaddress['street'],
		     "Phone"=>$billingaddress['telephone']);
$order = Mage::getModel('sales/order')->loadByIncrementId($order["increment_id"]);
$items = $order->getAllVisibleItems();
 $productarray=array();
   foreach($items as $i){
	$cur_category =  Mage::getModel('catalog/product')->load($i['product_id']);
		 			 $img_url=$cur_category->getImageUrl() ;
	  $productarray[]=array("ProductId"=>$i['product_id'],
	                        "ProductName"=>$i['name'],
				 "ProductSku"=>$i['sku'],
				"ProductPrice"=>$i['price'],
				"Image"=>$img_url,
				"Options"=>$i['product_options']
	   );
   }
   $mainarray[]=array("Customer"=>$customerarray,"Shipping"=>$shippingarray,"Billing"=>$billingarray,"Products"=>$productarray);
}
 echo json_encode($mainarray);
?>
