<?php
include '../config.ini.php';
include_once("../config.ini.curl.php");
include("../class/class.TripPlan.php");
$trip = new TripPlan();


function uniqidgen()
{
    $unique_id = bin2hex(openssl_random_pseudo_bytes(2)) . time() . mt_rand(100, 999);
    return $unique_id;
}

if (isset($_POST['location_triptype']) && isset($_POST['location_from']) && isset($_POST['location_to']) && isset($_POST['location_from_latlng']) && isset($_POST['location_to_latlng'])) {

    $from = $_POST["location_from"];
    $to = $_POST["location_to"];
    $from_latlng = $_POST["location_from_latlng"];
    $to_latlng = $_POST["location_to_latlng"];
    $transport = filter_var($_POST["transport"], FILTER_SANITIZE_STRING);
    //$transport = filter_var("plane", FILTER_SANITIZE_STRING);
    $filter = array();
    if (isset($_POST['filter_option'])) {
        $filter = $_POST['filter_option'];
    }
    $embassis = '';
    if (isset($_POST['embassy_list'])) {
        $embassis = $_POST['embassy_list'];
    }
    
    function formateDate($hold_Date)
    {
        $hold = date('Y-m-d', strtotime($hold_Date));
        return $hold;
    }
    
    $itinerary_type = 'trip';

    $location_triptype = $_POST['location_triptype'];
    $location_datel = $_POST['location_datel'] ? formateDate($_POST['location_datel']) : null;
    $location_datel_arr = $_POST['location_datel_arr'] ? formateDate($_POST['location_datel_arr']) : null;
    $location_datel_deptime = $_POST['location_datel_deptime'];
    $location_datel_arrtime = $_POST['location_datel_arrtime'];
    $dep_flight_no = "";
    if (isset($_POST['dep_flight_no'])) {
        $dep_flight_no = $_POST['dep_flight_no'];
    }
    $dep_seat_no = "";
    if (isset($_POST['dep_seat_no'])) {
        $dep_seat_no = $_POST['dep_seat_no'];
    }
    $location_dater = $_POST['location_dater'] ? formateDate($_POST['location_dater']) : null;
    $location_dater_arr = $_POST['location_dater_arr'] ? formateDate($_POST['location_dater_arr']) : null;
    $location_dater_deptime = $_POST['location_dater_deptime'];
    $location_dater_arrtime = $_POST['location_dater_arrtime'];
    $ret_flight_no = "";
    if (isset($_POST['ret_flight_no'])) {
        $ret_flight_no = $_POST['ret_flight_no'];
    }
    $ret_seat_no = "";
    if (isset($_POST['ret_seat_no'])) {
        $ret_seat_no = $_POST['ret_seat_no'];
    }
    $hotel_name = $_POST['hotel_name'];
    $hotel_date_checkin = $_POST['hotel_date_checkin'];
    $hotel_date_checkout = $_POST['hotel_date_checkout'];
    $rental_agency = $_POST['rental_agency'];
    $rental_date_pick = $_POST['rental_date_pick'];
    $rental_date_drop = $_POST['rental_date_drop'];
    $location_from_flightportion = '';
    if (isset($_POST['location_from_flightportion'])) {
        $location_from_flightportion = $_POST['location_from_flightportion'];
    }
    $location_to_flightportion = '';
    if (isset($_POST['location_to_flightportion'])) {
        $location_to_flightportion = $_POST['location_to_flightportion'];
    }
    $location_from_latlng_flightportion = '';
    if (isset($_POST['location_from_latlng_flightportion'])) {
        $location_from_latlng_flightportion = $_POST['location_from_latlng_flightportion'];
    }
    $location_to_latlng_flightportion = '';
    if (isset($_POST['location_to_latlng_flightportion'])) {
        $location_to_latlng_flightportion = $_POST['location_to_latlng_flightportion'];
    }

    $location_from_drivingportion = $_POST['location_from_drivingportion'];
    $location_to_drivingportion = $_POST['location_to_drivingportion'];
    $location_from_latlng_drivingportion = $_POST['location_from_latlng_drivingportion'];
    $location_to_latlng_drivingportion = $_POST['location_to_latlng_drivingportion'];
    $location_from_trainportion = $_POST['location_from_trainportion'];
    $location_to_trainportion = $_POST['location_to_trainportion'];
    $location_from_latlng_trainportion = $_POST['location_from_latlng_trainportion'];
    $location_to_latlng_trainportion = $_POST['location_to_latlng_trainportion'];
    $multi_waypoint_seat_no = isset($_POST['multi_waypoint_seat_no']) ? $_POST['multi_waypoint_seat_no'] : "";
    $multi_waypoint_flight_no = isset($_POST['multi_waypoint_flight_no']) ? $_POST['multi_waypoint_flight_no'] : "";
    $location_multi_waypoint = isset($_POST['multi_location_waypoint']) ? $_POST['multi_location_waypoint'] : "";
    $location_multi_waypoint_date = isset($_POST['multi_location_waypoint_date']) ? $_POST['multi_location_waypoint_date'] : "";
    $location_multi_waypoint_dep_date = isset($_POST['multi_location_waypoint_dep_date']) ? $_POST['multi_location_waypoint_dep_date'] : "";
    $location_multi_waypoint_latlng = isset($_POST['location_multi_waypoint_latlng']) ? $_POST['location_multi_waypoint_latlng'] : "";
    $via_waypoints = isset($_POST['via_waypoints']) ? $_POST['via_waypoints'] : "";
    $hotel_address = "";

    if (isset($_POST['hotel_located']) && $_POST['hotel_located'] == 'on') {
        $hotel_address = 'Located at Airport';
    } else {
        $hotel_address = $_POST['hotel_address'];
    }

    $rental_agency_address = "";
    if (isset($_POST['rental_agency_located']) && $_POST['rental_agency_located'] == 'on') {
        $rental_agency_address = 'Located at Airport';
    } else {
        $rental_agency_address = $_POST['rental_agency_address'];
    }
    
    $location_portion_to_latlng = $_POST['location_portion_to_latlng'];
    
    $uniqidgen = uniqidgen();

    if (!empty($from) && !empty($to) && !empty($from_latlng) && !empty($to_latlng)) { // save data trip in DB

        $location_category = $location_category ?? null;
        $location_note = $location_note ?? null;
        $location_document = $location_document ?? null;
        $contact_name = $contact_name ?? null;
        $contact_phone = $contact_phone ?? null;
        $location_with_name = $location_with_name ?? null;
        $rental_number = $rental_number ?? null;
        $flight_number = $flight_number ?? null;
        $flight_confirmation_number = $flight_confirmation_number ?? null;
        $flight_time_start = $flight_time_start ?? null;
        $flight_time_end = $flight_time_end ?? null;
        $train_time_start = $train_time_start ?? null;
        $train_time_end = $train_time_end ?? null;
        $train_confirmation_number = $train_confirmation_number ?? null;

        $location_contact_name = $location_contact_name ?? null;
        $location_contact_phone = $location_contact_phone ?? null;
        $location_contact_email = $location_contact_email ?? null;

        $lastId = $trip->put_data(
            $uniqidgen, $itinerary_type, $transport, $from, $to, $from_latlng, $to_latlng, $filter, $embassis,
            $location_triptype, $location_datel, $location_datel_deptime, $location_datel_arr, $location_datel_arrtime,
            $dep_flight_no, $dep_seat_no, $location_dater, $location_dater_deptime, $location_dater_arr,
            $location_dater_arrtime, $ret_flight_no, $ret_seat_no, $hotel_name, $hotel_date_checkin, $hotel_date_checkout,
            $rental_agency, $rental_date_pick, $rental_date_drop, $location_from_flightportion, $location_to_flightportion,
            $location_from_latlng_flightportion, $location_to_latlng_flightportion, $location_from_drivingportion,
            $location_to_drivingportion, $location_from_latlng_drivingportion, $location_to_latlng_drivingportion,
            $location_from_trainportion, $location_to_trainportion, $location_from_latlng_trainportion,
            $location_to_latlng_trainportion, "", "", $hotel_address, $rental_agency_address,
            json_encode($location_multi_waypoint), $location_multi_waypoint_latlng, $via_waypoints, $location_multi_waypoint_date,
            $location_multi_waypoint_dep_date, $multi_waypoint_flight_no, $multi_waypoint_seat_no, $location_portion_to_latlng,
            $location_category, $location_note, $location_document, $contact_name, $contact_phone, $location_with_name,
            $rental_number, $flight_number, $flight_confirmation_number, $flight_time_start, $flight_time_end,
            $train_time_start, $train_time_end, $train_confirmation_number,
            $location_contact_name, $location_contact_phone, $location_contact_email
        );

        if (!$trip->error) {

            http_response_code(200);

            $response = array(
                "status" => 200,
                "data" => $lastId,
                "message" => "Successfully trip created",
            );
        } else {

            http_response_code(422);

            $response = array(
                'status' => 422,
                'message' => "A system error has been encountered",
            );
        }
    } else {


        http_response_code(422);

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered.",
        );
    }

    echo json_encode($response);
}
