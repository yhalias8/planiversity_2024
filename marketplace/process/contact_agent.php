<?php
include_once("../../config.ini.php");
include_once('list.php');
$PATH = "inquiry/agent";


if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['country']) && isset($_POST['state'])) {

    $parms = "";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $country_code = $_POST['country_code'];
    $message = $_POST['message'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $mobile_number = null;

    if (!empty($mobile)) {
        $mobile_number = $country_code . $mobile;
    }
    
    $user_id = null;
    if ($auth->isLogged()) {
        $user_id = $userdata['id'];
    }    

    $params = [
        'name' => $name,
        'email' => $email,
        'mobile' => $mobile_number,
        'message' => $message,
        'country' => $country,
        'state' => $state,
        'user_id' => $user_id,
    ];

    $fields = json_encode($params);

    $API_GET_PATH = $URL . $PATH;

    $mData = curlRequestPost($API_GET_PATH, $fields);
    $rData = json_decode($mData);


    if (!empty($rData)) {
        $returnValue = $rData;
    } else {
        $returnValue = [];
    }

    $output = [
        'response' => $returnValue,
    ];

    $output = array("data" => $output);

    echo json_encode($output);
} else {
    http_response_code(422);
    $output = array(
        'status' => 422,
        'message' => "All fields is required",
    );
    echo json_encode($output);
}
