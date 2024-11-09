<?php
$API_URL = "https://planiversity.azurewebsites.net/api/notifications/send";
$TOKEN = "tanEgGQb3UQLrRLWvuL7";

function curlRequestPost($url, $token, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);

    $headers = array(
        "Content-type: application/json",
        "API-KEY: $token",
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function notificationBodyProcess($mode, $type, $title)
{

    $notificationBody = "";
    switch ($mode) {
        case "add":
            $notificationBody = "has a new $type added. Check it out!";
            break;
        case "update":
            $notificationBody = "$type has been updated. Take a look!";
            break;
        case "checkin":
            $notificationBody = "$type has been check in. Have a look!";
            break;
        case "status_update":
            $notificationBody = "has updated status. Check it out!";
            break;
        default:
            $notificationBody = "$type has been deleted.";
            break;
    }

    return "Your '$title' Trip $notificationBody";
}
