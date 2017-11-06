<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
umask(0);
Mage::app('default');
 $postdata = file_get_contents("php://input");
$request = json_decode($postdata);
/*
    const STATE_NEW             = 'new';
    const STATE_PENDING_PAYMENT = 'pending_payment';
    const STATE_PROCESSING      = 'processing';
    const STATE_COMPLETE        = 'complete';
    const STATE_CLOSED          = 'closed';
    const STATE_CANCELED        = 'canceled';
    const STATE_HOLDED          = 'holded';
    const STATE_PAYMENT_REVIEW  = 'payment_review';
 */
$orderId = $request->id;
$order_num = Mage::getModel('sales/order')->loadByIncrementID($orderId);
// following 2 lines are used to update order status
// $order->setState(Mage_Sales_Model_Order::STATE_COMPLETE, true);
// $order->save();
 // echo $order->getStatusLabel(); 
 // echo $order->getFirstname(); 
 // echo "<br />Status Updated";
 $order_num->setData('state', 'complete');
 $order_num->setStatus('complete');
 $history = $order_num->addStatusHistoryComment('', false);
 $history->setIsCustomerNotified(false);
 $order_num->save();
 echo 1;
exit;
 // $collection = Mage::getModel('sales/order')->getCollection()
    // ->addAttributeToFilter('increment_id', '100000102');
	// print_r($collection);
	// exit;
    // ->addAttributeToFilter('customer_lastname', $lnm)
    // ->addAttributeToFilter('customer_email', $eml);
 // $order = $collection->getFirstItem();
 // if ($order->getId()) { 
    // echo $order->getStatusLabel();
	// echo $order->getCustomerName();
	// echo $order->getCustomerId();
// }
// else {
    // echo 'invalid';
// }
?>