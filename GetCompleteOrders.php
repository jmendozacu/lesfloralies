<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';
Mage::app();

$orders = Mage::getModel('sales/order')->getCollection()
    ->addFieldToFilter(status,"complete")
    ->addAttributeToSort('increment_id',"DSC");
    // ->addAttributeToSelect('customer_email')
    // ->addAttributeToSelect('status');
// print_r($orders);
$mainarray=array();
foreach ($orders as $order) {
	$billingarray=array();
	$shippingarray=array();
	$customerarray=array();
	// echo "\n";
	
	// echo $order["increment_id"];
	// echo $order["customer_firstname"];
	// echo $order["customer_email"];
	// echo $order["grand_total"];
	// echo $order["shipping_address_id"];
	// echo $order["billing_address_id"];
	

	
	// echo "<br/>";
	// echo "<br/>";
$orderdetail = Mage::getModel('sales/order')->loadByIncrementId($order["increment_id"]);
$customerarray=array("OrderID"=>$order["increment_id"],
	"Date"=>$orderdetail['created_at'],
	"Name"=>$order["customer_firstname"],
	"Tax"=>$orderdetail['tax_amount'],
	                     "Email"=>$order["customer_email"],
						 "Total"=>$order["grand_total"],
                                                     "Status"=>"Completed");

$shippingaddress=$orderdetail->getShippingAddress();
// echo $shippingaddress['firstname'];
// echo $shippingaddress['street'];
// echo $shippingaddress['telephone'];

$shippingarray=array("Name"=>$shippingaddress['firstname'],
                     "Address"=>$shippingaddress['street'],
					 "Phone"=>$shippingaddress['telephone']);
$billingaddress=$orderdetail->getBillingAddress();
// echo $billingaddress['firstname'];
// echo $billingaddress['street'];
// echo $billingaddress['telephone'];
$billingarray=array("Name"=>$billingaddress['firstname'],
                     "Address"=>$billingaddress['street'],
					 "Phone"=>$billingaddress['telephone']);
// print_r($orderdetail->getShippingAddress());
// print_r($orderdetail->getBillingAddress());
    
$order = Mage::getModel('sales/order')->loadByIncrementId($order["increment_id"]);

$items = $order->getAllVisibleItems();
 $productarray=array();
   foreach($items as $i){
	
	   // print_r($i);
      // echo $i['customer_id'];
	  // echo $i['product_id'];
	  // echo "<br/>";
	  // echo $i['name'];
	  // echo "<br/>";
      // echo $i['sku'];
	  // echo "<br/>";
	  // echo $i['price'];
	  // echo "<br/>";
	  // echo $i['product_options'];
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
  
// }
}
 echo json_encode($mainarray);
?>
