<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];

    $list_id = '9270f15a74';
    $authToken = 'df0c2c1f49f4bc48c25f36d5ccdfb21e-us18';
    // The data to send to the API

    $postData = array(
        "email_address" => $email,
        "status" => "subscribed",
        'tags'  => array('Paid')
    );

    // Setup cURL
    $ch = curl_init('https://us18.api.mailchimp.com/3.0/lists/' . $list_id . '/members/');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Authorization: apikey ' . $authToken,
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));

    $response = curl_exec($ch);
    echo $response;
}
