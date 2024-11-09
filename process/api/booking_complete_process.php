<?php
include '../../config.ini.php';
include 'list.php';


// if (
//     !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
// ) {

if (isset($_POST['travels_type']) && isset($_POST['travels_id']) && isset($_POST['travels_title']) && isset($_POST['travels_gender']) && isset($_POST['travels_dob']) && isset($_POST['travels_name']) && isset($_POST['travels_family_name']) && isset($_POST['travels_email']) && isset($_POST['offersRequest']) && isset($_POST['off_id']) && isset($_POST['payment_intent_id']) && isset($_POST['check']) && isset($_POST['date'])) {


    $travels_type = filter_var($_POST["travels_type"], FILTER_SANITIZE_STRING);
    $travels_id = $_POST["travels_id"];
    $travels_title =$_POST["travels_title"];
    $travels_gender = $_POST["travels_gender"];
    $travels_dob = $_POST["travels_dob"];
    $travels_name = $_POST["travels_name"];
    $travels_family_name = $_POST["travels_family_name"];
    $travels_email = $_POST["travels_email"];
    $countryCode = $_POST["countryCode"];
    $travels_number = $_POST["travels_number"];
    $off_request_id = $_POST["offersRequest"];
    $off_id = filter_var($_POST["off_id"], FILTER_SANITIZE_STRING);
    $payment_intent_id = filter_var($_POST["payment_intent_id"], FILTER_SANITIZE_STRING);


    $url = "air/offers/" . $off_id;

    $API_GET_PATH = $API_PATH . $url;

    $mData = curlRequestGet($API_GET_PATH, $TOKEN);
    $rData = json_decode($mData);



    if (!empty($rData->data->id)) {
        
    $singleNode = $rData->data->slices[0];

   foreach ($travels_id as $i => $value){
    $passengersSlices[] = array(
      "title" => $travels_title[$i],
      "gender" => $travels_gender[$i],
      "family_name" => $travels_family_name[$i],
      "given_name" => $travels_name[$i],
      "born_on" => $travels_dob[$i],
      "email" => $travels_email[$i],
      "phone_number" => $countryCode[$i].$travels_number[$i],
      "id" => $travels_id[$i],
    );

    }

    $offerSlice=[
        $off_id
    ];


    $paymentsSlices[] = (array(
    "type" => "balance",
    "currency" => $rData->data->total_currency,
    "amount" => $rData->data->total_amount
    )
    );

    $metaSlices = (array(
    "payment_intent_id" => $payment_intent_id,
    )
    );

  $params = [
    'selected_offers' => $offerSlice,
    'payments' => $paymentsSlices,
    'passengers' => $passengersSlices,
    'type' => "instant", 
    'metadata' => $metaSlices, 
       
  ];

    $params = array("data" => $params);
    $fields = json_encode($params);


    $API_POST_PATH = $API_PATH . "air/orders";

    $oData = curlRequestPost($API_POST_PATH, $TOKEN, $fields);
    $dData = json_decode($oData);


    // echo "<pre>";
    // print_r($dData);

    //check whether the charge is successful
    if (!empty($dData->data->booking_reference)) {

        
        $date = date("Y-m-d H:i:s");
        $status = "succeeded";


        $query = "INSERT INTO flight_booking (offer_request_id,offer_id,payment_intent_id,booking_reference,first_name, last_name, email, amount , date_paid, ip_address,origin_address,origin_lat,origin_long,destination_address,destination_lat,destination_long,trip_mode,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?, ? , ? , ?)";

            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $off_request_id, PDO::PARAM_STR);
            $stmt->bindValue(2, $off_id, PDO::PARAM_STR);
            $stmt->bindValue(3, $payment_intent_id, PDO::PARAM_STR);
            $stmt->bindValue(4, $dData->data->booking_reference, PDO::PARAM_STR);
            $stmt->bindValue(5, $travels_name[0], PDO::PARAM_STR);
            $stmt->bindValue(6, $travels_family_name[0], PDO::PARAM_STR);
            $stmt->bindValue(7, $travels_email[0], PDO::PARAM_STR);
            $stmt->bindValue(8, $dData->data->total_amount, PDO::PARAM_STR);
            $stmt->bindValue(9, $date, PDO::PARAM_STR);
            $stmt->bindValue(10, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->bindValue(11, $singleNode->origin->name, PDO::PARAM_STR);
            $stmt->bindValue(12, $singleNode->origin->latitude, PDO::PARAM_STR);
            $stmt->bindValue(13, $singleNode->origin->longitude, PDO::PARAM_STR);
            $stmt->bindValue(14, $singleNode->destination->name, PDO::PARAM_STR);
            $stmt->bindValue(15, $singleNode->destination->latitude, PDO::PARAM_STR);
            $stmt->bindValue(16, $singleNode->destination->longitude, PDO::PARAM_STR);
            $stmt->bindValue(17, tripModeCalculate(count($rData->data->slices)), PDO::PARAM_STR);
            $stmt->bindValue(18, $status, PDO::PARAM_STR);
            $stmt->execute();


            http_response_code(200);

            $response = array(
                "status" => 200,
                "type" => "order",
                "id" => $dData->data->booking_reference,
                "message" => "Order has been made successfully",
            );




    }else{

            //echo "<pre>";
            //print_r($dData);

            http_response_code(422);
            $response = array(
                'status' => 422,
                'message' => "Order has beed failed",
                "errors" =>$dData->errors
            );


    }

}else{

            http_response_code(422);
            $response = array(
                'status' => 422,
                'message' => "Session has expired",
                "errors" =>$dData->errors
            );


}

        
    echo json_encode($response);
}
