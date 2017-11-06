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
$customer = Mage::getModel('customer/customer')->load($var_id);
$addressarray=array();
$addresses=$customer->getAddresses();
foreach($addresses as $address){
	// echo $address['firstname'];
	// echo $address['street'];
	// echo $address['telephone'];
	$addressarray[]=array("ID"=>$var_id,
						"Name"=>$address['firstname'],
	                    "Address"=>$address['street'],
						"Phone"=>$address['telephone']
	 );
	 
}
echo json_encode($addressarray);
?>