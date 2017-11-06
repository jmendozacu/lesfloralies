<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
require_once 'app/Mage.php';

Mage::app();
Mage::app('admin'); 
$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$var_id= $request->id;
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
$var_discount=$request->personalDiscount;
$servername = "localhost";
$username = "phpmyadmin";
$password = "abcd1234";
$dbname = "phpmyadmin";

$addressid=0;
#load customer object
$customer = Mage::getModel('customer/customer')->load($var_id); //insert cust ID
#create customer address array
$customerAddress = array();
#loop to create the array

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
$query="update personaldiscountDetails set discountamount='$var_discount' where customerid='$var_id'";
$conn->query($query);
}
$billingid=$customer['default_billing'];
$shippingid=$customer['default_shipping'];
if($customer['default_shipping']==NULL)
{
	
	        $customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
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
			$billingid=$customer['default_billing'];
			$address2 = $customer->getAddressById($billingid); 
			$address2->setFirstname($var_sname);
			$address2->setTelephone($var_sphone);
			$address2->setCompany($var_company);
			$address2->setStreet($var_billingaddress);
			$address2->setCountryId('AE');
        		$address2->setPostcode('7620000');
        		$address2->setCity('Dubai');

			$address2->setIsDefaultBilling(false);
			$address2->setIsDefaultShipping('1');
			$address2->setSaveInAddressBook('1');
			$address2->save();
			$address2 = Mage::getModel("customer/address");
			$address2->setCustomerId($customer->getId());
			$address2->setFirstname($var_bname);
			$address2->setTelephone($var_bphone);
			$address2->setStreet($var_shippingaddress);
			$address2->setCountryId('AE');
        		$address2->setPostcode('7620000');
        		$address2->setCity('Dubai');
			$address2->setIsDefaultBilling(false);
			$address2->setIsDefaultShipping('1');
			$address2->setSaveInAddressBook('1');
			$address2->save(); 			

}
else if($customer['default_shipping']!=NULL)
{
	// echo "Updateting Shipping Address......";
	        	$customer   ->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
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
	$billingid=$customer['default_billing'];
			$address2 = $customer->getAddressById($billingid); 
			$address2->setFirstname($var_sname);
			$address2->setTelephone($var_sphone);
			$address2->setCompany($var_company);
			$address2->setStreet($var_billingaddress);
			$address2->setIsDefaultBilling(false);
			$address2->setIsDefaultShipping('1');
			$address2->setSaveInAddressBook('1');
			$address2->save();
	$shippingid=$customer['default_shipping'];
$address = $customer->getAddressById($shippingid); 
			$address->setFirstname($var_bname);
			$address->setTelephone($var_bphone);
			$address->setStreet($var_shippingaddress);
			$address->setIsDefaultBilling(false);
			$address->setIsDefaultShipping('1');
			$address->setSaveInAddressBook('1');
			$address->save();	
}

echo 1;
exit;
// $shippingAddress =  Mage::getModel('customer/address')
                    // ->setData($addressArray)
                    // ->setCustomerId($customerId)
                    // ->setSaveInAddressBook('1');

// echo $billingid.'  &&& '.$shippingid;
// foreach ($customer->getAddresses() as $address)
// {
   // $customerAddress[] = $address->toArray();
   // echo $customerAddress[0]['entity_id'];
   // echo $customerAddress[1]['entity_id'];
   // echo $addressId = $address->getId();
   // exit;
   // print_r($customerAddress);
	// $addressid=$customerAddress[0]['entity_id'];
	// break;
// }
// echo $shippingid;

if($billingid!=$shippingid)
{
	// print_r($customer);
	
	echo " Different ".$shippingid;
	exit;
			$customer   ->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
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
		

			$address = $customer->getAddressById($billingid); 
			$address->setFirstname($var_bname);
			$address->setTelephone($var_bphone);
			$address->setStreet($var_shippingaddress);
			$address->setIsDefaultBilling(false);
			$address->setIsDefaultShipping('1');
			$address->setSaveInAddressBook('1');
			$address->save();

			if(is_int($shippingid))
			{
					echo "Shipping is Already Added";
		$address2 = $customer->getAddressById($shippingid); 
		$address2->setFirstname($var_sname);
        $address2->setTelephone($var_sphone);
        $address2->setCompany($var_company);
        $address2->setStreet($var_billingaddress);
		$address->setIsDefaultBilling(false);
        $address->setIsDefaultShipping('1');
        $address->setSaveInAddressBook('1');
		$address2->save();
					}
			else{		
						echo "Going To Add Shipping"; 			
			$address2 = Mage::getModel("customer/address");
			$address2->setCustomerId($customer->getId());
			$address2->setFirstname($var_bname);
			$address2->setTelephone($var_bphone);
			$address2->setStreet($var_shippingaddress);
			$address2->setIsDefaultBilling(false);
			$address2->setIsDefaultShipping('1');
			$address2->setSaveInAddressBook('1');
			$address2->save(); 			

			}

		// $addr	ess->setIsDefaultBilling(true);
	// if ($address->getDefaultBilling()) {
	// $customer->setData('default_billing', '');
	// }
	// $customer->addAddress($billingAddress);

// $address2 = $customer->getAddressById($shippingid); 
		// $address2->setFirstname($var_sname);
        // $address2->setTelephone($var_sphone);
        // $address2->setCompany($var_company);
        // $address2->setStreet($var_billingaddress);
		// $address->setIsDefaultBilling(false);
        // $address->setIsDefaultShipping('1');
        // $address->setSaveInAddressBook('1');
		// $address2->save();
		
}
else if($billingid==$shippingid)
{
	echo "Same".$billingid;
	exit;
	$customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
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

		
		echo $billingid;
		$address = $customer->getAddressById($billingid);
		// $address = Mage::getModel("customer/address");
		// $address->setCustomerId($customer->getId());
		$address->setFirstname($var_sname);
        $address->setTelephone($var_sphone);
        $address->setCompany($var_company);
        $address->setStreet($var_billingaddress);
		$address2->setIsDefaultBilling('1');
        $address2->setIsDefaultShipping(false);
        $address2->setSaveInAddressBook('1');	
		$address->save();

		// $address2 = Mage::getModel("customer/address");
		$address2 = Mage::getModel("customer/address");
		$address2->setCustomerId($customer->getId());
        // $address2->setCustomerId($customer->getId());	
		$address2->setFirstname($var_bname);
        $address2->setTelephone($var_bphone);
        $address2->setStreet($var_shippingaddress);	
		$address2->setIsDefaultBilling(false);
        $address2->setIsDefaultShipping('1');
        $address2->setSaveInAddressBook('1');		
        $address2->save(); 	
}
else{
		echo "Dont't Have Any";
		exit;
	$customer   ->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($var_sname)
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
		$address->setCustomerId($var_id);
        $address->setFirstname($var_sname);
        $address->setTelephone($var_sphone);
        $address->setCompany($var_company);
        $address->setStreet($var_billingaddress);
        $address->setIsDefaultBilling('1');
        $address->setIsDefaultShipping(false);
        $address->setSaveInAddressBook('1');

        $address->save();
		
		
		$address = Mage::getModel("customer/address");
		$address->setCustomerId($var_id);
        $address->setFirstname($var_bname);
        $address->setTelephone($var_bphone);
        $address->setStreet($var_shippingaddress);
        $address->setIsDefaultBilling(false);
        $address->setIsDefaultShipping('1');
        $address->setSaveInAddressBook('1');
        $address->save(); 
}

?>