<?php
$SECURE_SECRET = "09315712F7278A0836AD1672B54AB635";  
        $accessCode = '452B6764';
        $merchantId = '845594000';
        if($migs_testmode ==1) {
            $SECURE_SECRET = "09315712F7278A0836AD1672B54AB635";
            $accessCode = '452B6764';
            $merchantId = '845594000';
        }
     $amount =10.00;
    $unique_id = rand(999999,8988888888);//this is a sample random no
        $postdata = array(
                "vpc_AccessCode" => $accessCode,
                "vpc_Amount" => ($amount*100),
                "vpc_Command" => 'pay',
                "vpc_Locale" => 'en',
                "vpc_MerchTxnRef" => $unique_id,
                "vpc_Merchant" => $merchantId,
                "vpc_OrderInfo" => 'this is a product',
                "vpc_ReturnURL" => "https://mywebsite.com/success.php",
                "vpc_Version" => '1');


        $vpcURL = 'https://migs.mastercard.com.au/vpcpay?';
        $md5Hash = $SECURE_SECRET;
        $appendAmp = 0;


        foreach ($wpay_postdata as $key => $value) {

            if (strlen($value) > 0) {

                if ($appendAmp == 0) {
                    $vpcURL .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                } else {
                    $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                }
                $md5Hash .= $value;
            }
        }

        if (strlen($SECURE_SECRET) > 0) {
            $vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($md5Hash));
        }
        header("Location: " . $vpcURL)
?>