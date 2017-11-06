<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
Mage::app();


// $addressdata = $billingaddress->getData();

// echo $shippingaddress['street'];
// echo $shippingaddress['telephone'];
// echo $billingaddress['street'];
// echo $addressdata['telephone'];

$flag=false;
$output=new stdClass();
$mainarray=array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query = $request->email;
// $model = Mage::getSingleton('customer/customer');
$result = Mage::getSingleton('customer/customer')->getCollection()
        ->addAttributeToSelect('*')
        ->addAttributeToFilter('email', array('like' => "%$query%"));
		$items = array();
		foreach($result as $r) 
		{		
         print_r($r);		
		$id=$r['entity_id']	;
		$flag=true;
		// echo $id;
        // $customer = $model->load($r['entity_id']);
		 $customer = Mage::getModel('customer/customer')->load($r['entity_id']);
		// $customerGroup = Mage::getModel('customer/group')->load($customer->getCustomerGroupId())->getCode();
		// $costomerdata = Mage::getModel('customer/customer')->load($id);
		// $shippingaddress = Mage::getModel('customer/address')->load($id->default_shipping);
		// $billingaddress = Mage::getModel('customer/address')->load($id->default_billing);
		// $addressdata = $billingaddress->getData();
		// echo $addressdata['telephone'];
		// echo $addressdata['street'];
		// echo $billingaddress['street'];
		// echo $shippingaddress['street'];
		// print_r($shippingaddress);
		// echo "<br/>";
		// echo "<br/>";
		// echo "<br/>";
		$customerAddress = array();
		if(empty($customer->getAddresses())==1){
//  IMP	echo "<pre/>";print_r($result);
		if($customer->getGender()==1)
		{
		$mainarray[]=array(
		'ID'=>$customer->getId(),
		'Name'=>$customer->getName(),
		'BillingName'=>$customerAddress[0]['firstname'],
		'ShippingName'=>$customerAddress[1]['firstname'],
        'Email'=>$customer->getEmail(),
		'Gender'=>"Male",
		'Dob'=>$customer->getDob(),
		'GroupId'=>$customer->getGroupId(),
	    'BillingPhone'=>$customerAddress[0]['telephone'],
	    'ShippingPhone'=>$customerAddress[1]['telephone'],
		'BillingAddress'=>$billingaddress['street'],
	    'ShippingAddress'=>$shippingaddress['street']
		);
//		$output->customer=$mainarray;
		}
	else
	{
	        $mainarray[]=array(
			'ID'=>$customer->getId(),
			'Name'=>$customer->getName(),
			'BillingName'=>$customerAddress[0]['firstname'],
			'ShippingName'=>$customerAddress[1]['firstname'],
              'Email'=>$customer->getEmail(),
			  'Gender'=>"Female",
			  'Dob'=>$customer->getDob(),
			  'GroupId'=>$customer->getGroupId(),
	              'BillingPhone'=>$customerAddress[0]['telephone'],
	              'ShippingPhone'=>$customerAddress[1]['telephone'],
				  'BillingAddress'=>$shippingaddress['street'],
	              'ShippingAddress'=>$customerAddress[1]['street'],
				  );
//				  	$output->customer=$mainarray;
	}
}
else{
	foreach ($customer->getAddresses() as $address)
	{
		$customerAddress[] = $address->toArray();
	}

$customerchk=1;
foreach($customerAddress as $addrs){	
	if($customer->getGender()==1)
	{
			$mainarray[]=array(
			'ID'=>$customer->getId(),
			'Name'=>$customer->getName(),
			'BillingName'=>$customerAddress[0]['firstname'],
			'ShippingName'=>$customerAddress[1]['firstname'],
              'Email'=>$customer->getEmail(),
			  'Gender'=>"Female",
			  'Dob'=>$customer->getDob(),
			  'GroupId'=>$customer->getGroupId(),
	              'BillingPhone'=>$customerAddress[0]['telephone'],
	              'ShippingPhone'=>$customerAddress[1]['telephone'],
				  'BillingAddress'=>$customerAddress[0]['street'],
	              'ShippingAddress'=>$customerAddress[1]['street'],
				  );
//		$output->customer=$mainarray;
	}			
else{	

$mainarray[]=array('ID'=>$customer->getId(),
			'Name'=>$customer->getName(),
			'BillingName'=>$customerAddress[0]['firstname'],
			'ShippingName'=>$customerAddress[1]['firstname'],
              'Email'=>$customer->getEmail(),
			  'Gender'=>"Male",
			  'Dob'=>$customer->getDob(),
			  'GroupId'=>$customer->getGroupId(),
	              'BillingPhone'=>$customerAddress[0]['telephone'],
	              'ShippingPhone'=>$customerAddress[1]['telephone'],
				  'BillingAddress'=>$customerAddress[0]['street'],
	              'ShippingAddress'=>$customerAddress[1]['street'],
				  );
//	$output->customer=$mainarray;
				  }		
}

}
		}
if($flag==true)
{
	echo json_encode($mainarray);
	}
else
{
echo -1;
}
?>