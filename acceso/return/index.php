<?php

require_once '../../libsphp/libs/google/src/Google_Client.php' ;
require_once '../../libsphp/libs/google/src/contrib/Google_Oauth2Service.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
$oauth2 = new Google_Oauth2Service($client);

$client->setRedirectUri('http://localhost/juan/prinet/welcome.html');
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
	unset($_SESSION['audit_first']);
    $_SESSION['token'] = $client->getAccessToken();
  //$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  //header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  //return;
}

if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
    unset($_SESSION['token']);
    $client->revokeToken();
}

if ($client->getAccessToken()) {
    $user = $oauth2->userinfo->get();
    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
    $firstName = $user['given_name'];

    $personMarkup = "$email<br>$firstName<div><img src='$img?sz=50'></div>";
    $_SESSION['token'] = $client->getAccessToken();
	$_SESSION['excell']=$user;
} else {
    $authUrl = $client->createAuthUrl();
	$_SESSION['audit_first']= rand(11111111111, 99999999999);
}

if (isset($personMarkup)) {
    //print $personMarkup;
	header('Content-type: application/json');
	echo json_encode($user);
	return;
}

if (isset($authUrl)) {
	$firstOut=array("status"=>"initiiert","location"=>$authUrl);
    echo(json_encode($firstOut));
}

?>