<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$var_fname = $request->fname;
$var_lname = $request->lname;
$var_email = $request->email;
$var_groupid = $request->groupid;
$client = new SoapClient('http://127.0.0.1:8080/Magento/api/soap/?wsdl');
$session = $client->login('soap', 'Magentoideo');
$result = $client->call($session,'customer.create',
array(array('email' => $var_email,
 'firstname' => $var_fname,
 'lastname' => $var_lname,
 'website_id' => 0,
 'store_id' => 0,
 'group_id' => $var_groupid)));
// If you don't need the session anymore
//$client->endSession($session);
?>