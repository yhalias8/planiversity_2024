<?php

class TripResource
{
    var $_id = 0;
    var $trip_id = 0;
    var $title = '';
    var $address = '';
    var $lat = '';
    var $lng = '';
    var $type = '';
    var $created_at = '';
    var $custom = 0;
    var $error = '';
    var $lastid = '';

    var $table = '';

    public function __construct($nametable = 'tripit_resources')
    {
        $this->_id = 0;
        $this->trip_id = 0;
        $this->title = '';
        $this->address = '';
        $this->lat = '';
        $this->lng = '';
        $this->custom = 0;
        $this->created_at = '';

        $this->table = $nametable;
        $this->error = '';
        $this->lastid = '';
    }

    function get_resources_from_trip($trip_id)
    {
        global $dbh;
        $this->error = '';

        if (!empty($trip_id)) {
            $query = "SELECT * FROM " . $this->table . " WHERE trip_id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
            else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
        } else
            $this->error = 'error_mising';

        return null;
    }

    function find_by_id($res_id)
    {
        global $dbh;
        $this->error = '';

        if (!empty($res_id)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $res_id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
            else {
                return $stmt->fetch(PDO::FETCH_OBJ);
            }
        } else
            $this->error = 'error_mising';

        return null;
    }

    function create($trip_id, $title, $address, $lat, $lng, $type, $custom = 0)
    {
        global $dbh;
        $this->error = '';
        if (!empty($trip_id) && !empty($title) && !empty($address) && !empty($lat) && !empty($lng) && !empty($type)) {
            $query = "INSERT INTO " . $this->table . " (trip_id,title,address,lat,lng,type,created_at,custom) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $title, PDO::PARAM_STR);
            $stmt->bindValue(3, $address, PDO::PARAM_STR);
            $stmt->bindValue(4, $lat, PDO::PARAM_STR);
            $stmt->bindValue(5, $lng, PDO::PARAM_STR);
            $stmt->bindValue(6, $type, PDO::PARAM_STR);
            $stmt->bindValue(7, date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(8, $custom, PDO::PARAM_INT);

            $tmp = $stmt->execute();
            $id = $dbh->lastInsertId();

            if (!$tmp) {
                $this->error = 'error_fail';
                return null;
            }
            ActivityLogger::log($trip_id, ActivityLogger::RESOURCES_ADDED);
            return $id;
        } else {
            $this->error = 'error_mising';
            return null;
        }
    }

    function update($id, $trip_id, $title, $address, $lat, $lng, $type, $custom = 0)
    {
        global $dbh;
        $this->error = '';

        if (!empty($id) && !empty($trip_id) && !empty($title) && !empty($lat) && !empty($lng) && !empty($type)) {
            $query = "UPDATE " . $this->table . " SET trip_id = ?, title = ?, address = ?, lat = ?, lng = ? ,type = ?,custom = ? WHERE id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $title, PDO::PARAM_STR);
            $stmt->bindValue(3, $address, PDO::PARAM_STR);
            $stmt->bindValue(4, $lat, PDO::PARAM_STR);
            $stmt->bindValue(5, $lng, PDO::PARAM_STR);
            $stmt->bindValue(6, $type, PDO::PARAM_STR);
            $stmt->bindValue(7, $custom, PDO::PARAM_INT);
            $stmt->bindValue(8, $id, PDO::PARAM_INT);

            $tmp = $stmt->execute();
            if (!$tmp)  {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($trip_id, ActivityLogger::RESOURCES_UPDATED);
            }
        } else {
            $this->error = 'error_missing';
        }
    }

    function delete($id)
    {
        global $dbh;
        $tripId = $this->getTripId($id);
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $this->error = 'error_fail';
        } else {
            ActivityLogger::log($tripId, ActivityLogger::RESOURCES_DELETED);
        }
    }

    private function getTripId($id)
    {
        global $dbh;
        $query = "select trip_id from {$this->table} where id = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function getPdfIconByType($type)
    {
        switch ($type) {
            case 'hotel':
                return 'hotel_filter_map.png';
            case 'hospital':
                return 'hospital_filtermap.png';
            case 'gas_station':
                return 'gas_filtermap.png';
            case 'taxi_stand':
                return 'airport_filtermap.png';
            case 'airport':
                return 'airport_filtermap.png';
            case 'parking':
                return 'parking_filtermap.png';
            case 'school':
                return 'library_filtermap.png';
            case 'atm':
                return 'atm_filtermap.png';
            case 'park':
                return 'parks_filtermap.png';
            case 'museum':
                return 'museum_filtermap.png';
            case 'library':
                return 'book_filtermap.png';
            case 'pharmacy':
                return 'pharmacy.png';
            case 'church':
                return 'church.png';
            case 'covid_testing_center':
                return 'hospital_filtermap.png';
            case 'ev_charging_station':
                return 'gas_filtermap.png';
            case 'shopping_mall':
                return 'shopping_mall.png';
            case 'golf_course':
                return 'golf_course.png';
            case 'restaurant':
                return 'restaurant.png';
            case 'cafe':
                return 'cafe.png';
            case 'historical site':
                return 'historical.png';
            case 'gym':
                return 'gym.png';
            case 'bus_station':
                return 'bus_station.png';
            case 'post_office':
                return 'post_office.png';
            case 'embassy':
                return 'embassy.png';
            case 'car_rental':
                return 'car_rental.png';
            case 'movie_theater':
                return 'movie_theater.png';

            default:
                return '';
        }
    }

    function getPlaceByType($type)
    {
        $typeMap = [
            "lodging" => "Hotels/Motels",
            "police" => "Polices",
            "hospital" => "Hospitals",
            "airport" => "Airports",
            "parking" => "Parking's",
            "subway_station" => "Subway Stations",
            "gas_station" => "Gas Stations",
            "taxi_stand" => "Taxi Stands",
            "university" => "Universities",
            "atm" => "Atm",
            "library" => "Libraries",
            "museum" => "Museums",
            "gym" => "Gyms",
            "post_office" => "Post Office",
            "bus_station" => "Bus Station",
            "embassy" => "Embassy",
            "car_rental" => "Theater",
            "movie_theater" => "Cinema",
            "church" => "Сhurches",
            "train_station" => "Metro Stations",
            "park" => "Parks",
            "pharmacy" => "Pharmacies",
            "covid_testing_center" => "Covid Testing Centers",
            "ev_charging_station" => "Electric Car Charging Stations",
            "shopping_mall" => "Shopping Malls",
            "golf_course" => "Golf Courses",
            "restaurant" => "Restaurants",
            "cafe" => "Cafes",
            "historical site" => "Historical Sites",
        ];

        return $typeMap[$type] ?? '';
    }
}
