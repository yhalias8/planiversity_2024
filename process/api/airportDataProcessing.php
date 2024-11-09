<?php

$SITE = "https://api.duffel.com/places/suggestions";
$PATH = "?query=";
$QUERY = urlencode($_POST['query']);
$URL = $SITE . $PATH . $QUERY;
$TOKEN = "duffel_test_ZgNwOQ6vA1oMHPy7r1UY5j5VI4xItZuwRKxOcpcsjMj";

$mData = curlRequest($URL, $TOKEN);

echo $mData;

function curlRequest($url, $TOKEN)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);

    $headers = array(
        "Content-type: application/json",
        "Authorization: Bearer $TOKEN",
        "Duffel-Version: beta"
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    return $response;
    curl_close($ch);
}
