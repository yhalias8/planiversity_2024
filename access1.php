<?php 
include_once('../config.ini.php');

include_once('tripit.php');

$api_url = 'https://api.tripit.com/oauth/request_token';
$api_url_access = 'https://api.tripit.com/oauth/access_token';
$oauth_consumer_key = "cc3d1f65dfb016ccaa6c1aabad5e30cc57608709";
$oauth_consumer_secret = "2242bc9170c02a3335a18a9efb776239d43968e1";


$oauth_credential = new OAuthConsumerCredential($oauth_consumer_key, $oauth_consumer_secret);

 
$tripit = new TripIt($oauth_credential, $api_url);


//print serialize($tripit->get_request_token()) . "\n";
//echo '<pre>';
//print_r($tripit->get_request_token());
//die;
$outhArr=$tripit->get_request_token();

$_SESSION['outh-step1']=$outhArr;

$oauth_token=$outhArr['oauth_token'];
$oauth_token_secret=$outhArr['oauth_token_secret'];
//echo  $request_token_secret;
//$oauth_credential1 = new OAuthConsumerCredential($oauth_consumer_key, $oauth_consumer_secret, $request_token, $request_token_secret);

//$tripit1 = new TripIt($oauth_credential1, $api_url_access);

//$final_accesstoken=$tripit1->get_access_token();
//echo '<pre><br>';
//print_r($final_accesstoken);
//die;
//print serialize($tripit->get_access_token()) . "\n";
$url='https://www.tripit.com/oauth/authorize?oauth_token='.$oauth_token.'&oauth_callback=https://www.planiversity.com/staging/tripitapi/access_token.php';
header("Location:" .$url);
?>