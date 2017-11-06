<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
include 'app/Mage.php';
	$amount=$_GET["amount"];
	$orderID=$_GET["orderid"];
	if($amount!="" && $orderID!=""){
	$secretHash="09315712F7278A0836AD1672B54AB635";
    $accessCode='452B6764';
    $merchantId='845594000';    
    $amount*=100;
    $data = array(
        "vpc_AccessCode" => $accessCode,
        "vpc_Amount" => $amount,
        "vpc_Command" => 'pay',
        "vpc_Locale" => 'en',
        "vpc_MerchTxnRef" =>  "REF_".time(),
        "vpc_Merchant" => $merchantId,
        "vpc_OrderInfo" => "Order_N_".time(),
        "vpc_ReturnURL" => urlencode("http://34.212.185.219/SuccessfullPayment.php?orderID='$orderID'"),
        "vpc_Version" => '1',
        'vpc_SecureHashType' => 'SHA256'    
    );

    ksort($data);
    $hash = null;
    foreach ($data as $k => $v) {
        if (in_array($k, array('vpc_SecureHash', 'vpc_SecureHashType'))) {
            continue;
        }
        if ((strlen($v) > 0) && ((substr($k, 0, 4)=="vpc_") || (substr($k, 0, 5) =="user_"))) {
            $hash .= $k . "=" . $v . "&";
        }
    }
    $hash = rtrim($hash, "&");

    $secureHash = strtoupper(hash_hmac('SHA256', $hash, pack('H*', $secretHash)));
    $paraFinale = array_merge($data, array('vpc_SecureHash' => $secureHash));
    $actionurl = 'https://migs.mastercard.com.au/vpcpay?'.http_build_query($paraFinale);

    //print_r($actionurl);
    header("Location:".$actionurl);
	}
?>