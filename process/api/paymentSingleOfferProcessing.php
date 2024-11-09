<?php

include_once('list.php');

if (isset($_GET['offer_id'])) {

  $request_id = $_GET['offer_id'];

  $url = "air/offers/" . $request_id;

  $API_GET_PATH = $API_PATH . $url;


  $mData = curlRequestGet($API_GET_PATH, $TOKEN);
  $rData = json_decode($mData);


    if (!empty($rData->data->id)) {

    $total_amount = price_calculate($rData->data->total_amount);

    $params = [
    'currency' => $rData->data->total_currency,
    'amount' => price_calculate_wf($rData->data->total_amount),
    ];

    $params = array("data" => $params);

    $fields = json_encode($params);

    $API_POST_PATH = $API_PATH . "payments/payment_intents";  
    $pData = curlRequestPost($API_POST_PATH, $TOKEN, $fields);

    $sData = json_decode($pData);

    if (!empty($sData->data->id)) {

      $response = [
        "status" => 200,
        'offer_id' => $rData->data->id,
        'payment_id' => $sData->data->id,
        'total_amount' => $sData->data->amount,
        'client_token' => $sData->data->client_token,
      ];

      $response = array("data" => $response);

      http_response_code(200);
      
    } else {

      http_response_code(422);

      $response = array(
        'status' => 422,
        'message' => "Higher amount limit alert",
      );
      
    }

    
  }else{    

    http_response_code(422);
   
    $response = array(
     'status' => 422,
      'message' => "Session has expired",
    );

  }

  echo json_encode($response);


}
