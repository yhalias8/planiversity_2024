<?php
include 'list.php';




if (isset($_POST['intent_id']) && isset($_POST['check']) && isset($_POST['date'])) {

$intent_id = filter_var($_POST["intent_id"], FILTER_SANITIZE_STRING);

$API_POST_PATH = $API_PATH . "payments/payment_intents/".$intent_id."/actions/confirm";


$params = array();

$fields = json_encode($params);

$mData = curlRequestPost($API_POST_PATH, $TOKEN, $fields);

$cData = json_decode($mData);


if($cData->data->status="succeeded"){

            http_response_code(200);

            $response = array(
                "status" => 200,
                "type" => "payment_confirm",
                "id" => $cData->data->id,
                "message" => "Payment has been made successfully",
            );    

}else{

        http_response_code(422);
        $response = array(
            'status' => 422,
            'message' => "Transaction has been failed",
        );    

}

echo json_encode($response);

}
