<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
 require_once 'app/Mage.php';
 Mage::app();
$collection = Mage::getModel('customer/customer')->getCollection()
    ->addAttributeToSelect('firstname')
   ->addAttributeToSelect('entity_id')
   ->addAttributeToSelect('dob')
   ->addAttributeToSelect('gender')
   ->addAttributeToSelect('email');
$output=array();
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
foreach ($collection as $item)
{
	$id=$item["entity_id"];
		$customer=Mage::getModel('customer/customer')->load($id);
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$query = "SELECT * FROM personaldiscountDetails where customerid='$id'";
$result = $conn->query($query);
$disval=0;
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$disval=$row["discountamount"];
}
}
}

		$customerAddress = array();
		if(empty($customer->getAddresses())==1){
			
		if($item["gender"]==1){
		$dob=$item["dob"];
		 $real=explode(" ",$dob);
	$output[]=array("Id"=>$item["entity_id"],
	                "Name"=>$item["firstname"],
			"Discount"=>$disval,
	                "Email"=>$item["email"],
					"Gender"=>'Male',
					"dob"=>$real[0],
					"Company"=>$customerAddress[0]['company'],
					"BillingName"=>$billingaddress['firstname'],
                	"BillingAddress"=>$billingaddress['street'],
					"Billingphone"=>$billingaddress['telephone'],
					"ShippingName"=>$shippingaddress['firstname'],
                	"ShippingAddress"=>$shippingaddress['street'],
					"Shippingphone"=>$shippingaddress['telephone'],
					"Company"=>$customerAddress[0]['company']
					);
	}else
	{
		$dob=$item["dob"];
    	 $real=explode(" ",$dob);
	$output[]=array("Id"=>$item["entity_id"],
	                "Name"=>$item["firstname"],
			"Discount"=>$disval,
	                "Email"=>$item["email"],
					"Gender"=>'Female',
					"dob"=>$real[0],
					"BillingName"=>$billingaddress['firstname'],
                	"BillingAddress"=>$billingaddress['street'],
					"Billingphone"=>$billingaddress['telephone'],
					"ShippingName"=>$shippingaddress['firstname'],
                	"ShippingAddress"=>$shippingaddress['street'],
					"Shippingphone"=>$shippingaddress['telephone'],
					"Company"=>$customerAddress[0]['company']
					);		
	}
}
else{
	foreach ($customer->getAddresses() as $address)
	{
		$customerAddress[] = $address->toArray();
	}
	foreach($customerAddress as $addrs){
	if($item["gender"]==1){
		$dob=$item["dob"];
		 $real=explode(" ",$dob);
	$output[]=array("Id"=>$item["entity_id"],
	                "Name"=>$item["firstname"],
			"Discount"=>$disval,
	                "Email"=>$item["email"],
					"Gender"=>'Male',
					"dob"=>$real[0],
					"BillingName"=>$customerAddress[0]['firstname'],
                	"BillingAddress"=>$customerAddress[0]['street'],
					"Billingphone"=>$customerAddress[0]['telephone'],
					"ShippingName"=>$customerAddress[1]['firstname'],
                	"ShippingAddress"=>$customerAddress[1]['street'],
					"Shippingphone"=>$customerAddress[1]['telephone'],
					"Company"=>$customerAddress[0]['company']
					);
	}else
	{
		$dob=$item["dob"];
    	 $real=explode(" ",$dob);
	$output[]=array("Id"=>$item["entity_id"],
	                "Name"=>$item["firstname"],
	                "Discount"=>$disval,
			"Email"=>$item["email"],
					"Gender"=>'Female',
					"dob"=>$real[0],
						"BillingName"=>$customerAddress[0]['firstname'],
                	"BillingAddress"=>$customerAddress[0]['street'],
					"Billingphone"=>$customerAddress[0]['telephone'],
					"ShippingName"=>$customerAddress[1]['firstname'],
                	"ShippingAddress"=>$customerAddress[1]['street'],
					"Shippingphone"=>$customerAddress[1]['telephone'],
					"Company"=>$customerAddress[0]['company']
					);		
	}
	break;
}
}
}
echo json_encode($output);
?>