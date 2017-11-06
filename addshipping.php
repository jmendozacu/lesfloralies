<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';
Mage::app();
Mage::app('admin'); 
$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$var_id= $request->id;
$var_bname = $request->toShipName;
$var_shippingaddress=$request->toShipAddress;
$var_bphone=$request->toShipPhone;


$customer = Mage::getModel('customer/customer')->load($var_id);
$shippingid=$customer['default_shipping'];

$address2 = Mage::getModel("customer/address");
			$address2->setCustomerId($customer->getId());
			$address2->setFirstname($var_bname);
			$address2->setTelephone($var_bphone);
			$address2->setStreet($var_shippingaddress);
			$address2->setIsDefaultBilling(false);
			$address2->setIsDefaultShipping('1');
			$address2->setSaveInAddressBook('1');
			$address2->save(); 				
			//$shippingid=$customer['default_shipping'];
			//$address = $customer->getAddressById($shippingid); 
			//$address->setFirstname($var_bname);
		//	$address->setTelephone($var_bphone);
		//	$address->setStreet($var_shippingaddress);
		//	$address->setIsDefaultBilling(false);
		//	$address->setIsDefaultShipping('1');
		//	$address->setSaveInAddressBook('1');
		//	$address->save();	

echo 1;
?>
