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
    $client->setScopes([Google_Service_Gmail::GMAIL_READONLY, Google_Service_Gmail::GMAIL_COMPOSE, Google_Service_Gmail::GMAIL_MODIFY, Google_Service_Gmail::GMAIL_SEND]);
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

function decodeBody($body) {
    $rawData = $body;
    $sanitizedData = strtr($rawData,'-_', '+/');
    $decodedMessage = base64_decode($sanitizedData);
    if(!$decodedMessage){
        $decodedMessage = FALSE;
    }
    return $decodedMessage;
}

// Get the API client and construct the service object.
$client = getClient();
/*$service = new Google_Service_Gmail($client);*/

// Print the labels in the user's account.
$service = new Google_Service_Gmail($client);

$list = $service->users_messages->listUsersMessages('me', ['maxResults' => 10000, 'q' => 'category:primary']);
if (count($list->getMessages()) == 0) {
    print "No labels found.\n";
} else {
    foreach ($list->getMessages() as $message) {
        $optParamsGet2['format'] = 'full';
        $single_message = $service->users_messages->get('me', $message->id, ['format' => 'full', 'metadataHeaders' => ['To', 'X-Original-To', 'X-Original-From', 'From', 'Reply-To', 'Subject']]);
        $payload = $single_message->getPayload();
        /*echo "<pre>";
        print_r($payload);
        echo "</pre>";
        die;*/
        $subject = $payload->getHeaders()[21]->value;
        $reply_to = $payload->getHeaders()[22]->value;
        $from = $payload->getHeaders()[23]->value;
        $body = $payload->getBody();
        $FOUND_BODY = decodeBody($body['data']);

        // If we didn't find a body, let's look for the parts
        if(!$FOUND_BODY) {
            $parts = $payload->getParts();
            foreach ($parts  as $part) {
                if($part['body']) {
                    $FOUND_BODY = decodeBody($part['body']->data);
                    break;
                }
                // Last try: if we didn't find the body in the first parts,
                // let's loop into the parts of the parts (as @Tholle suggested).
                if($part['parts'] && !$FOUND_BODY) {
                    foreach ($part['parts'] as $p) {
                        // replace 'text/html' by 'text/plain' if you prefer
                        if($p['mimeType'] === 'text/html' && $p['body']) {
                            $FOUND_BODY = decodeBody($p['body']->data);
                            break;
                        }
                    }
                }
                if($FOUND_BODY) {
                    break;
                }
            }
        }
        if ($reply_to != "do-not-reply@tripit.com") {
            $booking_reference = get_string_between($subject, 'Itinerary #', ')');
            $email = get_string_between($from, '<', '>');
            if (!empty($booking_reference)) {
                $strRawMessage = "From: Email <planiversityllc@gmail.com> \r\n";
                $strRawMessage .= "To: <plans@tripit.com>\r\n";
                $strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
                $strRawMessage .= "MIME-Version: 1.0\r\n";
                $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
                $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
                $strRawMessage .= "$FOUND_BODY\r\n";
                $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
                $msg = new Google_Service_Gmail_Message();
                $msg->setRaw($mime);
                try {
                    $sent = $service->users_messages->send("me", $msg);
                } catch (Exception $exception) {
                    echo $exception->getCode() . "<br/>";
                }
            }
        }
    }
}