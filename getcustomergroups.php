<?php 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
error_reporting(E_ALL | E_STRICT);
$mageFilename = 'app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('admin'); 
$output=array();
$customer = Mage::getModel('customer/group')->getCollection();
$items = array();
foreach($customer as $type) {
    // echo '<pre>Customer group id :'.$type->getCustomerGroupId().'<br>';
    // echo '<pre>Customer group code :'.$type->getCustomerGroupCode().'<br>';
	
	$output[] =  array('ID'=>$type->getCustomerGroupId(),
                        'Name'=>$type->getCustomerGroupCode());
				  // $items[] =  $output['ID'];
				  // $items[] =  $output['Name'];
							}
  echo json_encode($output);
?>