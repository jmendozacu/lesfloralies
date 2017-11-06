<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
error_reporting(E_ALL | E_STRICT);
$mageFilename = 'app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
Mage::app('admin'); 
$categories = Mage::getModel('catalog/product')->getCollection()
->addAttributeToSelect('id')
->addAttributeToSelect('name')
 ->addAttributeToSelect('sku')
 ->addAttributeToSelect('price');

$items = array();
$output=array();
$initialoptions=array();
$initialoptions[0]="box";
$initialoptions[1]="color";
$initialoptions[2]="size";
$glob=0;
foreach ($categories as $category)
{
        $entity_id = $category->getId();
        $name = $category->getName();
		$sku=$category->getSku();
		$price=$category->getPrice();
		$quantity=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($entity_id)->getQty();
        $url_key = $category->getUrlKey();
        $url_path = $category->getUrl();
         $cur_category =  Mage::getModel('catalog/product')->load($entity_id);
		 			 $img_url=$cur_category->getImageUrl() ;				 
		
		 
		 $optionschk=false;
	if($cur_category->getOptions()==NULL){
		 $output[] =  array('ID'=>$entity_id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>'null'
					 );
		
	}
	else{
		$minoptions=array();
		$optionschk=0;
		$mainarray=new stdClass();
		 $out= array();
		 $posts=array();
   foreach($cur_category->getOptions() as $o){
     $optionType = $o->getType();
	 // echo $o->getId();
	 // echo $o->getStoreId();
	 $optionname=$o->getTitle();
	 $optionname=strtolower($optionname);
	 
	if ($optionType == 'drop_down') {
		$optionschk++;
		
         $values = $o->getValues();
		 $title  = strtolower($o->getTitle());
		array_push($minoptions,$title);
         foreach ($values as $v) {

		 $out[]=[$title=>$v->getTitle(),"Price"=>round($v->getPrice(),0)];
		 
		}		 
        $mainarray->$optionname=$out;
		unset($out);	
	}
}
  // echo $optionschk;
  if($optionschk==1)
  {
    $result = array_diff($initialoptions, $minoptions);
	        foreach($result as $res){
			 $mainarray->$res="null";
			} 	 
	$output[] =  array('ID'=>$entity_id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
	}
  else if($optionschk==2)
  {
	   $result = array_diff($initialoptions, $minoptions);
	        foreach($result as $res){
			 $mainarray->$res="null";
			} 	 
	$output[] =  array('ID'=>$entity_id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
  }
  else{
  $output[] =  array('ID'=>$entity_id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);
					 
		}
	}
 }
  echo  json_encode($output);
  
?>