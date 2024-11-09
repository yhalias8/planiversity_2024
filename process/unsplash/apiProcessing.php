<?php

include_once('list.php');

if (isset($_GET['request_id']) && isset($_GET['type'])  && isset($_GET['keyword'])) {

  $request_id = $_GET['request_id'];
  $trip_style = $_GET['type'];
  $keyword = $_GET['keyword'];

  $url = "";

  //https: //api.unsplash.com/search/photos?query=travel&per_page=10&orientation=portrait&page=1&client_id


  if (isset($_GET['keyword'])) {
    $url .= "?query=" . urlencode($_GET['keyword']);
  }

  $url .= "&per_page=" . $per_page;
  $url .= "&orientation=" . $orientation;
  $url .= "&client_id=" . $ACCESS_TOKEN;

  if (isset($_GET['page'])) {
    $url .= "&page=" . $_GET['page'];
  }

  //$PATH_EXTRA = "air/offers?limit=4" . $url;
  //$API_GET_PATH = $API_PATH . $PATH_EXTRA;

  $API_GET_PATH = $API_PATH . $url;

  $mData = curlRequestGet($API_GET_PATH);
  $rData = json_decode($mData);

  if (!empty($rData->results)) {
    $returnValue = $rData->results;
    $total_pages = $rData->total_pages;
    $after = $_GET['page'] + 1;
    $before = previous_setp_calculate($_GET['page']);
  } else {
    $returnValue = [];
    $total_pages = null;
    $after = null;
    $before = null;
  }

  $responseList = processData($returnValue);

  $output = [
    'responseList' => $responseList,
    'return_values' => $returnValue,
    'request_query' => $keyword,
    'total_pages' => $total_pages,
    'previous_page' => $before,
    'next_page' => $after,
  ];

  $output = array("data" => $output);

  echo json_encode($output);
}
