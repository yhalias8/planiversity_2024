<?php
// https://api.tripit.com

include_once('../config.ini.php');

$outh_token=$_SESSION['final_accesstoken']['oauth_token'];
$oauth_token_secret=$_SESSION['final_accesstoken']['oauth_token_secret'];
 
//$oauth_credential = new OAuthConsumerCredential("cc3d1f65dfb016ccaa6c1aabad5e30cc57608709", "2242bc9170c02a3335a18a9efb776239d43968e1");
//$token = (new TripIt($oauth_credential))->get_request_token();

$oauth_credential = new OAuthConsumerCredential("cc3d1f65dfb016ccaa6c1aabad5e30cc57608709", "2242bc9170c02a3335a18a9efb776239d43968e1", $outh_token, $oauth_token_secret);
// $oauth_credential = new OAuthConsumerCredential("d8079ff67dcb12ec8c222306432f8bfe1abbd1d1", "3213135bdcc8394f2c7d83849ce36893a1b6ee94", "776d4839ad1c47f5a2f20b3dd2e817c0", "166da0763ed947fab062e7d0f16d337a");