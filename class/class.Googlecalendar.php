<?php
include_once(__DIR__ . "/../config.app.php");
include_once(__DIR__ . "/../config.ini.php");
include_once(__DIR__ . "/../config.ini.curl.php");

class GoogleCalendar
{

    private $access_token;
    private $refresh_token;
    private $client_id;
    private $client_secret;
    private $user_id;
    private $calendarId;


    public function __construct()
    {
        global $userdata;
        $this->user_id = $userdata['id'];
        $this->access_token = $userdata['gcaltoken'];
        $this->refresh_token = $userdata['gcaltoken_refresh'];
        $this->calendarId = 'primary'; // or the actual calendar ID
        $this->client_id = GOOGLE_CLIENT_ID;
        $this->client_secret = GOOGLE_CLIENT_SECRET;
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

    private function refreshToken($postData)
    {
        global $dbh; // Access the global $dbh variable

        $url = "https://oauth2.googleapis.com/token";
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        );
        // return $data; 
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

            $query = "UPDATE users SET gcaltoken = ? WHERE id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->execute([$token, $this->user_id]);

            return $response_data['access_token'];
        } else {
            return false;
        }
    }

    public function postData($data)
    {
        if(isset($data['access_token'])){
            $this->access_token = $data['access_token'];
        }

        if(isset($data['refresh_token'])){
            $this->access_token = $data['refresh_token'];
        }

        $timeZone = $this->getTimeZone($data['dateTimeStart']);
        $dateStart = $this->formattedDate($data['dateTimeStart']);
        $dateTo = $this->formattedDate($data['dateTimeEnd']);

        $url = "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events";

        $eventData = [
            "summary" => $data['summary'],
            "location" => $data['location'],
            "description" => $data['description'],
            "start" => [
                "dateTime" => $dateStart, // Ganti dengan waktu mulai acara
                "timeZone" => $timeZone // Ganti dengan zona waktu yang sesuai
            ],
            "end" => [
                "dateTime" => $dateTo, // Ganti dengan waktu selesai acara
                "timeZone" => $timeZone // Ganti dengan zona waktu yang sesuai
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


        if ($http_code == 401) {
            // UNAUTHENTICATED error, token is invalid or expired
            $new_token = $this->refreshToken($data);

            if ($new_token) {
                $this->access_token = $new_token;
                // Retry the request with the new token
                $headers = array(
                    'Authorization: Bearer ' . $this->access_token,
                    'Content-Type: application/json'
                );
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($http_code == 200) {
                    return $response;
                } else {
                    echo "Error: Unable to post data.";
                    return false;
                }
            } else {
                echo "Error: Unable to refresh token.";
                return false;
            }
        } elseif ($http_code == 200) {
            return $response;
        } else {
            echo "Error: Unable to post data.";
            return false;
        }
    }

    public function deleteData($data)
    {
        $url = "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events/{$data}";

        $headers = array(
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 401) {
            // UNAUTHENTICATED error, token is invalid or expired
            $new_token = $this->refreshToken($data);
            if ($new_token) {
                $this->access_token = $new_token;
                // Retry the request with the new token
                $headers = array(
                    'Authorization: Bearer ' . $this->access_token,
                    'Content-Type: application/json'
                );
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($http_code == 204) {
                    return true; // Event deleted successfully
                } else {
                    return false; // Error deleting event
                }
            } else {
                echo "Error: Unable to refresh token.";
                return false;
            }
        } elseif ($http_code == 204) {
            return true; // Event deleted successfully
        } else {
            echo "Error: Unable to delete event.";
            return false;
        }
    }
}