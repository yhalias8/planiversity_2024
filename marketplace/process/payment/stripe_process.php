<?php
include_once('../list.php');
$PATH = "payment/stripe";

if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip']) && isset($_POST['stripeToken']) && isset($_POST['service_uuid'])) {

    $params = "";

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $stripeToken = $_POST['stripeToken'];
    $service_uuid = $_POST['service_uuid'];
    $uuid = $_POST['uuid'];
    $u_number = $_POST['u_number'];

    $params = [
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'phone_number' => $phone_number,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'stripeToken' => $stripeToken,
        'service_uuid' => $service_uuid,
        'uuid' => $uuid,
        'u_number' => $u_number,
    ];


    $fields = json_encode($params);

    $API_GET_PATH = $URL . $PATH;

    $mData = curlRequestPost($API_GET_PATH, $fields);
    $rData = json_decode($mData);

    // if (!empty($rData->data)) {
    //     $returnValue = $rData;
    // } else {
    //     $returnValue = [];
    // }

    $returnValue = $rData;

    http_response_code($returnValue->status);
    $output = [
        'results' => $returnValue,
    ];
    echo json_encode($output);
} else {

    http_response_code(422);
    $output = array(
        'status' => 422,
        'message' => "All fields is required",
    );
    echo json_encode($output);
}

//$output = array("data" => $output);
