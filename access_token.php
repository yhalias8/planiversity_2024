<?php 
include_once('../config.ini.php');
//print_r($_GET);

$_SESSION["outh_session"] = $_GET;

//print_r($_SESSION["outh_session"]);
//print_r($_SESSION['outh-step1']);
//die;
include_once('tripit.php');

//$api_url = 'https://api.tripit.com/oauth/request_token';
$api_url_access = 'https://api.tripit.com/oauth/access_token';
$oauth_consumer_key = "cc3d1f65dfb016ccaa6c1aabad5e30cc57608709";
$oauth_consumer_secret = "2242bc9170c02a3335a18a9efb776239d43968e1";


//$oauth_credential = new OAuthConsumerCredential($oauth_consumer_key, $oauth_consumer_secret);

 
//$tripit = new TripIt($oauth_credential, $api_url);


//print serialize($tripit->get_request_token()) . "\n";
//echo '<pre>';
//print_r($tripit->get_request_token());
//die;
//$outhArr=$tripit->get_request_token();
$request_token=$_SESSION["outh_session"]['oauth_token'];
$request_token_secret=$_SESSION["outh-step1"]['oauth_token_secret'];
//echo  $request_token_secret; die;
$oauth_credential1 = new OAuthConsumerCredential($oauth_consumer_key, $oauth_consumer_secret, $request_token, $request_token_secret);

$tripit1 = new TripIt($oauth_credential1, $api_url_access);

$final_accesstoken=$tripit1->get_access_token();
//echo '<pre><br>';
$_SESSION['final_accesstoken']=$final_accesstoken;
//print_r($_SESSION['final_accesstoken']);




//die;


?>