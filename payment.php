<?php
require_once 'app/Mage.php';
Mage::app();
$customer = Mage::getModel('customer/customer')->load(80);
$billingAddress = $customer->getPrimaryBillingAddress();
// print_R($billingAddress);
$orderIds = implode(',', Mage::getSingleton('core/session')->getOrderIds(true));
echo $orderIds;
$order = Mage::getModel('sales/order')->loadByIncrementId(100000050);


$server = $order->getPayment()->getMethodInstance();
echo $server->getMigsVpcServerUrl();
$form = new Varien_Data_Form();
$form->setAction($server->getMigsVpcServerUrl())
    ->setId('migsvpc_server_checkout')
    ->setName('migsvpc_server_checkout')
    ->setMethod('POST')
    ->setUseContainer(true);
foreach ($server->getFormFields() as $field=>$value) {
    $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
}
echo $form;
$html = '<html><body>';
$html.= ('You will be redirected to MIGS in a few seconds.');
$html.= $form->toHtml();
$html.= '<script type="text/javascript">document.getElementById("migsvpc_server_checkout").submit();</script>';
$html.= '</body></html>';
$html = str_replace('<div><input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'" /></div>','',$html);
echo $html;
?>