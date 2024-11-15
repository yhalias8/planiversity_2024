<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Configuration
include '../config.ini.php';
include '../config.ini.curl.php';
include '../class/class.MicrosoftGraph.php';



$clientId = OUTLOOK_CLIENT_ID;
$tenantId = OUTLOOK_CLIENT_TENANT; // Contoh: 'common'
$clientSecret = OUTLOOK_CLIENT_TENANT;
$redirectUri = OUTLOOK_CLIENT_REDIRECT_URL; // Ganti dengan URL redirect Anda
$authCode = $_GET['code'];

// $url = "https://login.microsoftonline.com/".OUTLOOK_CLIENT_TENANT."/oauth2/v2.0/token";
$url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";
$data = [
    'code' => $authCode,
    'client_id' => OUTLOOK_CLIENT_ID,
    'redirect_uri' => OUTLOOK_CLIENT_REDIRECT_URL,
    'client_secret' => OUTLOOK_CLIENT_SECRET,
    'grant_type' => 'authorization_code',
    // 'scope' => 'https://graph.microsoft.com/.default',
    // 'scope' => 'Calendars.ReadWrite offline_access',
    'scope' => 'https://graph.microsoft.com/Calendars.ReadWrite offline_access',
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
curl_close($ch);

// Handle errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    exit;
}

// Decode response
$response_data = json_decode($response, true);


// Get access token and refresh token
$access_token = $response_data['access_token'];
$refresh_token = $response_data['refresh_token'];

// Update user data
if ($access_token) {
    $query = "UPDATE users SET outlooktoken = ?, outlooktoken_refresh = ?, sync_outlookcalendar = 1 WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$access_token, $refresh_token, $userdata['id']]);

    // Get upcoming events
    $queryEvents = "SELECT * FROM events WHERE user_id = :user_id AND DATE(NOW()) <= DATE(event_date_to)";
    $stmtEvents = $dbh->prepare($queryEvents);
    $stmtEvents->bindParam(':user_id', $userdata['id']);
    $stmtEvents->execute();
    $events = $stmtEvents->fetchAll(PDO::FETCH_OBJ);

    // Create events in Google Calendar
    if ($events && $stmtEvents->rowCount() > 0) {
        $microsoftGraph = new MicrosoftGraph();

        // set upcoming events
        foreach ($events as $event) {
            $eventTitle = $event->event_title;
            $dateStart = $event->event_date_from . ' ' . $event->event_time_from;
            $dateEnd = $event->event_date_to . ' ' . $event->event_time_to;
            $eventLocation = $event->event_location;
    
            $data = [
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'subject' => $eventTitle,
                'dateTimeStart' => $dateStart,
                'dateTimeEnd' => $dateEnd,
                'location' => $eventLocation,
            ];
    
            $respons = $microsoftGraph->postData($data);
            $response_data = json_decode($respons);
    
            if ($response_data->id) {
                $query = "UPDATE events SET outlook_event_id = ? WHERE id = ?";
                $stmt = $dbh->prepare($query);
                $stmt->execute([$response_data->id, $event->id]);
            }
        }
    }

    showSuccessMessage();
}


function showSuccessMessage()
{
    echo '<html>
            <head>
                <style>
                    #success-message {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #f0f0f0;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                    }
                </style>
            </head>
            <body>
                <div id="success-message">
                    <h2>Authentication successful!</h2>
                    <p>Please wait for a moment...</p>
                </div>
                <script>
                    setTimeout(function() {
                        window.close();
                    }, 2000); // wait 2 seconds before closing the window
                </script>
            </body>
        </html>';
    
}