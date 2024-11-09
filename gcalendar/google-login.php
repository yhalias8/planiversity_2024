<?php
session_start();

require_once('google-calendar-api.php');
require_once('settings.php');

// Google passes a parameter 'code' in the Redirect Url
if (isset($_GET['code'])) {
	try {
		$capi = new GoogleCalendarApi();

		// Get the access token 
		$data = $capi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

		// Save the access token as a session variable
		//$_SESSION['access_token'] = $data['access_token'];

		// edit data base
		include_once("../config.ini.php");
		if (!$auth->isLogged()) {
			$_SESSION['redirect'] = 'welcome';
			echo "<script>window.top.location.href = \"http://planiversity.com/login\";</script>";
			//header("Location:".SITE."login");
			exit();
		}
		$query = "UPDATE `users` SET `sync_googlecalendar` = '1' , `google_access_token` = '" . $data['access_token'] . "' WHERE `users`.`id` = " . $userdata['id'] . ";";
		$stmtnew = $dbh->prepare($query);
		$tmp = $stmtnew->execute();

		/*if ($tmp === false) {
        $err = $stmtnew->errorInfo();
        if ($err[0] === '00000' || $err[0] === '01000') {
            echo  'true';
        }
    }
    print_r($err);
	echo '----- '.$data['access_token'];*/

		echo '<p>In now on your trips will be saved in your google calendar</p>';
		exit();
	} catch (Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
