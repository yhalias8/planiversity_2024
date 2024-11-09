<?php
//use setasign\Fpdi\Tcpdf\Fpdi;
session_start();
include_once("../config.ini.php");
include("../class/class.TripPlan.php");



if (!$auth->isLogged()) {
	$_SESSION['redirect'] = 'trip/name/' . $_GET['idtrip'];
	header("Location:" . SITE . "login");
}

$idtrip = $_GET['idtrip'];

$mapBoxKey  = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";
$key        = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';

$trip = new TripPlan();
//$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
//$userdata['id'];

$idtrip = filter_var($idtrip, FILTER_SANITIZE_STRING);
// if (empty($idtrip)) {
//  header("Location:" . SITE . "trip/how-are-you-traveling");
// }

$trip->get_data($idtrip);

echo '<pre>';
print_r($trip);

echo "Start";

die();