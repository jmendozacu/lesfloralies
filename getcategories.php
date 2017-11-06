<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
error_reporting(E_ALL | E_STRICT);
$mageFilename = 'app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
Mage::app('admin'); 
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "magento";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT entity_id FROM `catalog_category_entity` WHERE parent_id=2";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		 $cur_category =  Mage::getModel('catalog/category')->load($row["entity_id"]);
		 $img_url=$cur_category->getImageUrl() ;
		$output[] =  array('ID'=>$cur_category->getId(),
                     'Name'=>$cur_category->getName(),
					 'Image'=>$img_url);
	}
	echo json_encode($output);
}
else
{
	echo -1;
}





/*  $categories = Mage::getModel('catalog/category')->getCollection()
->addAttributeToSelect('id')
->addAttributeToSelect('name')
->addAttributeToSelect('url_key')
->addAttributeToSelect('url')
->addAttributeToSelect('is_active');

$output = array();
foreach ($categories as $category)
{
    if ($category->getIsActive()) { // Only pull Active categories
        $entity_id = $category->getId();
        $name = $category->getName();
        $url_key = $category->getUrlKey();
        $url_path = $category->getUrl();
         $cur_category =  Mage::getModel('catalog/category')->load($entity_id);
		 $img_url=$cur_category->getImageUrl() ;
		$output[] =  array('ID'=>$entity_id,
                     'Name'=>$name,
					 'Image'=>$img_url);

		 // echo Mage::getBaseUrl('media').'catalog/category/'.$cur_category->getImage();
		  // echo $cur_category->getImageUrl() ;
		 // echo $url_key ;
		  // echo $img_url ;
	}
}
// $arrlength=count($items);
// for($x=0;$x<$arrlength;$x++)
  // {
  // echo $items[$x];
  // echo "<br>";
  // }
   echo json_encode($output);
*/   
?>