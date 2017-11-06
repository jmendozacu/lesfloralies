<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
umask(0);
 $app = Mage::app('default');
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$username = $request->username;
$var_pass=  $request->password;
// ->addFieldToFilter('username',$username)->getData()
$user_data = Mage::getModel('admin/user')->getCollection();
$successchk=false;
foreach($user_data as $data)
{
	$magentoname=$data['username'];
	if($magentoname==$username)
	{
	    $magentopasswod=$data['password'];
		$passwordhash=explode(":",$magentopasswod);
	    $passhash=md5($passwordhash[1].$var_pass);
     if($passhash == $passwordhash[0])
     {
		 $successchk=true;
		 break;
     }
	}
}
if($successchk==true)
{
	$output=Array("Result","1");
echo json_encode($username);
}
else
{
$output=Array("Result","-1");
echo json_encode("-1");
}
//By Username get admin User's Role Details
// $username = 'admin';
// $role_data = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('username',$username)->getFirstItem()->getRole()->getData();
// var_dump($role_data);

//By ID get admin User's Details
// $id = 1;
// $user_data = Mage::getModel('admin/user')->load($id)->getData();
// var_dump($user_data);


//By ID get admin User's Role Details
// $id = 1;
// $role_data = Mage::getModel('admin/user')->load($id)->getRole()->getData();
// var_dump($role_data);


// Administration Person Details Code
// $userArray = Mage::getSingleton('admin/session')->getData();
// echo $userArray;
// $user = Mage::getSingleton('admin/session'); 
// $userId = $user->getUser()->getUserId();
// $userEmail = $user->getUser()->getEmail();
// $userFirstname = $user->getUser()->getFirstname();
// $userLastname = $user->getUser()->getLastname();
// $userUsername = $user->getUser()->getUsername();
// $userPassword = $user->getUser()->getPassword();
//By Username get admin User's Details
?>

