<?php
include_once(__DIR__ . "/../config.app.php");
include_once(__DIR__ . "/../config.ini.php");
include_once(__DIR__ . "/../config.ini.curl.php");

class MicrosoftGraph
{
    private $access_token;
    private $refresh_token;
    private $client_id;
    private $client_secret;
    private $user_id;

    public function __construct()
    {
        global $userdata;
        $this->user_id = $userdata['id'];
        $this->access_token = $userdata['outlooktoken'];
        $this->refresh_token = $userdata['outlooktoken_refresh'];
        $this->client_id = OUTLOOK_CLIENT_ID;
        $this->client_secret = OUTLOOK_CLIENT_SECRET;
    }

    private function getTimeZone()
    {
        $ip = file_get_contents('https://api.ipify.org/');
        $url = 'http://ip-api.com/json/' . $ip;
        $response = json_decode(file_get_contents($url), true);
        $timezone = $response['timezone'];
        return $timezone;
    }

    private function formattedDate($date)
    {
        $dateObject = new DateTime($date, new DateTimeZone($this->getTimeZone()));
        $formattedDate = $dateObject->format('Y-m-d\TH:i:sP'); // ISO 8601 format;
        return $formattedDate;
    }

    private function getUserId()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        $data = json_decode($response, true);
        // print_r($data['id']);
        // return $data;

        if ($http_code == 401) {

            // UNAUTHENTICATED error, token is invalid or expired
            $new_token = $this->refreshToken();
            if ($new_token) {
                $this->access_token = $new_token;
                // Retry the request with the new token
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Bearer ' . $this->access_token
                    ),
                ));

                $response = curl_exec($curl);
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);
                $data = json_decode($response, true);

                if ($http_code == 200) {

                    return $data['id'];
                } else {
                    echo "Error 1: Unable to post data.";
                    return false;
                }
            } else {
                echo "Error 2: Unable to refresh token.";
                return false;
            }
        } else if ($http_code == 200) {
            return $data['id'];
        } else {
            echo "Error 2: Unable to get id.";
            return false;
        }
    }

    private function refreshToken()
    {
        global $dbh;
        $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        );
        $json_data = http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $response_data = json_decode($response, true);
            $token = $response_data['access_token'];

            $query = "UPDATE users SET outlooktoken = ? WHERE id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->execute([$token, $this->user_id]);

            return $token;
        } else {
            return false;
        }
    }

    public function getEvents($queryParams)
    {
        $url = 'https://graph.microsoft.com/v1.0/me/events';
        $headers = array(
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($queryParams));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    public function postData($data)
    {
        if(isset($data['access_token'])){
            $this->access_token = $data['access_token'];
        }

        if(isset($data['refresh_token'])){
            $this->refresh_token = $data['refresh_token'];
        }
        
        $timeZone = $this->getTimeZone($data['dateTimeStart']);
        $dateStart = $this->formattedDate($data['dateTimeStart']);
        $dateTo = $this->formattedDate($data['dateTimeEnd']);
        $userOutlookId = $this->getUserId();
        $url = 'https://graph.microsoft.com/v1.0/users/' . $userOutlookId . '/events';
        // return $url;

        $eventData = [
            "subject" => $data['subject'],
            // "body" => [
            //     "contentType" => "HTML",
            //     "content" => "Discuss the project updates."
            // ],
            "start" => [
                "dateTime" => $dateStart, // Ganti dengan waktu mulai acara
                "timeZone" => $timeZone // Ganti dengan zona waktu yang sesuai
            ],
            "end" => [
                "dateTime" => $dateTo, // Ganti dengan waktu selesai acara
                "timeZone" => $timeZone // Ganti dengan zona waktu yang sesuai
            ],
            "location" => [
                "displayName" => $data['location'] // Ganti dengan nama lokasi
            ]
        ];


        $headers = array(
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        );

        $json_data = json_encode($eventData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }

    public function deleteData($eventId)
    {
        $userOutlookId = $this->getUserId();
        $url = 'https://graph.microsoft.com/v1.0/users/' . $userOutlookId . '/events/' . $eventId;

        $headers = array(
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 204) {
            return true;
        } else {
            echo "Error: Unable to delete event.";
            return false;
        }
    }
}