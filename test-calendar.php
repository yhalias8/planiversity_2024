<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

include_once("config.ini.php");
include_once("config.ini.curl.php");
include("class/class.Googlecalendar.php");


$googleCalendarAPI = new GoogleCalendar();

$data = [
    'summary' => 'Test Event from PHP',
    'dateTimeStart' => '2024-10-15',
    'dateTimeEnd' => '2024-10-15',
    'location' => 'Online',
    'description' => 'test',
];

$response = $googleCalendarAPI->postData($data);
print_r($response); return;
// $response_data = json_decode($response, true);
// $gcalendar_id = $response_data['id'];
// print_r($response_data); return;
