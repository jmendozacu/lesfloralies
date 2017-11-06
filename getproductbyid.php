<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
Mage::app();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$cat_id  = $request->categoryid;

$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = $category->getProductCollection()->addAttributeToSort('position');
 
$items = array();
foreach ($collection as $product) {
	  $proid= $product->getId();
	  $product_sku=$product->getsku();
	  $product_obj = Mage::getModel('catalog/product')->loadByAttribute('sku',$product_sku);
	 $stock_obj = Mage::getModel('cataloginventory/stock_item')->load($proid);
	 $product_sku = $product_obj->getSku();
     $product_name = $product_obj->getName();
     $product_qty = $stock_obj->getQty();
	 $output =  array('Name'=>$product_name,
                 'Quantity'=>$product_qty,
				  'Sku'=>$product_sku);
				  $items[] =  $output['Name'];
				  $items[] =  $output['Quantity'];
				  $items[] =  $output['Sku'];		  
	}
	  echo json_encode($items);
?>
