<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
try{
$postdata = file_get_contents("php://input");
/*$postdata='
{
  "discount": "5",
  "shipping": "5",
  "taxamount": 0,
  "ShippmentMethod": "checkmo",
  "length": 2,
  "customer": "1",
  "cart": [
    {
      "size": "Large",
      "box": "Round",
      "boxColor": "Red",
      "quantity": 2,
      "total": 40,
      "sizeAddon": "",
      "boxAddon": "",
      "totalAddon": 1,
      "product": {
        "ID": "1111",
        "Name": "Misty Whites with Red Roses",
        "Image": "",
        "Sku": "22",
        "price": 20
      }
    },
    {
      "size": "Large",
      "box": "Round",
      "boxColor": "Red",
      "quantity": "2",
      "total": 164,
      "sizeAddon": "",
      "boxAddon": "",
      "totalAddon": null,
      "product": {
        "ID": "201",
        "Name": "Mint Square Box",
        "Image": "http://34.212.185.219/media/catalog/product/cache/0/image/265x/9df78eab33525d08d6e5fb8d27136e95/l/s/ls1_8409-min_1.jpg",
        "Sku": "23",
        "price": 82
      }
    }
  ],
  "totalAmount": 204,
  "shippmentObject": {
    "id": "",
    "shipName": "",
    "shipPhone": "",
    "shipAddress": "",
    "toshipdate": ""
  }
}
';*/
$data=json_decode($postdata);
$customerid=$data->customer;
$count=$data->length;
$products=array();
$options=array();
$boxarray=array();
$boxColorarray=array();
$sizearray=array();
 for( $i = 0; $i < $count; $i++)
 {
		    $opt=array();
		    $products[$data->cart[$i]->product->ID]=array('qty' => 
	            $data->cart[$i]->quantity);
				
				
				if(isset($data->cart[$i]->boxColor))
				{
			                $opt[]=array(
				   "boxColor"=>$data->cart[$i]->boxColor
				);					
				$boxarray=array(
				   "boxColor"=>$data->cart[$i]->boxColor
				);		
				}
				else
				{
					$boxarray=array(
				   "boxColor"=>null
				);
				}
				if(isset($data->cart[$i]->box))
				{
                $opt[]=array(
				   "box"=>$data->cart[$i]->box,
				   "boxAddon"=>$data->cart[$i]->boxAddon
				);
				$boxColorarray=array(
				"box"=>$data->cart[$i]->box,
				"boxAddon"=>$data->cart[$i]->boxAddon
				);				
				}
				else{
				$boxColorarray=array(
				"box"=>null,
				"boxAddon"=>null
				);		
				}
				if(isset($data->cart[$i]->size))
				{
                $opt[]=array(
				   "size"=>$data->cart[$i]->size,
				   "sizeAddon"=>$data->cart[$i]->sizeAddon
						    );
                $sizearray=array(
							"size"=>$data->cart[$i]->size,
							"sizeAddon"=>$data->cart[$i]->sizeAddon
				);							
				}
				else{
				 $sizearray=array(
							"size"=>null,
							"sizeAddon"=>null
				);	
				}
				}
				$options[]=array("Color"=>$boxarray["boxColor"],"Box"=>$boxColorarray["box"],"BoxAddon"=>$boxColorarray["boxAddon"],"Size"=>$sizearray["size"],"SizeAddon"=>$sizearray["sizeAddon"]);
				
require_once 'app/Mage.php';
Mage::app();
$customer = Mage::getModel('customer/customer')->load($customerid);/*$customerId is the id of the customer who is placing the order, it can be passed as an argument to the function place()*/
$transaction = Mage::getModel('core/resource_transaction');
$storeId = $customer->getStoreId();
$reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($storeId);



$order = Mage::getModel('sales/order')
->setIncrementId($reservedOrderId)
->setStoreId($storeId)
->setQuoteId(0)
->setGlobal_currency_code('AED')
->setBase_currency_code('AED')
->setStore_currency_code('AED')
->setOrder_currency_code('AED');

$orderr = Mage::getModel('sales/order');

  $order->setCustomer_email($customer->getEmail())
  ->setCustomerFirstname($customer->getFirstname())
  ->setCustomerLastname($customer->getLastname())
  ->setCustomerGroupId($customer->getGroupId())
  ->setCustomer_is_guest(0)
  ->setCustomer($customer);


  $billing = $customer->getDefaultBillingAddress();
  $billingAddress = Mage::getModel('sales/order_address')
  ->setStoreId($storeId)
  ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
  ->setCustomerId($customer->getId())
  ->setCustomerAddressId($customer->getDefaultBilling())
  ->setCustomer_address_id($billing->getEntityId())
  ->setPrefix($billing->getPrefix())
  ->setFirstname($billing->getFirstname())
  ->setMiddlename($billing->getMiddlename())
  ->setLastname($billing->getLastname())
  ->setSuffix($billing->getSuffix())
  ->setCompany($billing->getCompany())
  ->setStreet($billing->getStreet())
  ->setCity($billing->getCity())
  ->setCountry_id($billing->getCountryId())
  ->setRegion($billing->getRegion())
  ->setRegion_id($billing->getRegionId())
  ->setPostcode($billing->getPostcode())
  ->setTelephone($billing->getTelephone())
  ->setFax($billing->getFax());
  $order->setBillingAddress($billingAddress);
  $shippingid=$customer['default_shipping'];

  $shipping = $customer->getDefaultShippingAddress();
  $shippingAddress = Mage::getModel('sales/order_address')
  ->setStoreId($storeId)
  ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
  ->setCustomerId($customer->getId())
  ->setFirstname($data->shippmentObject->shipName)
  ->setStreet($data->shippmentObject->shipAddress)
  ->setCity("Dubai")
  ->setCountry_id("United Arab Emirates")
  ->setTelephone($data->shippmentObject->shipPhone);
  $order->setShippingAddress($shippingAddress)
  ->setShipping_method('flatrate_flatrate')
  ->setShippingAmount($data->shipping)
  ->setShippingDescription('flatrate');
  
  $orderPayment = Mage::getModel('sales/order_payment')
  ->setStoreId($storeId)
  ->setCustomerPaymentId(0)
  ->setMethod($data->ShippmentMethod)
  ->setPo_number(' - ');
  $order->setPayment($orderPayment);
  $subTotal = 0;
  $counter=0;
  $lengthcart=count($data->cart);
  for($iterator=0;$iterator<$lengthcart;$iterator++) {
  $orderItem = Mage::getModel('sales/order_item')
  ->setStoreId($storeId)
  ->setQuoteItemId(0)
  ->setProductId($data->cart[$counter]->product->ID)
  ->setTotalQtyOrdered($data->cart[$counter]->quantity)
  ->setQtyOrdered($data->cart[$counter]->quantity)
  ->setName($data->cart[$counter]->product->Name)
  ->setSku($data->cart[$counter]->product->Sku)
  ->setPrice($data->cart[$counter]->product->price)	
  ->setBasePrice($data->cart[$counter]->product->price)
  ->setOriginalPrice($data->cart[$counter]->product->price)
  ->setRowTotal($data->cart[$counter]->total)
  ->setBaseRowTotal($data->cart[$counter]->total);
// ->setProductOptions($options[$counter]);
  // *$data->cart[$counter]->totalAddon
  $subTotal += $rowTotal;
  $order->addItem($orderItem);
  $prid=$data->cart[$counter]->product->ID;
  
  if($prid!=1111){
   $quantityy=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($prid)->getQty();
  if($quantityy<$data->cart[$counter]->quantity)
  {
	  echo -1;
	  exit;
  }
  else{
   $newQty=$quantityy-$data->cart[$counter]->quantity;
   Mage::getModel('cataloginventory/stock_item')
                   ->loadByProduct($prid)
                   ->setQty($newQty)
                   ->save();	  
  }
  }
$counter++;
  }  
  $subTotal=$data->totalAmount;
  $tax=$data->taxamount;
  $discount=$data->discount;
  $taxval=$subTotal/100*$tax;
  $discountval=$subTotal/100*$discount;
  $totalamount=$data->totalAmount;
  $order->setSubtotal($totalamount)
  ->setTaxAmount($taxval)
  ->setTaxPercentage($tax)
  ->setDiscountAmount($discountval)
  ->setDiscountPercentage($discount)
  ->setBaseSubtotal($totalamount)
  ->setGrandTotal($totalamount)
  ->setBaseGrandTotal($totalamount);

  
  
  $transaction->addObject($order);
  
  $transaction->addCommitCallback(array($order, 'place'));
  
  $transaction->addCommitCallback(array($order, 'save'));
  
  $transaction->save(); 
  
  
  $oid=$order['increment_id'];
  $ddate = $data->shippmentObject->toshipdate;
  if($data->ShippmentMethod=="purchaseorder")
  {

	$order_num = Mage::getModel('sales/order')->loadByIncrementID($order['increment_id']);
	$order_num->setData('state', 'new');
    $order_num->setStatus('new');
    $history = $order_num->addStatusHistoryComment('', false);
    $history->setIsCustomerNotified(false);
    $order_num->save();
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$query = "SELECT * FROM DeliveryData";
$result = $conn->query($query);
if(empty($result)){
				  $query = "CREATE TABLE DeliveryData(
                		  orderid integer,
 				  DeliveryDate varchar(50),
				  primary key(orderid))";							 
				  $conn->query($query);
				  }
$query="Insert into DeliveryData(orderid,DeliveryDate) VALUES ('$oid','$ddate')";
$conn->query($query);
echo json_encode(array("OrderID"=>$oid,"Code"=>"200","DeliveryDate"=>$ddate));
exit;

  }
  }
 else if($data->ShippmentMethod=="checkmo")
  {

	$order_num = Mage::getModel('sales/order')->loadByIncrementID($order['increment_id']);
	$order_num->setData('state', 'complete');
    $order_num->setStatus('complete');
    $history = $order_num->addStatusHistoryComment('', false);
    $history->setIsCustomerNotified(false);
    $order_num->save();
echo json_encode(array("OrderID"=>$oid,"Code"=>"200","DeliveryDate"=>$ddate));
exit;

  }
else
{
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$query = "SELECT * FROM DeliveryData";
$result = $conn->query($query);
if(empty($result)){
				  $query = "CREATE TABLE DeliveryData(
                		  orderid integer,
 				  DeliveryDate varchar(50),
				  primary key(orderid))";							 
				  $conn->query($query);
				  }
$query="Insert into DeliveryData(orderid,DeliveryDate) VALUES ('$oid','$ddate')";
$conn->query($query);
echo json_encode(array("OrderID"=>$oid,"Code"=>"200","DeliveryDate"=>$ddate));
exit;
}
}
echo json_encode(array("OrderID"=>$oid,"Code"=>"200","DeliveryDate"=>$ddate));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>