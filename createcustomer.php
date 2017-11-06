<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
 require_once 'app/Mage.php';
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";
 Mage::app();
 
    $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
		
$customer = Mage::getModel("customer/customer");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$var_sname = $request->customerName;
$var_bname = $request->toShipName;
$var_email = $request->customerEmail;
$var_workphone=$request->customerWorkPhone;
$var_groupid = $request->customerType;
$var_dob=$request->customerDOB;
$var_company=$request->customerCompany;
$var_gender=$request->customerGender;
$var_billingaddress=$request->customerAddress;
$var_shippingaddress=$request->toShipAddress;
$var_sphone=$request->customerCellPhone;	
$var_bphone=$request->toShipPhone;
$var_discount=$request->discountamount;
try {
$customer   ->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
            ->setLastname('')
			->setGroupId($var_groupid)
			->setDob($var_dob)
			->setGender(
        Mage::getResourceModel('customer/customer')
            ->getAttribute('gender')
            ->getSource()
            ->getOptionId($var_gender)
             )
            ->setEmail($var_email);

			$customer->save();

$address = Mage::getModel("customer/address");
  $address->setCustomerId($customer->getId());
        $address->setFirstname($var_sname);
        $address->setLastname('');
	$address->setCountryId('AE');
        $address->setPostcode('7620000');
        $address->setCity('Dubai');
        $address->setTelephone($var_sphone);
        $address->setFax('');
        $address->setCompany($var_company);
        $address->setStreet($var_billingaddress);
        $address->setIsDefaultBilling('1');
        $address->setIsDefaultShipping(false);
        $address->setSaveInAddressBook('1');

        $address->save();
		
	if($var_shippingaddress != ''){	
	$address = Mage::getModel("customer/address");
        $address->setCustomerId($customer->getId());
        $address->setFirstname($var_bname);
        $address->setLastname('');
        $address->setCountryId('AE');
        $address->setPostcode('7620000');
        $address->setCity('Dubai');
        $address->setTelephone($var_bphone);
        $address->setFax();
        $address->setCompany();
        $address->setStreet($var_shippingaddress);
        $address->setIsDefaultBilling(false);
        $address->setIsDefaultShipping('1');
        $address->setSaveInAddressBook('1');

        $address->save(); 
	}
}catch (Exception $e) {
    echo json_encode(array("Code"=>"500"));
    exit;
}
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$query = "SELECT * FROM personaldiscountDetails";
$result = $conn->query($query);
if(empty($result)) {
                $query = "create table personaldiscountDetails(
					customerid integer primary key,
					discountamount integer
				)";
			$conn->query($query);
}
$cusid=$customer->getId();
$query="Insert into personaldiscountDetails VALUES ('$cusid','$var_discount')";
$result = $conn->query($query);
}
 echo json_encode(array("Code"=>"200"));
