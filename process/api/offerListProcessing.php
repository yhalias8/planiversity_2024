<?php

include_once('list.php');

if (isset($_GET['request_id']) && isset($_GET['trip_mode'])  && isset($_GET['steps']) && isset($_GET['sorts'])) {

  $request_id = $_GET['request_id'];
  $trip_style = $_GET['trip_mode'];
  $steps = $_GET['steps'];
  $sorts = $_GET['sorts'];

  //&sort=total_amount
  $url = "";
  $url .= "&offer_request_id=" . $request_id;


  if (isset($_GET['type']) && isset($_GET['typeValue'])) {
    $url .= "&" . $_GET['type'] . "=" . $_GET['typeValue'];
  }

  $url .= "&sort=" . $sorts;
  $url .= "&max_connections=" . $steps;

  $PATH_EXTRA = "air/offers?limit=4" . $url;
  $API_GET_PATH = $API_PATH . $PATH_EXTRA;


  $mData = curlRequestGet($API_GET_PATH, $TOKEN);
  $rData = json_decode($mData);

  // echo "<pre/>";
  // print_r($rData);

  if (!empty($rData->data)) {
    $offersValue = $rData->data;
    $request_id = $request_id;
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

  //echo $API_GET_PATH;

  //echo "<pre>";
  //print_r($_GET);
}
