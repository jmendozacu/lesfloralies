<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
 require_once 'app/Mage.php';
 Mage::app();
 Mage::setIsDeveloperMode(true);
 Mage::app('admin'); 
$initialoptions=array();
$initialoptions[0]="box";
$initialoptions[1]="color";
$initialoptions[2]="size";

 $postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$cat_id = $request->id;
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
$proid=array();
$secondchilds=array();
$mainobj=new stdClass();
$output=array();
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "Select entity_id from catalog_category_entity where parent_id=$cat_id";
$result = $conn->query($sql);
$firstchild=0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $firstchild=$row["entity_id"];
    }
} else {
    echo -1;
	exit;
}
$sql = "Select parent_id from catalog_category_entity where entity_id=$firstchild";
$result = $conn->query($sql);
$firstchild=0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       array_push($secondchilds,$row["parent_id"]);
    }
} else {
    echo -1;
	exit;
}
for($iterator=0;$iterator<count($iterator);$iterator++){

$sql = "SELECT product_id FROM catalog_category_product where category_id=$secondchilds[$iterator]";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // echo "id: " .$row["product_id"]. "<br>";
		array_push($proid,$row["product_id"]);
    }
}
}
// echo count($proid);
for ($x = 0; $x <count($proid); $x++) {
	// echo $proid[$x];
	// echo "<br/>";
	$_product = Mage::getModel('catalog/product')->load($proid[$x]);
    $name= $_product->getName();
	$sku= $_product->getsku();
	$price= $_product->getPrice();
	$quantity=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($proid[$x])->getQty();
	// echo $quantity;
	   $cur_category =  Mage::getModel('catalog/product')->load($proid[$x]);
		 $img_url=$cur_category->getImageUrl() ;
		 
		 if($cur_category->getOptions()==NULL){
			$output[]=  array('ID'=>$proid[$x],
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'Price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>'null'
					 );
		 $mainobj->Products=$output;
											 }
	else{
		$mainarray=new stdClass();
		$minoptions=array();
		$optionschk=0;
		
		 $posts=array();
		 foreach($cur_category->getOptions() as $o){
     $optionType = $o->getType();
	 $optionname=$o->getTitle();
	 $optionname=strtolower($optionname);
	 $out=array();
	if ($optionType == 'drop_down') {
		$optionschk++;
		
         $values = $o->getValues();
		  $title  = strtolower($o->getTitle());
		  
		 	array_push($minoptions,$title);
         foreach ($values as $v) {
            $out[]=[$title=>$v->getTitle(),"Price"=>round($v->getPrice(),0)];
		}
		  $mainarray->$optionname=$out;
		// unset($posts[0]);
		  unset($out);	
		 // unset($pricee);
	}
	}
		 if($optionschk==1)
  {
    $result = array_diff($initialoptions, $minoptions);
	        foreach($result as $res){
			 $mainarray->$res="null";
			} 	 
	$output[] =  array('ID'=>$proid[$x],
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
					  $mainobj->Products=$output;
	$optionchk=0;
}
  else if($optionschk==2)
  {
	   $result = array_diff($initialoptions, $minoptions);
	        foreach($result as $res){
			 $mainarray->$res="null";
			} 	 
	$output[] =  array('ID'=>$proid[$x],
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);	
					  $mainobj->Products=$output;
					  $optionchk=0;
  }
  else{
  $output[] =  array('ID'=>$proid[$x],
                     'Name'=>$name,
					 'Image'=>$img_url,
					 'Sku'=>$sku,
					 'price'=>round($price,0),
					 'Quantity'=>$quantity,
					 'Options'=>$mainarray);
					  $mainobj->Products=$output;
		$optionchk=0;
		}
		}
}
		
echo json_encode($output);
// $conn->close();
?>