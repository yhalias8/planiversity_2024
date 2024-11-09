<?php

include_once("../config.ini.php");
include("../class/class.Plan.php");
include("../class/class.TripPlan.php");
$plan = new Plan();


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
$trip = new TripPlan();
$trip->get_data($id_trip);



//echo $trip->itinerary_type;

$GLOBALS["id_trip"] = $id_trip;
$GLOBALS["uid"] = $userdata['id'];

if ($trip->itinerary_type == "event") {
    $type = "trip_pdf_code_curl_tc_event.php";
} else {
    //$type = "trip_pdf_trip.php";
    include_once("trip_pdf_trip.php");
}



// $url = "https://" . $_SERVER['HTTP_HOST'] . "/master/stag/preview/" . $type . "?idtrip=" . $id_trip  . "&uid=" . $userdata['id'];

// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// //for debug only!
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// $resp = curl_exec($curl);
// curl_close($curl);

// header('Content-type: application/pdf');
// //http_response_code(200);


// if ($resp) {
//     echo $resp;
// }

//printf($resp);
