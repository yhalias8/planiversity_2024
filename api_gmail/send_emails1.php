<?php
require '../vendor/autoload.php';

include_once("../config.ini.php");
include("common.php");

/*if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}*/

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('planiversity_api');
    $client->setScopes(Google_Service_Gmail::GMAIL_COMPOSE);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

// Get the API client and construct the service object.
$client = getClient();
/*$service = new Google_Service_Gmail($client);*/

// Print the labels in the user's account.
$service = new Google_Service_Gmail($client);
try {
    $strSubject = "Verification Mail";
    $strRawMessage = "From: Me<rammanojpotla1608@gmail.com>\r\n";
    $strRawMessage .= "To: manoj<rammanoj.potla@gmail.com>\r\n";
    $strRawMessage .= "CC: rammanoj<rammanojpotla1608@gmail.com>\r\n";
    $strRawMessage .= "Subject: =?utf-8?B?" . base64_encode($strSubject) .     "?=\r\n";
    $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
    $msg = new Google_Service_Gmail_Message();
    $msg->setRaw($mime);
    $service->users_messages->send("me", $msg);
    echo "mail sent";
} catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
}

?>
/*$list = $service->users_messages->listUsersMessages('me', ['maxResults' => 10000, 'q' => 'category:primary']);
if (count($list->getMessages()) == 0) {
    print "No labels found.\n";
} else {
    foreach ($list->getMessages() as $message) {
        $optParamsGet2['format'] = 'full';
        $single_message = $service->users_messages->get('me', $message->id, ['format' => 'metadata', 'metadataHeaders' => ['To', 'X-Original-To', 'X-Original-From', 'From', 'Reply-To', 'Subject']]);
        $from = $single_message->getPayload()->getHeaders()[0]->value;
        $subject = $single_message->getPayload()->getHeaders()[1]->value;
        $reply_to = $single_message->getPayload()->getHeaders()[2]->value;
        $body = $single_message->getSnippet();
        if ($reply_to != "do-not-reply@tripit.com") {
            $booking_reference = get_string_between($subject, 'Itinerary #', ')');
            $email = get_string_between($from, '<', '>');
            if (!empty($booking_reference)) {
                $strRawMessage = "From: Email <planiversityllc@gmail.com> \r\n";
                $strRawMessage .= "To: <mlkhamzaawan4210@gmail.com>\r\n";
                $strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
                $strRawMessage .= "MIME-Version: 1.0\r\n";
                $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
                $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
                $strRawMessage .= "$body\r\n";
                $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
                $msg = new Google_Service_Gmail_Message();
                $msg->setRaw($mime);
                /*print_r($mime);
                die;*/
                try {
                    $sent = $service->users_messages->send("me", $msg);
                } catch (Exception $exception) {
                    echo $exception->getCode() . "<br/>";
                }
            }
        }
    }
}*/