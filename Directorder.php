<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
  $postdata = file_get_contents("php://input");

 /*$postdata='{
  "DeliveryDate":"09-07-2034",
  "length": "2",
  "discount":"3",
  "shipping":"2",
  "customer": "39",
  "taxamount":"5",
  "ShippmentMethod":"checkmo",
  "cart": [
    {
		"tatalAddon":1.7,
		"size":"Size(1-10 stems)",
		"sizeAddon":"0.59611",
        "boxColor":"silver",
        "boxColorAddon":"0.658",
      "quantity": "3",
      "total": 1600,
      "product": {
        "ID": "4",
        "Name": "Snowwhite",
        "Image": "http://182.180.56.81:8080/Magento/media/catalog/product/cache/0/image/265x/9df78eab33525d08d6e5fb8d27136e95/h/a/hat_-_orchids.jpg",
        "Sku": "120",
        "price": 1600
      }
    },
    {
		"tatalAddon":3.7,
      "quantity": "2",
	  "size":"Size(1-10 stems)",
	  "sizeAddon":"0.59611",
      "total": 1300,
	  "box":"Square",
	  "boxAddon":"0.6555",
      "product": {
        "ID": "2",
        "Name": "BlackSquare",
        "Image": "http://182.180.56.81:8080/Magento/media/catalog/product/cache/0/image/265x/9df78eab33525d08d6e5fb8d27136e95/s/q/square_-_black.jpg",
        "Sku": "150",
        "price": 1300
      }
    }
  ],
  "shippmentObject":
  {
    "shipName":"ok",
    "shipPhone":"4124124",
    "shipAddress":"sama@gmail.com"
  }
}';*/
 $data=json_decode($postdata);
 // print_r($data);
 // exit;
 // echo "<pre/>";print_r($data);
 
 
// print_r(json_decode($postdata));
// exit;
// print_r($postdata);
// exit;

// echo $json->customer;
// echo $data->shippmentObject->shipName;
$customerid=$data->customer;
// echo "<br/>";
$count=$data->length;
// echo $count;
// echo "<br/>";

$products=array();

$options=array();

$boxarray=array();
$boxColorarray=array();
$sizearray=array();

 for( $i = 0; $i < $count; $i++)
 {
				$opt=array();
				// echo $data->cart[$i]->product->Name;
				$products[$data->cart[$i]->product->ID]=array('qty' => 
	            $data->cart[$i]->quantity);
				
				
				if(isset($data->cart[$i]->boxColor))
				{
					// echo "<br/>";
					// echo "existttttttt";	
					// echo "<br/>";				
					
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
					// echo "<br/>";
					// echo "existttttttt";	
					// echo "<br/>";				

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
					// echo "<br/>";
					// echo "existttttttt";	
					// echo "<br/>";				

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
				$options[]=array("Color"=>$boxarray["boxColor"],"Box"=>$boxColorarray["box"],"BoxAddon"=>$boxColorarray["boxAddon"],"Size"=>$sizearray["size"],"SizeAddon"=>$sizearray["sizeAddon"]);
				// echo "<pre/>";
				// print_r($boxarray);
								// print_r($boxColorarray);
												// print_r($sizearray);
				// echo "Options Started";
				// $options[]=json_encode(array($data->cart[$i]->product->ID=>$opt));
				}
				// echo "<pre/>";
				// echo json_encode($options);
					// exit;
require_once 'app/Mage.php';
Mage::app();

$customer = Mage::getModel('customer/customer')->load($customerid);/*$customerId is the id of the customer who is placing the order, it can be passed as an argument to the function place()*/
$transaction = Mage::getModel('core/resource_transaction');
$storeId = $customer->getStoreId();
$reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($storeId);

if($data->shippmentObject->shipName!='' && $data->shippmentObject->shipPhone!='' && $data->shippmentObject->shipAddress!='')
{
	
	 $address2 = Mage::getModel("customer/address");
			 $address2->setCustomerId($customer->getId());
			 $address2->setFirstname($data->shippmentObject->shipName);
			 $address2->setTelephone($data->shippmentObject->shipPhone);
			 $address2->setStreet($data->shippmentObject->shipAddress);
			 $address2->setCountryId('AE');
        		 $address2->setPostcode('7620000');
        		 $address2->setCity('Dubai');

	        $address2->setIsDefaultBilling(false);
		$address2->setIsDefaultShipping('1');
		$address2->setSaveInAddressBook('1');
		 $address2->save(); 
}

$order = Mage::getModel('sales/order')
->setIncrementId($reservedOrderId)
->setStoreId($storeId)
->setQuoteId(0)
->setGlobal_currency_code('AED')
->setBase_currency_code('AED')
->setStore_currency_code('AED')
->setOrder_currency_code('AED');

$orderr = Mage::getModel('sales/order');

  // set Customer data
  $order->setCustomer_email($customer->getEmail())
  ->setCustomerFirstname($customer->getFirstname())
  ->setCustomerLastname($customer->getLastname())
  ->setCustomerGroupId($customer->getGroupId())
  ->setCustomer_is_guest(0)
  ->setCustomer($customer);

  // set Billing Address

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
  // ->setCustomerAddressId($customer->getDefaultShipping())
  // ->setCustomer_address_id($shipping->getEntityId())
  // ->setPrefix($shipping->getPrefix())
  ->setFirstname($data->shippmentObject->shipName)
  // ->setMiddlename($shipping->getMiddlename())
  // ->setLastname($shipping->getLastname())
  // ->setSuffix($shipping->getSuffix())
  // ->setCompany($shipping->getCompany())
  ->setStreet($data->shippmentObject->shipAddress)
  ->setCity("Dubai")
  ->setCountry_id("United Arab Emirates")
  // ->setRegion($shipping->getRegion())
  // ->setRegion_id($shipping->getRegionId())
  // ->setPostcode($shipping->getPostcode())
  ->setTelephone($data->shippmentObject->shipPhone);
  // ->setFax($shipping->getFax());
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
  foreach ($products as $productId=>$product) {
	 // echo $options[$counter];
	  // exit;
  $_product = Mage::getModel('catalog/product')->load($productId);
   // print_r($_product->getOptions());
  // print_r($_product);
  
	$helper = Mage::helper('catalog/product_configuration');

    // $options['additional_options'] = $helper->getCustomOptions($_product);
	 // print_r($options['additional_options']);
	// exit;
	// echo "Product ID ".$productId;
	// echo "</br>";
  $rowTotal = $_product->getPrice() * $product['qty'];
  
  $orderItem = Mage::getModel('sales/order_item')
  ->setStoreId($storeId)
  ->setQuoteItemId(0)
  ->setQuoteParentItemId(NULL)
  ->setProductId($productId)
  // ->setOptionId(2)
  // ->setOptionTitle('Red')
  // ->setOptionPrice(0.55)
  ->setProductType($_product->getTypeId())
  ->setQtyBackordered(NULL)
  ->setTotalQtyOrdered($product['qty'])
  ->setQtyOrdered($product['qty'])
  ->setName($_product->getName())
  ->setSku($_product->getSku())
  ->setPrice($_product->getPrice())
  ->setBasePrice($_product->getPrice())
  ->setOriginalPrice($_product->getPrice())
  ->setRowTotal($rowTotal)
  ->setBaseRowTotal($rowTotal)
  ->setProductOptions($options[$counter]);
  $subTotal += $rowTotal+($data->cart[$counter]->quantity*$data->cart[$counter]->totalAddon);
  $order->addItem($orderItem);
  $counter++;
  $quantityy=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty();
  if($quantityy<$product['qty'])
  {
	  echo -1;
	  exit;
  }
  else{
   $newQty=$quantityy-$product['qty'];
   Mage::getModel('cataloginventory/stock_item')
                   ->loadByProduct($productId)
                   ->setQty($newQty)
                   ->save();	  
  }
  }	
  $tax=$data->taxamount;
  $discount=$data->discount;
  $taxval=$subTotal/100*$tax;
  $discountval=$subTotal/100*$discount;
  $totalamount=($subTotal+$taxval)-$discountval+$data->shipping;
  $order->setSubtotal($totalamount)
  ->setTaxAmount($taxval)
  ->setTaxPercentage($tax)
  ->setDiscountAmount($discountval)
  ->setDiscountPercentage($discount)
  ->setBaseSubtotal($totalamount)
  ->setGrandTotal($totalamount)
  ->setBaseGrandTotal($totalamount);

  
  // echo $subTotal;
  
  $transaction->addObject($order);
  
  $transaction->addCommitCallback(array($order, 'place'));
  
  $transaction->addCommitCallback(array($order, 'save'));
  
  $transaction->save(); 
  
  // echo $order['increment_id'];
  
  $oid=$order['increment_id'];
  $ddate = $data->shippmentObject->toshipdate;
 if($data->ShippmentMethod=="checkmo")
  {

	$order_num = Mage::getModel('sales/order')->loadByIncrementID($order['increment_id']);
	$order_num->setData('state', 'complete');
    $order_num->setStatus('complete');
    $history = $order_num->addStatusHistoryComment('', false);
    $history->setIsCustomerNotified(false);
    $order_num->save();
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
}
}
echo json_encode(array("OrderID"=>$oid,"Code"=>"200","DeliveryDate"=>$ddate));
?>