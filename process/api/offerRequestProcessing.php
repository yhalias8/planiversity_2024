<?php

include_once('list.php');

if (isset($_POST['trip_style']) && isset($_POST['adults']) && isset($_POST['infants']) && isset($_POST['departure_flight_date']) && isset($_POST['childs']) && isset($_POST['sorts'])) {

  $API_POST_PATH = $API_PATH . "air/offer_requests?return_offers=false";

  $trip_style = $_POST['trip_style'];
  $cabin_class = $_POST['class'];
  $steps = $_POST['steps'];
  $sorts = $_POST['sorts'];

  $adults = $_POST['adults'];
  $childs = $_POST['childs'];
  if (isset($_POST['children'])) {
    $children_list = $_POST['children'];
  }
  $infants = $_POST['infants'];

  if (isset($_POST['infant'])) {
    $infant_list = $_POST['infant'];
  }

  $departureDate = $_POST['departure_flight_date'];
  $returnDate = $_POST['return_flight_date'];

  for ($x = 0; $x < $adults; $x++) {
    $passengers[] = array(
      "type" => "adult"
    );
  }

  for ($x = 0; $x < $childs; $x++) {
    $passengers[] = array(
      "age" => $children_list[$x]
    );
  }

  for ($x = 0; $x < $infants; $x++) {
    $passengers[] = array(
      "age" => $infant_list[$x]
    );
  }

  $parsedSlices[] = (array(
    "origin" => $_POST['from_location_code'],
    "destination" => $_POST['to_location_code'],
    "departure_date" => $departureDate
  )
  );


  if ($trip_style == "round") {

    $parsedSlices[] = (array(
      "origin" => $_POST['to_location_code'],
      "destination" => $_POST['from_location_code'],
      "departure_date" => $returnDate
    )
    );
  }


  $params = [
    'cabin_class' => $cabin_class,
    'passengers' => $passengers,
    'slices' => $parsedSlices,
    'max_connections' => $steps,
  ];

  $params = array("data" => $params);

  $fields = json_encode($params);

  // echo "<pre>";
  // print_r($fields);

  $mData = curlRequestPost($API_POST_PATH, $TOKEN, $fields);

  //echo "<pre>";
  $oData = json_decode($mData);

  // echo "<pre>";
  //print_r($oData->data->id);

  $PATH_EXTRA = "air/offers?limit=10&offer_request_id=" . $oData->data->id . "&max_connections=" . $steps . "&sort=" . $sorts;
  $API_GET_PATH = $API_PATH . $PATH_EXTRA;
  // echo "<br/>";

  $mData = curlRequestGet($API_GET_PATH, $TOKEN);

  $rData = json_decode($mData);

  // echo "<pre>";
  // print_r($rData);


  // echo "<br/>";

  if (!empty($rData->data)) {
    $offersValue = $rData->data;
    $request_id = $oData->data->id;
    $after = $rData->meta->after;
    $before = $rData->meta->before;
  } else {
    $offersValue = [];
    $request_id = null;
    $after = null;
    $before = null;
  }


  if ($trip_style == "one") {
    $offerList = offerProcessOneWay($offersValue);
  } else {
    $offerList = offerProcessRound($offersValue);
  }

  //echo $oData->data->id;

  $output = [
    'offerList' => $offerList,
    'results' => count($offersValue),
    'offers' => $offersValue,
    'offer_request_id' => $request_id,
    'previous_page' => $before,
    'next_page' => $after,
  ];

  $output = array("data" => $output);

  echo json_encode($output);

  //echo $mData;
}
