<?php
phpinfo();
/**
 * Example of products list retrieve using Customer account via Magento REST API. OAuth authorization is used
 */
$callbackUrl = "https://www.lesfloralies.ae/oauth_customer.php";
$temporaryCredentialsRequestUrl = "https://www.lesfloralies.ae/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
$adminAuthorizationUrl = 'https://www.lesfloralies.ae/oauth/authorize';
$accessTokenRequestUrl = 'https://www.lesfloralies.ae/oauth/token';
$apiUrl = 'https://www.lesfloralies.ae/api/rest';
$consumerKey = '663e20f82e72c15a0d2f2326da49d7ca';
$consumerSecret = '58d30b4d80bec195346c7148250bcf7a';
echo"<pre>";
session_start();
if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) && $_SESSION['state'] == 1) {
    $_SESSION['state'] = 0;
}
try {
    $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
    $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
    $oauthClient->enableDebug();
echo"come here";

    if (!isset($_GET['oauth_token']) && !$_SESSION['state'])
	{
		echo"come here";
        $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
        $_SESSION['secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['state'] = 1;
        header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
        exit;
    }
	else if ($_SESSION['state'] == 1)
	{
		echo"come here 2";
        $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
        $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
        $_SESSION['state'] = 2;
        $_SESSION['token'] = $accessToken['oauth_token'];
        $_SESSION['secret'] = $accessToken['oauth_token_secret'];
        header('Location: ' . $callbackUrl);
        exit;
    }
	else
{
	echo"come here 3";
        $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
        $resourceUrl = "$apiUrl/products";
       $oauthClient->fetch($resourceUrl, array(), 'GET', array('Content-Type' => 'application/json', 'Accept' => '*/*'));
      $productsList = json_decode($oauthClient->getLastResponse());
        print_r($productsList);
    }
	
} catch (OAuthException $e) {
	echo"come in error";
    print_r($e);
}
?>