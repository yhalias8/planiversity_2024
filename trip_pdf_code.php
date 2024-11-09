<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/pdf/' . $_POST['idtrip'];
    header("Location:" . SITE . "login");
}

include("class/class.Plan.php");
$plan = new Plan();
if (!$plan->check_plan($userdata['id'])) { //header("Location:".SITE."billing/".$_POST['idtrip']);
    //exit();     
}
//   exit;
include("class/class.TripPlan.php");
$trip = new TripPlan();
$trip->get_data($_POST["idtrip"]);
$id_trip = filter_var($_POST["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip)){
    header("Location:" . SITE . "trip/how-are-you-traveling");
}

if ($trip->itinerary_type == "event") {
    $type = "trip_pdf_code_curl_tc_event.php";
} else {
    $type = "trip_pdf_code_curl_tc_trip.php";
}

echo "OK";

$url = "https://" . $_SERVER['HTTP_HOST'] . "/" . $type . "?idtrip=" . $_POST['idtrip'] . "&uid=" . $userdata['id'];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

