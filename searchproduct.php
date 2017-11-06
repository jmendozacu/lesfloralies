<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
Mage::app();
$output=array();
$initialoptions=array();
$initialoptions[0]="box";
$initialoptions[1]="color";
$initialoptions[2]="size";
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$searchstring=$request->name;  

     // Code to Search Product by $searchstring and get Product IDs
     $product_collection = Mage::getResourceModel('catalog/product_collection')
                  ->addAttributeToSelect('*')
                  ->addAttributeToFilter('name', array('like' => '%'.$searchstring.'%'))
                  ->load();

     foreach ($product_collection as $category) {
         $id = $category->getId();
		 $name = $category->getName();
		 $sku=$category->getSku();
		 $price=round($category->getPrice());
		 $quantity=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($id)->getQty();
		 $cur_category =  Mage::getModel('catalog/product')->load($id);
		 $img_url=$cur_category->getImageUrl() ;	
		 if($cur_category->getOptions()==NULL){
		 $output[] =  array('ID'=>$id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>$price,
					 'Quantity'=>$quantity,
					 'Options'=>'null'
					 );
     }
	 else
	 {
		 	$minoptions=array();
		$optionschk=0;
		$mainarray=new stdClass();
		 $out= array();
		 $posts=array();
   foreach($cur_category->getOptions() as $o){
     $optionType = $o->getType();
	 $optionname=$o->getTitle();
	 $optionname=strtolower($optionname);
	 
	if ($optionType == 'drop_down') {
		$optionschk++;
		
         $values = $o->getValues();
		 $title  = strtolower($o->getTitle());
		array_push($minoptions,$title);
         foreach ($values as $v) {

		 $out[]=[$title=>$v->getTitle(),"Price"=>$v->getPrice()];
		 
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
	$output[] =  array('ID'=>$id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>$price,
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
	}
  else if($optionschk==2)
  {
	   $result = array_diff($initialoptions, $minoptions);
	        foreach($result as $res){
			 $mainarray->$res="null";
			} 	 
	$output[] =  array('ID'=>$id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>$price,
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
  }
  else{
  $output[] =  array('ID'=>$id,
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>$price,
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);
					 
		}
	}
	 }
echo json_encode($output);
?>