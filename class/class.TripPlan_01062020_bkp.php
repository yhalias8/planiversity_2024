<?php

class TripPlan {

    var $trip_id = 0;
    var $user_id = 0;
    var $trip_title = '';
    var $trip_transport = '';
    var $trip_location_from = '';
    var $trip_location_to = '';
    var $trip_location_from_latlng = '';
    var $trip_location_waypoint_latlng = '';
    var $trip_location_waypoint = '';
    var $trip_location_triptype = '';
    var $trip_location_datel = '';
    var $trip_location_datel_deptime = '';
    var $trip_location_datel_arrtime = '';
    var $trip_dep_flight_no = '';
    var $trip_dep_seat_no = '';
    var $trip_location_dater = '';
    var $trip_location_dater_deptime = '';
    var $trip_location_dater_arrtime = '';
    var $trip_ret_flight_no = '';
    var $trip_ret_seat_no = '';
    var $trip_hotel_name = '';
    var $trip_hotel_date_checkin = '';
    var $trip_hotel_date_checkout = '';
    var $trip_rental_agency = '';
    var $trip_rental_date_pick = '';
    var $trip_rental_date_drop = '';
    var $trip_location_from_flightportion = '';
    var $trip_location_to_flightportion = '';
    var $trip_location_from_latlng_flightportion = '';
    var $trip_location_to_latlng_flightportion = '';
    var $trip_location_from_drivingportion = '';
    var $trip_location_to_drivingportion = '';
    var $trip_location_from_latlng_drivingportion = '';
    var $trip_location_to_latlng_drivingportion = '';
    var $trip_location_from_trainportion = '';
    var $trip_location_to_trainportion = '';
    var $trip_location_from_latlng_trainportion = '';
    var $trip_location_to_latlng_trainportion = '';
    var $trip_option_weather = 0;
    var $trip_option_hotels = 0;
    var $trip_option_police = 0;
    var $trip_option_hospitals = 0;
    var $trip_option_gas = 0;
    var $trip_option_subway = 0;
    var $trip_option_embassis = 0;
    var $trip_option_taxi = 0;
    var $trip_option_airfields = 0;
    var $trip_option_directions = 0;
    var $trip_option_busmap = 0;
    var $trip_option_parking = 0;
    var $trip_option_university = 0;
    var $trip_option_atm = 0;
    var $trip_option_museum = 0;
    var $trip_option_church = 0;
    var $trip_option_metro = 0;
    var $trip_option_subway_station = 0;
    var $trip_option_playground = 0;
    var $trip_option_library = 0;
    var $trip_option_pharmacy = 0;
    var $trip_option_circle = 0;
    var $trip_list_embassis = '';
    var $trip_employee = 0;
    var $trip_directions_text = '';
    var $table = '';
    var $error = '';

    public function __construct($nametable = 'trips') {
        $this->trip_id = 0;
        $this->user_id = 0;
        $this->trip_title = '';
        $this->trip_transport = '';
        $this->trip_location_from = '';
        $this->trip_location_to = '';
        $this->trip_location_from_latlng = '';
        $this->trip_location_to_latlng = '';
        $this->trip_location_waypoint = '';
        $this->trip_location_waypoint_latlng = '';

        $this->trip_location_triptype = '';
        $this->trip_location_datel = '';
        $this->trip_location_datel_deptime = '';
        $this->trip_location_datel_arrtime = '';
        $this->trip_dep_flight_no = '';
        $this->trip_dep_seat_no = '';
        $this->trip_location_dater = '';
        $this->trip_location_dater_deptime = '';
        $this->trip_location_dater_arrtime = '';
        $this->trip_ret_flight_no = '';
        $this->trip_ret_seat_no = '';
        $this->trip_hotel_name = '';
        $this->trip_hotel_date_checkin = '';
        $this->trip_hotel_date_checkout = '';
        $this->trip_rental_agency = '';
        $this->trip_rental_date_pick = '';
        $this->trip_rental_date_drop = '';
        $this->trip_location_from_flightportion = '';
        $this->trip_location_to_flightportion = '';
        $this->trip_location_from_latlng_flightportion = '';
        $this->trip_location_to_latlng_flightportion = '';
        $this->trip_location_from_drivingportion = '';
        $this->trip_location_to_drivingportion = '';
        $this->trip_location_from_latlng_drivingportion = '';
        $this->trip_location_to_latlng_drivingportion = '';
        $this->trip_location_from_trainportion = '';
        $this->trip_location_to_trainportion = '';
        $this->trip_location_from_latlng_trainportion = '';
        $this->trip_location_to_latlng_trainportion = '';

        $this->trip_option_weather = 0;
        $this->trip_option_hotels = 0;
        $this->trip_option_police = 0;
        $this->trip_option_hospitals = 0;
        $this->trip_option_gas = 0;
        $this->trip_option_subway = 0;
        $this->trip_option_embassis = 0;
        $this->trip_option_taxi = 0;
        $this->trip_option_airfields = 0;
        $this->trip_option_directions = 0;
        $this->trip_option_busmap = 0;
        $this->trip_option_parking = 0;
        $this->trip_option_university = 0;
        $this->trip_option_atm = 0;
        $this->trip_option_museum = 0;
        $this->trip_option_church = 0;
        $this->trip_option_playground = 0;
        $this->trip_option_metro = 0;
        $this->trip_option_subway_station = 0;
        $this->trip_option_library = 0;
        $this->trip_option_pharmacy = 0;
        $this->trip_option_circle = 0;
        $this->trip_list_embassis = '';
        $this->trip_employee = 0;
        $this->trip_directions_text = '';
        $this->table = $nametable;
        $this->error = '';
    }

//    function TripPlan($nametable = 'trips') {
//        $this->trip_id = 0;
//        $this->user_id = 0;
//        $this->trip_title = '';
//        $this->trip_transport = '';
//        $this->trip_location_from = '';
//        $this->trip_location_to = '';
//        $this->trip_location_from_latlng = '';
//        $this->trip_location_to_latlng = '';
//        $this->trip_location_waypoint = '';
//        $this->trip_location_waypoint_latlng = '';
//
//        $this->trip_location_triptype = '';
//        $this->trip_location_datel = '';
//        $this->trip_location_datel_deptime = '';
//        $this->trip_location_datel_arrtime = '';
//        $this->trip_dep_flight_no = '';
//        $this->trip_dep_seat_no = '';
//        $this->trip_location_dater = '';
//        $this->trip_location_dater_deptime = '';
//        $this->trip_location_dater_arrtime = '';
//        $this->trip_ret_flight_no = '';
//        $this->trip_ret_seat_no = '';
//        $this->trip_hotel_name = '';
//        $this->trip_hotel_date_checkin = '';
//        $this->trip_hotel_date_checkout = '';
//        $this->trip_rental_agency = '';
//        $this->trip_rental_date_pick = '';
//        $this->trip_rental_date_drop = '';
//        $this->trip_location_from_flightportion = '';
//        $this->trip_location_to_flightportion = '';
//        $this->trip_location_from_latlng_flightportion = '';
//        $this->trip_location_to_latlng_flightportion = '';
//        $this->trip_location_from_drivingportion = '';
//        $this->trip_location_to_drivingportion = '';
//        $this->trip_location_from_latlng_drivingportion = '';
//        $this->trip_location_to_latlng_drivingportion = '';
//        $this->trip_location_from_trainportion = '';
//        $this->trip_location_to_trainportion = '';
//        $this->trip_location_from_latlng_trainportion = '';
//        $this->trip_location_to_latlng_trainportion = '';
//
//        $this->trip_option_weather = 0;
//        $this->trip_option_hotels = 0;
//        $this->trip_option_police = 0;
//        $this->trip_option_hospitals = 0;
//        $this->trip_option_gas = 0;
//        $this->trip_option_subway = 0;
//        $this->trip_option_embassis = 0;
//        $this->trip_option_taxi = 0;
//        $this->trip_option_airfields = 0;
//        $this->trip_option_directions = 0;
//        $this->trip_option_busmap = 0;
//        $this->trip_option_parking = 0;
//        $this->trip_option_university = 0;
//        $this->trip_option_atm = 0;
//        $this->trip_option_museum = 0;
//        $this->trip_option_church = 0;
//        $this->trip_option_playground = 0;
//        $this->trip_option_metro = 0;
//        $this->trip_option_subway_station = 0;
//        $this->trip_option_library = 0;
//        $this->trip_option_pharmacy = 0;
//        $this->trip_option_circle = 0;
//        $this->trip_list_embassis = '';
//        $this->trip_employee = 0;
//        $this->trip_directions_text = '';
//        $this->table = $nametable;
//        $this->error = '';
//    }

    function get_data($id) {
        global $dbh, $userdata;
        $stmt = $tmp = '';

        if (!empty($id)) {
            $stmt = $dbh->prepare("SELECT * FROM " . $this->table . " WHERE id_trip=? AND id_user=?");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();

            if (!$tmp)
                $this->error = 'error_fail';
            elseif ($stmt->rowCount() > 0) {
                $trips = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($trips as $trip) {
                    $this->trip_id = $trip->id_trip;
                    $this->user_id = $trip->id_user;
                    $this->trip_title = $trip->title;
                    $this->trip_transport = $trip->transport;
                    $this->trip_location_from = $trip->location_from;
                    $this->trip_location_to = $trip->location_to;
                    $this->trip_location_from_latlng = $trip->location_from_latlng;
                    $this->trip_location_to_latlng = $trip->location_to_latlng;
                    $this->trip_location_waypoint = $trip->location_waypoint;
                    $this->trip_location_waypoint_latlng = $trip->location_waypoint_latlng;

                    $this->trip_location_triptype = $trip->location_triptype;
                    $this->trip_location_datel = $trip->location_datel;
                    $this->trip_location_datel_deptime = $trip->location_datel_deptime;
                    $this->trip_location_datel_arrtime = $trip->location_datel_arrtime;
                    $this->trip_dep_flight_no = $trip->dep_flight_no;
                    $this->trip_dep_seat_no = $trip->dep_seat_no;
                    $this->trip_location_dater = $trip->location_dater;
                    $this->trip_location_dater_deptime = $trip->location_dater_deptime;
                    $this->trip_location_dater_arrtime = $trip->location_dater_arrtime;
                    $this->trip_dep_flight_no = $trip->ret_flight_no;
                    $this->trip_dep_seat_no = $trip->ret_seat_no;
                    $this->trip_hotel_name = $trip->hotel_name;
                    $this->trip_hotel_date_checkin = $trip->hotel_date_checkin;
                    $this->trip_hotel_date_checkout = $trip->hotel_date_checkout;
                    $this->trip_rental_agency = $trip->rental_agency;
                    $this->trip_rental_date_pick = $trip->rental_date_pick;
                    $this->trip_rental_date_drop = $trip->rental_date_drop;
                    $this->trip_location_from_flightportion = $trip->location_from_flightportion;
                    $this->trip_location_to_flightportion = $trip->location_to_flightportion;
                    $this->trip_location_from_latlng_flightportion = $trip->location_from_latlng_flightportion;
                    $this->trip_location_to_latlng_flightportion = $trip->location_to_latlng_flightportion;
                    $this->trip_location_from_drivingportion = $trip->location_from_drivingportion;
                    $this->trip_location_to_drivingportion = $trip->location_to_drivingportion;
                    $this->trip_location_from_latlng_drivingportion = $trip->location_from_latlng_drivingportion;
                    $this->trip_location_to_latlng_drivingportion = $trip->location_to_latlng_drivingportion;
                    $this->trip_location_from_trainportion = $trip->location_from_trainportion;
                    $this->trip_location_to_trainportion = $trip->location_to_trainportion;
                    $this->trip_location_from_latlng_trainportion = $trip->location_from_latlng_trainportion;
                    $this->trip_location_to_latlng_trainportion = $trip->location_to_latlng_trainportion;

                    $this->trip_option_weather = $trip->option_weather;
                    $this->trip_option_hotels = $trip->option_hotels;
                    $this->trip_option_police = $trip->option_police;
                    $this->trip_option_hospitals = $trip->option_hospitals;
                    $this->trip_option_gas = $trip->option_gas;
                    $this->trip_option_subway = $trip->option_subway;
                    $this->trip_option_embassis = $trip->option_embassis;
                    $this->trip_option_taxi = $trip->option_taxi;
                    $this->trip_option_airfields = $trip->option_airfields;
                    $this->trip_option_directions = $trip->option_directions;
                    $this->trip_option_busmap = $trip->option_busmap;
                    $this->trip_option_parking = $trip->option_parking;
                    $this->trip_option_university = $trip->option_university;
                    $this->trip_option_atm = $trip->option_atm;
                    $this->trip_option_museum = $trip->option_museum;
                    $this->trip_option_church = $trip->option_church;
                    $this->trip_option_playground = $trip->option_playground;
                    $this->trip_option_metro = $trip->option_metro;
                    $this->trip_option_library = $trip->option_metro;
                    $this->trip_option_pharmacy = $trip->option_pharmacy;
                    $this->trip_option_subway_station = $trip->option_subway_station;
                    $this->trip_option_circle = $trip->option_circle;
                    $this->trip_list_embassis = $trip->embassis_list;
                    $this->trip_employee = $trip->id_employee;
                    $this->trip_directions_text = $trip->directions_text;
                }
            } else
                $this->error = 'error_access';
        } else
            $this->error = 'error_mising';
    }

    function put_data($transport, $from, $to, $from_latlng, $to_latlng, $filter, $embasylist = '', $location_triptype, $location_datel, $location_datel_deptime, $location_datel_arrtime, $dep_flight_no, $dep_seat_no, $location_dater, $location_dater_deptime, $location_dater_arrtime, $ret_flight_no, $ret_seat_no, $hotel_name, $hotel_date_checkin, $hotel_date_checkout, $rental_agency, $rental_date_pick, $rental_date_drop, $location_from_flightportion, $location_to_flightportion, $location_from_latlng_flightportion, $location_to_latlng_flightportion, $location_from_drivingportion, $location_to_drivingportion, $location_from_latlng_drivingportion, $location_to_latlng_drivingportion, $location_from_trainportion, $location_to_trainportion, $location_from_latlng_trainportion, $location_to_latlng_trainportion, $waypoint = '', $waypoint_latlng = '', $hotel_address, $rental_agency_address) {
        global $dbh, $userdata;
        $stmt = $tmp = $query = $queryfilter = $queryfiltervalue = '';
//        $elist = implode(',', $embasylist);

        if (!empty($from) && !empty($to)) {/* if (!empty($embasylist))
          $query = "INSERT INTO ".$this->table." (id_user, transport, location_from, location_to, location_from_latlng, location_to_latlng, option_weather, option_hotels, option_police, option_hospitals, option_gas, option_subway, option_embassis, embassis_list) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          else */
            /* $query = "INSERT INTO `".$this->table."` (`id_user`, `title`, `transport`, `location_triptype`, `location_datel`, `location_dater`, `location_from`, `location_to`, `location_from_latlng`, `location_to_latlng`, `location_from_flightportion`, `location_to_flightportion`, `location_from_latlng_flightportion`, `location_to_latlng_flightportion`, `location_from_drivingportion`, `location_to_drivingportion`, `location_from_latlng_drivingportion`, `location_to_latlng_drivingportion`, `location_from_trainportion`, `location_to_trainportion`, `location_from_latlng_trainportion`, `location_to_latlng_trainportion`, `option_weather`, `option_hotels`, `option_police`, `option_hospitals`, `option_gas`, `option_subway`, `option_embassis`, `option_circle`, `option_taxi`, `option_airfields`, `option_busmap`, `option_parking`, `option_directions`, `embassis_list`, `id_employee`, `pdf_generated`) VALUES ('".$userdata['id']."', NULL, '".$transport."', '".$location_triptype."', '".$location_datel."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, '0');";
              $stmt = $dbh->prepare($query); */

            //$query = "INSERT INTO ".$this->table." (id_user, transport, location_triptype, location_datel, location_dater, location_from, location_to, location_from_latlng, location_to_latlng, location_from_flightportion, location_to_flightportion, location_from_latlng_flightportion, location_to_latlng_flightportion, location_from_drivingportion, location_to_drivingportion, location_from_latlng_drivingportion, location_to_latlng_drivingportion, location_from_trainportion, location_to_trainportion, location_from_latlng_trainportion, location_to_latlng_trainportion, option_weather, option_hotels, option_police, option_hospitals, option_gas, option_subway, option_embassis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $query = "INSERT INTO " . $this->table . " (id_user, transport, location_triptype, location_datel,location_datel_deptime, location_datel_arrtime ,dep_flight_no, dep_seat_no, location_dater, location_dater_deptime, location_dater_arrtime,ret_flight_no, ret_seat_no,hotel_name,hotel_date_checkin,hotel_date_checkout,rental_agency, rental_date_pick, rental_date_drop, location_from, location_to, location_from_latlng, location_to_latlng, location_from_flightportion, location_to_flightportion, location_from_latlng_flightportion, location_to_latlng_flightportion, location_from_drivingportion, location_to_drivingportion, location_from_latlng_drivingportion, location_to_latlng_drivingportion, location_from_trainportion, location_to_trainportion, location_from_latlng_trainportion, location_to_latlng_trainportion, option_weather, option_hotels, option_police, option_hospitals, option_gas, option_subway, option_taxi, option_airfields, option_busmap, option_university, option_atm, option_museum, option_church, option_embassis, option_metro, option_playground, option_subway_station, option_library, option_pharmacy, location_waypoint, location_waypoint_latlng,hotel_address,rental_agency_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
//               echo $query;die('--');
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $stmt->bindValue(2, $transport, PDO::PARAM_STR);
            $stmt->bindValue(3, $location_triptype, PDO::PARAM_STR);
            $stmt->bindValue(4, ($location_datel) ? date('Y-m-d', strtotime($location_datel)) : '0000-00-00', PDO::PARAM_STR);
            $stmt->bindValue(5, ($location_datel_deptime) ? date('H:i', strtotime($location_datel_deptime)) : '00:00', PDO::PARAM_STR);
            $stmt->bindValue(6, ($location_datel_arrtime) ? date('H:i', strtotime($location_datel_arrtime)) : '00:00', PDO::PARAM_STR);
            $stmt->bindValue(7, $dep_flight_no, PDO::PARAM_STR);
            $stmt->bindValue(8, $dep_seat_no, PDO::PARAM_STR);
            $stmt->bindValue(9, ($location_dater) ? date('Y-m-d', strtotime($location_dater)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(10, ($location_dater_deptime) ? date('H:i', strtotime($location_dater_deptime)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(11, ($location_dater_arrtime) ? date('H:i', strtotime($location_dater_arrtime)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(12, $ret_flight_no, PDO::PARAM_STR);
            $stmt->bindValue(13, $ret_seat_no, PDO::PARAM_STR);
            $stmt->bindValue(14, $hotel_name, PDO::PARAM_STR);
            $stmt->bindValue(15, ($hotel_date_checkin) ? date('Y-m-d', strtotime($hotel_date_checkin)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(16, ($hotel_date_checkout) ? date('Y-m-d', strtotime($hotel_date_checkout)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(17, $rental_agency, PDO::PARAM_STR);
            $stmt->bindValue(18, ($rental_date_pick) ? date('Y-m-d', strtotime($rental_date_pick)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(19, ($rental_date_drop) ? date('Y-m-d', strtotime($rental_date_drop)) : NULL, PDO::PARAM_STR);
            $stmt->bindValue(20, $from, PDO::PARAM_STR);
            $stmt->bindValue(21, $to, PDO::PARAM_STR);
            $stmt->bindValue(22, $from_latlng, PDO::PARAM_STR);
            $stmt->bindValue(23, $to_latlng, PDO::PARAM_STR);
            $stmt->bindValue(24, $location_from_flightportion, PDO::PARAM_STR);
            $stmt->bindValue(25, $location_to_flightportion, PDO::PARAM_STR);
            $stmt->bindValue(26, $location_from_latlng_flightportion, PDO::PARAM_STR);
            $stmt->bindValue(27, $location_to_latlng_flightportion, PDO::PARAM_STR);
            $stmt->bindValue(28, $location_from_drivingportion, PDO::PARAM_STR);
            $stmt->bindValue(29, $location_to_drivingportion, PDO::PARAM_STR);
            $stmt->bindValue(30, $location_from_latlng_drivingportion, PDO::PARAM_STR);
            $stmt->bindValue(31, $location_to_latlng_drivingportion, PDO::PARAM_STR);
            $stmt->bindValue(32, $location_from_trainportion, PDO::PARAM_STR);
            $stmt->bindValue(33, $location_to_trainportion, PDO::PARAM_STR);
            $stmt->bindValue(34, $location_from_latlng_trainportion, PDO::PARAM_STR);
            $stmt->bindValue(35, $location_to_latlng_trainportion, PDO::PARAM_STR);
            $stmt->bindValue(36, (in_array('weather', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(37, (in_array('hotels', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(38, (in_array('police', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(39, (in_array('hospitals', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(40, (in_array('gas', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(41, (in_array('subway', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(42, (in_array('taxi', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(43, (in_array('airports', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(44, (in_array('parking', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(45, (in_array('university', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(46, (in_array('atm', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(47, (in_array('museum', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(48, (in_array('church', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(49, (in_array('embassis', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(50, (in_array('metro', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(51, (in_array('playground', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(52, (in_array('subway_station', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(53, (in_array('library', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(54, (in_array('pharmacy', $filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(55, $waypoint, PDO::PARAM_STR);
            $stmt->bindValue(56, $waypoint_latlng, PDO::PARAM_STR);
            $stmt->bindValue(57, $hotel_address, PDO::PARAM_STR);
            $stmt->bindValue(58, $rental_agency_address, PDO::PARAM_STR);

            //$stmt->bindValue(14, (in_array('taxi',$filter) ? 1 : 0), PDO::PARAM_INT);
            //$stmt->bindValue(15, (in_array('airfield',$filter) ? 1 : 0), PDO::PARAM_INT);
            //$stmt->bindValue(16, (in_array('busmap',$filter) ? 1 : 0), PDO::PARAM_INT);
            /* if (!empty($embasylist))
              $stmt->bindValue(14, $elist, PDO::PARAM_STR); */
            $tmp = $stmt->execute();

            // print_r($stmt->errorInfo());
            // die;
            if (!$tmp)
                $this->error = 'error_fail';
        } else
            $this->error = 'error_mising';
    }

    function edit_data_filter($id, $filter, $embasylist = '', $directions_text = '') {
        global $dbh;
        $elist = implode(',', $embasylist);

        if (!empty($embasylist))
            $query = "UPDATE " . $this->table . " SET option_weather = ?, option_hotels = ?, option_police = ?, option_hospitals = ?, option_gas = ?, option_subway = ?, option_embassis = ?, option_taxi = ?, option_airfields = ?, option_busmap = ?, option_parking = ?, option_directions = ?, directions_text = ?, option_circle = ?, option_university = ?, option_atm = ?, option_museum = ?, option_church = ?, embassis_list = ?, option_metro = ?, option_playground = ?, option_subway_station = ?, option_library = ?, option_pharmacy = ?  WHERE id_trip = ?";
        else
            $query = "UPDATE " . $this->table . " SET option_weather = ?, option_hotels = ?, option_police = ?, option_hospitals = ?, option_gas = ?, option_subway = ?,  option_embassis = ?, option_taxi = ?, option_airfields = ?, option_busmap = ?, option_parking = ?, option_directions = ?, directions_text = ?, option_circle = ?, option_university = ?, option_atm = ?, option_museum = ?, option_church = ?, option_metro = ?, option_playground = ?, option_subway_station = ?, option_library = ?, option_pharmacy = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, (in_array('weather', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(2, (in_array('hotels', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(3, (in_array('police', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(4, (in_array('hospitals', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(5, (in_array('gas', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(6, (in_array('subway', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(7, (in_array('embassis', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(8, (in_array('taxi_stand', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(9, (in_array('airports', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(10, (in_array('busmap', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(11, (in_array('parking', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(12, (in_array('directions', $filter) ? 1 : 0), PDO::PARAM_STR);
        $stmt->bindValue(13, $directions_text, PDO::PARAM_STR);
        $stmt->bindValue(14, $filter[50], PDO::PARAM_STR);
        $stmt->bindValue(15, (in_array('university', $filter) ? 1 : 0), PDO::PARAM_INT);
        $stmt->bindValue(16, (in_array('atm', $filter) ? 1 : 0), PDO::PARAM_STR);
        $stmt->bindValue(17, (in_array('museum', $filter) ? 1 : 0), PDO::PARAM_STR);
        $stmt->bindValue(18, (in_array('church', $filter) ? 1 : 0), PDO::PARAM_STR);
        if (!empty($embasylist)) {
            $stmt->bindValue(19, $elist, PDO::PARAM_STR);
            $stmt->bindValue(20, (in_array('metro', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(21, (in_array('playground', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(22, (in_array('subway_station', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(23, (in_array('library', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(24, (in_array('pharmacy', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(25, $id, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(19, (in_array('metro', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(20, (in_array('playground', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(21, (in_array('subway_station', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(22, (in_array('library', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(23, (in_array('pharmacy', $filter) ? 1 : 0), PDO::PARAM_STR);
            $stmt->bindValue(24, $id, PDO::PARAM_INT);
        }
        $tmp = $stmt->execute();
//          print_r($stmt->errorInfo());
//         die;
        if (!$tmp)
            $this->error = 'error_fail';
        /* { $t = $stmt->errorInfo();
          $this->error = 'error_fail - '.$t[0].'<br><br>'.$t[1].'<br><br>'.$t[2];} */
    }

    function edit_data_employee($id, $id_employee) {
        global $dbh;

        $query = "UPDATE " . $this->table . " SET id_employee = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id_employee, PDO::PARAM_INT);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp)
            $this->error = 'error_fail';
    }

    function edit_data_name($id, $name) {
        global $dbh;

        $query = "UPDATE " . $this->table . " SET title = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp)
            $this->error = 'error_fail';
    }

    /* function check_pay_plan()
      { global $dbh,$userdata;
      $stmt = $tmp = '';

      $stmt = $dbh->prepare("SELECT * FROM payments WHERE id_user=?");
      $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
      $tmp = $stmt->execute();

      if (!$tmp)
      $this->error = 'error_fail';
      elseif ($stmt->rowCount()>0)
      { /*$trips = $stmt->fetchAll(PDO::FETCH_OBJ);
      foreach ($trips as $trip){
      $this->trip_id = $trip->id_trip;
      $this->user_id = $trip->id_user;
      $this->trip_title = $trip->title;
      $this->trip_transport = $trip->transport;
      $this->trip_location_from = $trip->location_from;
      $this->trip_location_to = $trip->location_to;
      $this->trip_location_from_latlng = $trip->location_from_latlng;
      $this->trip_location_to_latlng = $trip->location_to_latlng;
      $this->trip_option_weather = $trip->option_weather;
      $this->trip_option_hotels = $trip->option_hotels;
      $this->trip_option_police = $trip->option_police;
      $this->trip_option_hospitals = $trip->option_hospitals;
      $this->trip_option_gas = $trip->option_gas;
      $this->trip_option_subway = $trip->option_subway;
      $this->trip_option_embassis = $trip->option_embassis;
      $this->trip_employee = $trip->id_employee;
      }*
      }
      else
      $this->error = 'error_access';
      } */

    function getStaticGmapURLForDirection($origin, $destination, $key, $transport, $size = "640x300", $waypt = '') {
        $markers = array();
        $waypoints_labels = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
        $waypoints_label_iter = 0;

        $mode = 'driving';
        if ($waypt != '') {
            $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode('C' . '|' . $waypt);
        }
        $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter++] . '|' . $origin);
        $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter] . '|' . $destination);

        if ($transport == 'plane') {
            $markers = implode($markers, '&');
            return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=geodesic:true|weight:3|color:0xF1A033|$origin|$destination&$markers&scale=4&key=" . $key;
        } else {
            if ($transport == 'train')
                $mode = 'TRAIN';
            if ($transport == 'vehicle')
                $mode = 'DRIVING';
            if ($waypt != '') {
                //$markers = implode($markers, '&');
                // return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=geodesic:true|weight:3|color:0xF1A033|$origin|$waypt|$destination&scale=4&key=".$key;
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $waypt . "&waypoints=" . $destination . "&mode=$mode&transit_mode=$mode&key=" . $key;
            } else {
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($origin) . "&destination=" . urlencode($destination) . "&mode=$mode&transit_mode=$mode&key=" . $key;
            }
            $result = file_get_contents($url);
            $googleDirection = json_decode($result, true);

            $polyline = urlencode($googleDirection['routes'][0]['overview_polyline']['points']);
            $markers = implode($markers, '&');

            return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=weight:3|color:0xF1A033|enc:$polyline&$markers&mode=$mode&transit_mode=$mode&scale=2&key=" . $key;
            //    return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=weight:3|color:0xF1A033|enc:$polyline&mode=$mode&transit_mode=$mode&scale=2&key=".$key;
        }
    }

    function getStaticGmapForDirections($origin, $destination, $key, $key2, $size = "640x300") {
        $markers = array();
        $waypoints_labels = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
        $waypoints_label_iter = 0;

        $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter++] . '|' . $origin);
        $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter] . '|' . $destination);
        $mode = 'DRIVING';


        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($origin) . "&destination=" . urlencode($destination) . "&mode=driving&key=" . $key . "&alternatives=true";
        $result = file_get_contents($url);
        $googleDirection = json_decode($result, true);

        $path = '';
        if (sizeof($googleDirection['routes']) > 1) {
            $i = 0;
            foreach ($googleDirection['routes'] as $routes) {
                $path.='&path=enc%3A' . urlencode($googleDirection['routes'][$i]['overview_polyline']['points']);
                $i++;
            }
        } else {
            $path = '&path=enc%3A' . urlencode($googleDirection['routes'][0]['overview_polyline']['points']);
        }

        $markers = implode($markers, '&');

        //return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=weight:3|color:0x0000ff|enc:$polyline&$markers&mode=$mode&transit_mode=$mode&scale=2&key=".$key2;
        return "https://maps.googleapis.com/maps/api/staticmap?size=$size$path&$markers&mode=$mode&transit_mode=$mode&scale=2&key=" . $key2;
    }

    function getDistance($addressFrom, $addressTo, $unit, $key) {
        //Change address format
        $formattedAddrFrom = urlencode($addressFrom); //str_replace(' ','+',$addressFrom);
        $formattedAddrTo = urlencode($addressTo); //str_replace(' ','+',$addressTo);
        //Send request and receive json data
        $geocodeFrom = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $formattedAddrFrom . '&sensor=false&key=' . $key);
        $outputFrom = json_decode($geocodeFrom);
        $geocodeTo = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $formattedAddrTo . '&sensor=false&key=' . $key);
        $outputTo = json_decode($geocodeTo);

        //Get latitude and longitude from geo data
        $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo = $outputTo->results[0]->geometry->location->lng;

        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344); //.' km';
        } else if ($unit == "N") {
            return ($miles * 0.8684); //.' nm';
        } else {
            return $miles; //.' mi';
        }
    }

    function getMapFilters($place, $filter, $key) {
        global $userdata;
        $factor = 1000;
        $scale = 'K';
        $radius = 10000;
        if ($userdata['scale'] == 'imperial') {
            $factor = 1609;
            $radius = 16090;
            $scale = 'M';
        }

        if (!empty($this->trip_option_circle)) {
            $circle_data = explode('::', $this->trip_option_circle);
            $lat_to = $circle_data[0];
            $lng_to = $circle_data[1];
            $radius = $circle_data[2];
            $radius = round($radius * $factor);
            $showclear = 1;
            $place = $lat_to . ',' . $lng_to;
        }

        $mode = '';
        if ($this->trip_transport == 'vehicle')
            $mode = 'driving';
        if ($this->trip_transport == 'train')
            $mode = 'driving'; //'transit'; 
        $tmp = '';
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . urlencode($place) . "&radius=" . $radius . "&type=$filter&key=" . $key;
//        print_r($url);
        $result = file_get_contents($url);
        $googleDirection = json_decode($result, true);



        if ($googleDirection['status'] == 'OK') {


//            $tmp .= '';


            for ($i = 0; $i < 20; $i++) { //if ($googleDirection['results'][$i]['name'])
                /* $tmp .= '<table width="60%" border="0">
                  <tr>
                  <td rowspan="2" width="20%"></td>
                  <td width="10%" rowspan="2"><img src="'.SITE.'images/map-icons/'.$filter.'.png" /></td>
                  <td><b>Name</b>: '.$googleDirection['results'][$i]['name'].'</td>
                  </tr>
                  <tr>
                  <td><b>Address</b>: '.$googleDirection['results'][$i]['vicinity'].'</td>
                  </tr>
                  <tr>
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  </tr>
                  </table>'; */
                if ($mode) {
                    $apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=" . $userdata['scale'] . "&mode=" . $mode . "&origins=" . urlencode($this->trip_location_to_drivingportion) . "&destinations=" . urlencode($googleDirection['results'][$i]['vicinity']) . "&key=" . $key;
                    $api = file_get_contents($apiurl);
                    $data = json_decode($api, true);
                    //$distance = ((int)$data['rows'][0]['elements'][0]['distance']['value'] / 1000); // Km
                    $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                    //$distance = 'xxx';
                } else { //$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".urlencode($this->trip_location_from)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                    $distance = $this->getDistance($this->trip_location_to_drivingportion, $googleDirection['results'][$i]['vicinity'], $scale, $key);
                }
                if ($googleDirection['results'][$i]['name']) {
//                    $tmp .= '<table width="90%" border="0" align="center" bgcolor="#EFF7FC" style="margin-bottom:10px">
//                                  <tr><td colspan="4" height="15px"></td></tr>
//                                  <tr>
//                                    <td width="10%"></td>
//                                    <td width="10%" align="center" valign="middle"><img src="' . SITE . 'images/map-icons/' . $filter . '.png" /></td>
//                                    <td width="58%" style="color:#555555; font-family: OpenSansRegular; font-size:16px">Name: ' . $googleDirection['results'][$i]['name'] . ' Address: ' . $googleDirection['results'][$i]['vicinity'] . '<br />
//                                                                          Distance from destination point: ' . round((float) $distance, 2) . ' ' . $scale . ' </td>
//                                    <td width="22%">Destination: ' . $this->trip_location_to_drivingportion . '</td>    
//                                  </tr>
//                                  <tr><td colspan="4" height="15px"></td></tr>                                                                   
//                                </table>';
                    $address = str_split($googleDirection['results'][$i]['vicinity'], strrpos($googleDirection['results'][$i]['vicinity'], ','));
                    $street = $address[0];
                    $city = substr($address[1], 1);
                    $tmp .= ' <tr>
                        <td style="width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;"> ' . $googleDirection['results'][$i]['name'] . '</td>
                        <td style="width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">' . $googleDirection['results'][$i]['vicinity'] . '</td>
                        <td style="width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;"> ' . round((float) $distance, 2) . ' ' . $scale . '</td>
                        <td style="width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">' . $this->trip_location_to_drivingportion . '</td>
                    </tr>';
                }
            }
        }

        return $tmp;
    }

    function getMapEmbassis($place, $key, $list = '') {
        global $userdata;
        $factor = 1000;
        $scale = 'K';
        $radius = 10000;
        if ($userdata['scale'] == 'imperial') {
            $factor = 1609;
            $radius = 16090;
            $scale = 'M';
        }

        $tmp = '';
        $mode = '';
        if ($this->trip_transport == 'vehicle')
            $mode = 'driving';
        if ($this->trip_transport == 'train')
            $mode = 'driving'; //'transit';
        $elist = explode(',', $list);
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . urlencode($place) . "&radius=" . $radius . "&type=embassy&key=" . $key;
        $result = file_get_contents($url);
        $googleDirection = json_decode($result, true);

        if ($googleDirection['status'] == 'OK') {
            for ($i = 0; $i < 20; $i++) {
                if ($googleDirection['results'][$i]['name'] && in_array($googleDirection['results'][$i]['place_id'], $elist)) {
                    if ($mode) {
                        $apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=" . $userdata['scale'] . "&mode=" . $mode . "&origins=" . urlencode($this->trip_location_from) . "&destinations=" . urlencode($googleDirection['results'][$i]['vicinity']) . "&key=" . $key;
                        $api = file_get_contents($apiurl);
                        $data = json_decode($api, true);
                        //$distance = ((int)$data['rows'][0]['elements'][0]['distance']['value'] / 1000);  // Km
                        $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                    } else { //$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".urlencode($this->trip_location_from)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                        $distance = $this->getDistance($this->trip_location_to, $googleDirection['results'][$i]['vicinity'], $scale, $key);
                    }
//                    $tmp .= '<table width="100%" border="0">
//                                   <tr>
//                                     <td colspan="4" align="center">
//                                     <p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>' . $googleDirection['results'][$i]['name'] . '</b></p>
//                                     <img src="https://maps.googleapis.com/maps/api/staticmap?center=' . urlencode($googleDirection['results'][$i]['vicinity']) . '&maptype=hybrid&zoom=17&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C' . urlencode($googleDirection['results'][$i]['vicinity']) . '&size=640x250&scale=2&key=' . $key . '" width="100%" />
//                                     </td>
//                                   </tr>
//                                   <tr>
//                                      <td colspan="4">&nbsp;</td>                                     
//                                   </tr> 
//                                   <tr>
//                                     <td rowspan="3" width="10%"></td>
//                                     <td width="10%" rowspan="3"><img src="' . SITE . 'images/map-icons/embassy.png" /></td>
//                                     <td width="80%"><b>Name</b>: ' . $googleDirection['results'][$i]['name'] . '</td>
//                                     <td><!--<b>Phone</b>: ' . $googleDirection['results'][$i]['formatted_phone_number'] . '--></td>
//                                   </tr>
//                                   <tr>
//                                     <td><b>Address</b>: ' . $googleDirection['results'][$i]['vicinity'] . '</td>
//                                     <td><!--<b>Website</b>: ' . $googleDirection['results'][$i]['website'] . '--></td>
//                                   </tr>
//                                   <tr>
//                                     <td><b>Distance from destination point: </b>: ' . round((float) $distance, 2) . ' ' . $scale . '</td>
//                                     <td></td>
//                                   </tr>                                                                    
//                                   <tr>
//                                      <td colspan="4">&nbsp;</td>                                     
//                                   </tr>
//                                 </table>';

//                        <div style="margin-left:25px;margin-right:25px">
//                     
//            < style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a;font-size: 32px;color: #111;text-transform: uppercase;text-align: center;"><b>EMBASSY OF ' . $googleDirection['results'][$i]['name'] . '</b></h3>
//            <div style="width: 50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 20px;"></div>
//        </div>
                    $tmp .= '
        <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>EMBASSY OF ' . $googleDirection['results'][$i]['name'] . '</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        <div style="overflow: visible;left: 0;right: 0;width: 100mm;margin-top: 100px;margin-bottom: auto;margin-left: auto;margin-right: auto;background-color: #0f6fc6;width: 410px;height: 774px;border-radius: 50%;"></div>
            <div style="position: absolute;overflow: visible;left: 0;right: 0;width: 100mm;margin-left: auto;margin-right: auto;top:250;">
                
                    <div class="profile_img" style="background-image: url(https://maps.googleapis.com/maps/api/staticmap?center=' . urlencode($googleDirection['results'][$i]['vicinity']) . '&maptype=hybrid&zoom=15&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C' . urlencode($googleDirection['results'][$i]['vicinity']) . '&size=350x350&scale=2&key=' . $key . ');position: absolute;
    width: 350px;
    height: 250px;
    margin-left:25%;
    border-radius: 76% 24% 27% 73% / 53% 48% 52% 47% ;
    border-style: solid;
    border-color: white;
    border-width: 10px;
    overflow: hidden;

    background-size: 450px 450px;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; "></div>
</div>
            <div style="position: absolute;overflow: visible;left: -120;right: 0;width: 100mm;margin-left: auto;margin-right: auto;top:500;">
                <table style="width:110%" cellpadding="15">
                        <tr><td style="width:20%"><img src="' . SITE . 'images/pdf-icon/5.png" width="100px" height="100px" /></td>
                            <td style="width: 100%;"><p style="color:#FFF;width: 100%;text-align: left;font-size: 17px;font-family:Montserrat-Regular"><b>Name</b>
                                    :  ' . $googleDirection['results'][$i]['name'] . '<br>
                                    <b>Address</b>:' . $googleDirection['results'][$i]['vicinity'] . '<br>
                                    <b>Distance from destination point</b>:
                                    :  ' . round((float) $distance, 2) . ' ' . $scale . '</p>
                            </td></tr>
                    </table>
                    </div>';
                }
            }
        }

        return $tmp;
    }

    /*  function getWeatherFilters($place,$pdf=0) {
      $tmp = '';
      global $userdata;
      $url = "http://api.openweathermap.org/data/2.5/forecast?q=".urlencode($place)."&cluster=yes&units=".$userdata['scale']."&format=json&APPID=d4182585780d3774b783c699d6dab316";
      $result = file_get_contents($url);
      $googleDirection = json_decode($result, true);
      if(!$pdf){
      if ($googleDirection['cod'])
      { $tmp = '<table width="100%"><tr><td>';
      for ($i=0;$i<5;$i++)
      { if ($i==0)
      { $tmp .= '<span class="dest1">' .$place. '</span><br />
      <span class="date1">' . date('l - d F', $googleDirection['list'][0]['dt']) .'</span><br />
      <div class="date2"><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][0]['weather'][0]['icon']  . '.png" alt="">'.'<big>' . $googleDirection['list'][0]['main']['temp'] . '&#8457;</big></div>';
      }
      else
      { $tmp .= '<div class="date3"><b>'.date('D', $googleDirection['list'][$i]['dt']).'</b><br /><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][$i]['weather'][0]['icon'].'.png" alt="">'.'<b style="color:#F08A0D">'.round($googleDirection['list'][$i]['main']['temp_min']).'&#8457;'.'</b><br />'.round($googleDirection['list'][$i]['main']['temp_max']).'&#8457;</div>';
      }
      }
      $tmp .= '</td></tr></table>';
      }
      }else{
      //-----------------------PDF version----------------------------
      if ($googleDirection['cod'])
      { $tmp = '<table width="100%"><tr><td><table width="100%" style="text-align:center">';
      for ($i=0;$i<5;$i++)
      { if ($i==0)
      { $tmp .= '<tr><td colspan="4"><span style="font-size:25px; color:#F08A0D">' .$place. '</span><br />' . date('l - d F', $googleDirection['list'][0]['dt']) .'</td></tr>
      <tr><td colspan="4"><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][0]['weather'][0]['icon']  . '.png" width="60px" style="margin-top:10px; margin-bottom:-20px;" alt="">'.'<big style="font-size:25px;">' . $googleDirection['list'][0]['main']['temp'] . '&#8457;</big></td></tr><tr><td colspan="4"><br /></td></tr><tr>';
      }
      else
      { $tmp .= '<td><b>'.date('D', $googleDirection['list'][$i]['dt']).'</b><br /><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][$i]['weather'][0]['icon'].'.png" alt="">'.'<br /><b style="color:#F08A0D">'.round($googleDirection['list'][$i]['main']['temp_min']).'&#8457;'.'</b> - '.round($googleDirection['list'][$i]['main']['temp_max']).'&#8457;</td>';
      }
      }
      $tmp .= '</tr></table></td></tr></table>';
      }

      }
      return $tmp;
      } */
    /* function getWeatherFilters($place,$pdf=0) {
      $tmp = '';
      global $userdata;
      $units = $userdata['scale'];
      $xml=simplexml_load_file("http://api.openweathermap.org/data/2.5/forecast?q=".$place."&units=".$userdata['scale']."&mode=xml&APPID=d4182585780d3774b783c699d6dab316");
      $weather_data='';
      if($xml){
      //------------------PDF--------------------
      if($pdf){
      $weather_data.= '<table width="350" border="0" cellpadding="0" cellspacing="0" align="center">';
      }else{
      $weather_data.= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
      }

      $location = $xml->location->name.', '.$xml->location->country;
      $sunrise = explode('T',$xml->sun['rise']);
      $sunrise = explode(':',$sunrise[1]);
      $sunrise = $sunrise[0].':'.$sunrise[1];
      $sunset = explode('T',$xml->sun['set']);
      $sunset = explode(':',$sunset[1]);
      $sunset = $sunset[0].':'.$sunset[1];

      $i=$j=0;

      foreach($xml->forecast->time as $value){
      $i++;
      $j++;
      if($units=='imperial'){
      $temp_unit = 'F';
      $speed_unit = 'mph';
      }else{
      $temp_unit = 'C';
      $speed_unit = 'Km/h';
      }
      $date_from = str_replace('T',' ',$value['from']);
      $from     = date('D',strtotime($date_from));
      $wind     = $value->windDirection['code'].', '.$value->windSpeed['mps'].' '.$speed_unit;
      $Humidity     = $value->humidity['value'].' '.$value->humidity['unit'];

      $date_to = str_replace('T',' ',$value['to']);
      $to = date('d F Y h:i a',strtotime($date_to));


      if($j==1){
      $day1 = $from;
      $clouds   = $value->clouds['value'];
      $temp_cur     = round($value->temperature['value']).'&#176;'.$temp_unit;
      $from_header  = date('l, d.m.Y',strtotime($date_from));
      switch($clouds){
      case 'broken clouds': $imgname='broken_clouds.png';
      break;
      case 'scattered clouds': $imgname='scattered_clouds.png';
      break;
      case 'few clouds': $imgname='few_clouds.png';
      break;
      case 'clear sky': $imgname='clear_sky.png';
      break;
      case 'shower rain': $imgname='shower_rain.png';
      break;
      case 'snow': $imgname='snow.png';
      break;
      case 'thunderstorm': $imgname='thunderstorm.png';
      break;
      case 'mist': $imgname='mist.png';
      break;
      case 'rain': $imgname='rain.png';
      break;
      default : $imgname='clear_sky.png';
      break;

      }
      //------------------PDF--------------------
      if($pdf){
      $weather_data.= '
      <tr>
      <td colspan="5"><span style="font-size:1.5em; color:#333">'.$location.'</span><br /><span style="color:#999;">'.$from_header.'</span></td>
      </tr>
      <tr>
      <td colspan="5">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tr style="text-align:left">
      <td width="20%"><img src="'.SITE.'images/weather-icons/default/'.$imgname.'"  style="width:70px;" /></td>
      <td width="80%"><span style="font-size:2.25em; color:#1C72B4;">'.$temp_cur.'</span></td>
      </tr>
      </table>
      <span class="date4"><span style="color:#999;">Current:</span> '.$clouds.'</span><br />
      <span class="date4"><span style="color:#999;">Wind:</span> '.$wind.'</span><br />
      <span class="date4"><span style="color:#999;">Humidity:</span> '.$Humidity.'</span><br />
      <span class="date4"><span style="color:#999;">Sunrise:</span> '.$sunrise.' <span style="color:#999;">Sunset:</span> '.$sunset.'</span>

      </td>

      </tr>

      <tr>
      <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr><td colspan="5">&nbsp;</td></tr>
      <tr><td colspan="5"><hr /></td></tr>
      <tr>
      ';
      }else{
      $weather_data.= '
      <tr>
      <td colspan="5"><span class="dest1">'.$location.'</span><br /><span class="date1">'.$from_header.'</span></td>
      </tr>
      <tr>
      <td colspan="5">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="date2" >
      <tr valign="middle">
      <td valign="middle" width="40%"><img src="'.SITE.'images/weather-icons/default/'.$imgname.'" /></td>
      <td width="60%">'.$temp_cur.'</td>
      </tr>
      </table>
      <span class="date4"><b>Current:</b> '.$clouds.'</span><br />
      <span class="date4"><b>Wind:</b> '.$wind.'</span><br />
      <span class="date4"><b>Humidity:</b> '.$Humidity.'</span><br />
      <span class="date4"><b>Sunrise:</b> '.$sunrise.' <b>Sunset:</b> '.$sunset.'</span>
      </td>
      </tr>

      <tr>
      <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="date3" style="text-align:center;">
      <tr><td colspan="5"><hr /></td></tr>
      <tr>
      ';
      }
      }
      if($i==2){
      $t_max = floatval($value->temperature['max']);
      $temp_max = number_format($t_max,0).'&#176;'.$temp_unit;
      }
      if($day1!=$from){
      $clouds   = $value->clouds['value'];
      switch($clouds){
      case 'broken clouds': $imgname='broken_clouds.png';
      break;
      case 'scattered clouds': $imgname='scattered_clouds.png';
      break;
      case 'few clouds': $imgname='few_clouds.png';
      break;
      case 'clear sky': $imgname='clear_sky.png';
      break;
      case 'shower rain': $imgname='shower_rain.png';
      break;
      case 'snow': $imgname='snow.png';
      break;
      case 'thunderstorm': $imgname='thunderstorm.png';
      break;
      case 'mist': $imgname='mist.png';
      break;
      case 'rain': $imgname='rain.png';
      break;
      default : $imgname='clear_sky.png';
      break;

      }

      $t_min = floatval($value->temperature['min']);
      $temp_min  = number_format($t_min,0).'&#176;'.$temp_unit;
      //------------------PDF--------------------
      if($pdf){
      $weather_data.= '<td width="20%" style="text-align:center;"><span>'.$from.'</span><img src="'.SITE.'images/weather-icons/default/'.$imgname.'" /><br /><span style="color:#1C72B4;">'.$temp_max.'</span><br />'.$temp_min.'<br /></td>';
      }else{
      $weather_data.= '<td width="20%"><span>'.$from.'</span><img src="'.SITE.'images/weather-icons/default/'.$imgname.'" /><br /><b style="color:#1C72B4;">'.$temp_max.'</b><br />'.$temp_min.'<br /></td>';
      }
      $day1=$from;
      $i=0;
      }

      }
      $weather_data.= '             </tr>
      </table>
      </td>
      </tr>
      </table>';
      return $weather_data;
      }

      } */

    /*     * ************************************************************ WEATHER ********************************************************************************* */

    function getAccuWeatherLocation() {
        $location = array();
        // search by lat and lng
        //$_sll = $this->trip_location_to_latlng;
        $trip_location_to_name = $this->trip_location_to;
        $trip_location_to_latlng = $this->trip_location_to_latlng;
        if ($this->trip_location_waypoint_latlng != '') {
            $trip_location_to_latlng = $this->trip_location_waypoint_latlng;
        }
        if ($this->trip_transport == 'plane') {
            if ($this->trip_location_from_latlng_drivingportion) {
                $trip_location_to_name = $this->trip_location_to_drivingportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_drivingportion;
            }
            if ($this->trip_location_from_latlng_trainportion) {
                $trip_location_to_name = $this->trip_location_to_trainportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_trainportion;
            }
        }
        if ($this->trip_transport == 'vehicle') {
            if ($this->trip_location_from_latlng_flightportion) {
                $trip_location_to_name = $this->trip_location_to_flightportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_flightportion;
            }
            if ($this->trip_location_from_latlng_trainportion) {
                $trip_location_to_name = $this->trip_location_to_trainportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_trainportion;
                $trip_has_train = true;
            }
        }
        if ($this->trip_transport == 'train') {
            if ($this->trip_location_from_latlng_flightportion) {
                $trip_location_to_name = $this->trip_location_to_flightportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_flightportion;
            }
            if ($this->trip_location_from_latlng_drivingportion) {
                $trip_location_to_name = $this->trip_location_to_drivingportion;
                $trip_location_to_latlng = $this->trip_location_to_latlng_drivingportion;
            }
        }
        $_sll = $trip_location_to_latlng;
        $_sll = str_replace('(', '', $_sll);
        $_sll = str_replace(')', '', $_sll);
        $search = urlencode($_sll);
        $json = file_get_contents("http://dataservice.accuweather.com/locations/v1/search?q=" . $search . "&apikey=gFpAEASB1wb6HF5qfhsyA5mClpOhIBIb");
        $accuweatherlocation = json_decode($json, true);
        if ($accuweatherlocation) {
            $location[0] = $accuweatherlocation[0]['Key']; //'14-349727_1_AL'; 
            $location[1] = $accuweatherlocation[0]['EnglishName']; //'New York'; 
            $location[2] = $accuweatherlocation[0]['Country']['EnglishName'];
            return $location;
        } else { // search by name //$search = $this->trip_location_to;			   
            $search = $trip_location_to_name;
            $search = urlencode($search);
            $json = file_get_contents("http://dataservice.accuweather.com/locations/v1/search?q=" . $search . "&apikey=gFpAEASB1wb6HF5qfhsyA5mClpOhIBIb");
            $accuweatherlocation = json_decode($json, true);
            if ($accuweatherlocation) {
                $location[0] = $accuweatherlocation[0]['Key']; //'14-349727_1_AL'; 
                $location[1] = $accuweatherlocation[0]['EnglishName']; //'New York'; 
                $location[2] = $accuweatherlocation[0]['Country']['EnglishName'];
                return $location;
            }
        }
        return 0;
    }

    function getAccuWeatherCurrent($location, $sunrise, $sunset, $pdf = 0) {
        global $userdata;
        $data = '';
        $json = file_get_contents("http://dataservice.accuweather.com/currentconditions/v1/" . $location[0] . "?apikey=gFpAEASB1wb6HF5qfhsyA5mClpOhIBIb&details=true");
        $currentweather = json_decode($json, true);
        if ($currentweather) {
            $_location = $location[1] . ', ' . $location[2];
            $clouds = $currentweather[0]['WeatherText'];
            $wind = $currentweather[0]['Wind']['Direction']['English'] . ', ' . ($userdata['scale'] == 'imperial' ? $currentweather[0]['Wind']['Speed']['Imperial']['Value'] : $currentweather[0]['Wind']['Speed']['Metric']['Value']) . ' ' . ($userdata['scale'] == 'imperial' ? $currentweather[0]['Wind']['Speed']['Imperial']['Unit'] : $currentweather[0]['Wind']['Speed']['Metric']['Unit']);
            $Humidity = $currentweather[0]['RelativeHumidity'] . ' %';
            $date_from = str_replace('T', ' ', $currentweather[0]['LocalObservationDateTime']);
            $from_header = date('l, d.m.Y', strtotime($date_from));
            $temp_cur = $currentweather[0]['Temperature']['Metric']['Value'] . '&#176;' . $currentweather[0]['Temperature']['Metric']['Unit'];
            if ($userdata['scale'] == 'imperial')
                $temp_cur = $currentweather[0]['Temperature']['Imperial']['Value'] . '&#176;' . $currentweather[0]['Temperature']['Imperial']['Unit'];
            $imgname = $currentweather[0]['WeatherIcon'] . '.png';

//            if ($pdf) {
//                $data = '<tr>
//                                  <td colspan="5"><span style="font-size:1.5em; color:#333">' . $_location . '</span><br /><span style="color:#999;">' . $from_header . '</span></td>
//                                </tr>
//                                <tr>
//                                    <td colspan="5">
//                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
//                                          <tr style="text-align:left">
//                                             <td width="20%"><img src="' . SITE . 'images/weather-icons/acc/' . $imgname . '"  style="width:70px;" /></td>
//                                             <td width="80%"><span style="font-size:2.25em; color:#1C72B4;">' . $temp_cur . '</span></td>
//                                          </tr>
//                                        </table> 
//                                        <span class="date4"><span style="color:#999;">Current:</span> ' . $clouds . '</span><br />
//                                        <span class="date4"><span style="color:#999;">Wind:</span> ' . $wind . '</span><br />
//                                        <span class="date4"><span style="color:#999;">Humidity:</span> ' . $Humidity . '</span><br />
//                                        <span class="date4"><span style="color:#999;">Sunrise:</span> ' . $sunrise . ' <span style="color:#999;">Sunset:</span> ' . $sunset . '</span>
//                                    </td>
//                                </tr>';
            $data = '<div  style="margin-top: 3%;margin-right: 2px; font-family:Montserrat-Regular ;">
                <div align="center" style="background-color: #0f6fc6;color: white;border: 0;padding-top:10px;">
                    <b style="font-size: 25px;">' . $_location . '</b><br>' . $from_header . '
                </div>

                <table style="width: 100%;background-color: #0f6fc6;padding: 5px;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:10px;">
                            <h1 style="color: #f3a02e;font-family:Montserrat-Regular">' . $temp_cur . '</h1>
                            <div style="color: white;">current: <b>' . $clouds . '</b></div>
                            <div style="color: white;">Wind: <b>' . $wind . '</b></div>
                            <div style="color: white;">Humidity: <b> ' . $Humidity . '</b></div>
                        </td>
                        <td align="right" style="padding:10px;">
                            <img height="100px" width="100px" class="img-responsive" src="' . SITE . 'images/weather-icons/acc/' . $imgname . '">
                        </td>
                    </tr>
                </table>
                <div  style="background-color: #f3a02e;padding: 5px;border-top: 1px solid rgba(0,0,0,.125);">
                    <div style="margin: 10px 0 20px 0;color: white;font-family:Montserrat-Regular;" align="center">Sunrise: <b>' . $sunrise . ' </b> &nbsp;&nbsp;Sunset: <b>' . $sunset . '</b></div>
                </div>
            </div>';
//            } else {
//                $data = '<tr>
//                                  <td colspan="5"><span class="dest1">' . $_location . '</span><br /><span class="date1">' . $from_header . '</span></td>
//                                </tr>
//                                <tr>
//                                    <td colspan="5">
//                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="date2" >
//                                          <tr valign="middle">
//                                             <td valign="middle" width="40%"><img src="' . SITE . 'images/weather-icons/acc/' . $imgname . '" /></td>
//                                             <td width="60%">' . $temp_cur . '</td>
//                                          </tr>
//                                        </table>  
//                                        <span class="date4"><b>Current:</b> ' . $clouds . '</span><br />
//                                        <span class="date4"><b>Wind:</b> ' . $wind . '</span><br />
//                                        <span class="date4"><b>Humidity:</b> ' . $Humidity . '</span><br />
//                                        <span class="date4"><b>Sunrise:</b> ' . $sunrise . ' <b>Sunset:</b> ' . $sunset . '</span>
//                                    </td>
//                                </tr>';
//            }
        }
        return $data;
    }

    function getAccuWeatherFilters($pdf = 0) {
        $tmp = $weather_data = $weather_current = $accuweather = '';
        global $userdata;

        $metric = 'true';
        if ($userdata['scale'] == 'imperial')
            $metric = 'false';

        $locationdata = $this->getAccuWeatherLocation();
        if ($locationdata) {
            $json = file_get_contents("http://dataservice.accuweather.com/forecasts/v1/daily/5day/" . $locationdata[0] . "?apikey=gFpAEASB1wb6HF5qfhsyA5mClpOhIBIb&details=true&metric=" . $metric);
            $accuweather = json_decode($json, true);
            $font_size ='font-size:12px';
            if ($accuweather) {
//                $weather_data.= '<table style="width: 100%;margin-top: 15px;">';
//                if ($pdf)
//                    $weather_data.= '<table width="350" border="0" cellpadding="0" cellspacing="0" align="center">';
//                else
//                    $weather_data.= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
                $location = $locationdata[1] . ', ' . $locationdata[2];
                $sunrise = explode('T', $accuweather['DailyForecasts'][0]['Sun']['Rise']);
                $sunrise = explode(':', $sunrise[1]);
                $sunrise = $sunrise[0] . ':' . $sunrise[1];
                $sunset = explode('T', $accuweather['DailyForecasts'][0]['Sun']['Set']);
                $sunset = explode(':', $sunset[1]);
                $sunset = $sunset[0] . ':' . $sunset[1];
                //$wind = $accuweather['DailyForecasts'][0]['Day']['Wind']['Direction']['English'].', '.$accuweather['DailyForecasts'][0]['Day']['Wind']['Speed']['Value'].' '.$accuweather['DailyForecasts'][0]['Day']['Wind']['Speed']['Unit'];
                //$Humidity = $value->humidity['value'].' '.$value->humidity['unit'];
                //$date_from = str_replace('T',' ',$accuweather['DailyForecasts'][0]['Date']);
                //$from_header  = date('l, d.m.Y',strtotime($date_from));
                // current weather
                $weather_data .= $this->getAccuWeatherCurrent($locationdata, $sunrise, $sunset, $pdf);
                $weather_data.= '<table style="width: 100%;margin-top: 15px;" cellspacing="0"><tr>';
                // 5 days weather
//                if ($pdf)
//                    $weather_data .= '<tr>
//                                        <td>
//                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
//                                            <tr><td colspan="5">&nbsp;</td></tr>
//                                            <tr><td colspan="5"><hr /></td></tr>
//                                            <tr>';
//                else
//                    $weather_data .= '<tr>
//                                       <td>
//                                       <table width="100%" border="0" cellpadding="0" cellspacing="0" class="date3" style="text-align:center;">
//                                            <tr><td colspan="5"><hr /></td></tr>
//                                            <tr>';
                for ($i = 0; $i <= 4; $i++) {
                    $temp_max = number_format($accuweather['DailyForecasts'][$i]['Temperature']['Maximum']['Value'], 0) . '&#176;' . $accuweather['DailyForecasts'][$i]['Temperature']['Maximum']['Unit'];
                    $temp_min = number_format($accuweather['DailyForecasts'][$i]['Temperature']['Minimum']['Value'], 0) . '&#176;' . $accuweather['DailyForecasts'][$i]['Temperature']['Minimum']['Unit'];
                    $date_from = str_replace('T', ' ', $accuweather['DailyForecasts'][$i]['Date']);
                    $from = date('D', strtotime($date_from));
                    $imgname = $accuweather['DailyForecasts'][$i]['Day']['Icon'] . '.png';
                    $bgColor='#0836c1';
                    switch ($i){
                        case 0:
                            $bgColor='#0836c1';
                            break;
                        case 1:
                            $bgColor='#1d63dc';
                            break;
                        case 2:
                            $bgColor='#2f7ae5';
                            break;
                        case 3:
                            $bgColor='#3e88ef';
                            break;
                        case 4:
                            $bgColor='#439cef';
                            break;
                    }
                    
                    $weather_data .='<td style="'.$font_size.';background-color:' . $bgColor . ';width: 20%;height:250px;" align="center">
                        <div style="padding: 10px;">
                            <h3 style="'.$font_size.';color: white;text-align: center;font-family:Montserrat-Medium" class="card-title">' . $from . '</h3>
                            <div style="text-align: center;margin-top: 50px;">
                                <img src="' . SITE . 'images/weather-icons/acc/' . $imgname . '" alt="">
                            </div>
                            <div style="margin-top: 50px;text-align: center;">
                                <h4 style="'.$font_size.';color: #f3a02e;font-family:Montserrat-Medium">' . $temp_max . '</h4>
                                <h4 style="'.$font_size.';color: white;font-family:Montserrat-Medium">' . $temp_min . '</h4>
                            </div>
                        </div>
                    </td>';
//                    if ($pdf) {
//                        $weather_data .= '<td width="20%" style="text-align:center;"><span>' . $from . '</span><img src="' . SITE . 'images/weather-icons/acc/' . $imgname . '" /><br /><span style="color:#1C72B4;">' . $temp_max . '</span><br />' . $temp_min . '<br /></td>';
//                    } else {
//                        $weather_data .= '<td width="20%"><span>' . $from . '</span><img src="' . SITE . 'images/weather-icons/acc/' . $imgname . '" /><br /><b style="color:#1C72B4;">' . $temp_max . '</b><br />' . $temp_min . '<br /></td>';
//                    }
                }

                $weather_data .= '</tr>
                                    </table>';
            }
        } else { // No locations found.
            //$weather_data = $locationdata.'Working ......';
            $weather_data = 'No locations found.';
        }

        return $weather_data;
    }

    /*     * ************************************************************ END WEATHER ********************************************************************************* */

    function edit_data_pdf($id) {
        global $dbh;

        $query = "UPDATE " . $this->table . " SET pdf_generated = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, 1, PDO::PARAM_STR);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp)
            $this->error = 'error_fail';
    }

}

?>