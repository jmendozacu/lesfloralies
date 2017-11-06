<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email=$request->email;
$oid=$request->orderId;
$message=$request->message;
 	 $to = $email;
         $subject = "Lesfloralies Order Details";
         
       //  $message = "<b>Your Order is Placed Successfully.</b>";
      //  $message .= "<h1>OrderID -- $oid </h1>";
         
         $header = "From:sales@lesfloralies.ae\r\n";
         $header .= "Cc:talhamalik883@gmail.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo 1;
         }else {
            echo -1;
         }
?>