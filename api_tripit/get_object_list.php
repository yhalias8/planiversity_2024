<?php

// Copyright 2008-2018 Concur Technologies, Inc.
//
// Licensed under the Apache License, Version 2.0 (the "License"); you may
// not use this file except in compliance with the License. You may obtain
// a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
// WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
// License for the specific language governing permissions and limitations
// under the License.

include_once('tripit.php');
include_once('common.php');
include("../class/class.TripitObject.php");
include("../class/class.TripitItinerary.php");

function saveObject($tripit, $item)
{
    $trip_object_obj = new TripitObject();
    $trip_object_obj->edit_data($item);
    $trip_object = $trip_object_obj->get_data_by_tripit_id($item['id']);
    $trip_itinerary_obj = new TripitItinerary();
    $trip_itinerary = $trip_itinerary_obj->get_data_by_trip_id($trip_object->trip_id);
    if (!$trip_itinerary) {
        $json = <<<EOT
{
    "Trip":
    {
        "start_date":"2020-12-09",
        "end_date":"2020-12-27",
        "primary_location":"New York, NY"
    }
}
EOT;
        $res = $tripit->create($json, 'json');
        /*echo "<pre>";
        print_r($res);
        echo "</pre>";
        die;*/
        $trip_id = $res["Trip"]["id"];
        $json = <<<EOF
{
"CarObject": {
"id": "1112247915",
"trip_id": "$trip_id",
"relative_url": "/reservation/show/id/1112247915",
"display_name": "Alamo Car Rental",
"is_display_name_auto_generated": "false",
"last_modified": "1577294194",
"booking_site_conf_num": "7503200353310",
"booking_site_name": "Expedia",
"booking_site_phone": "1-877-261-3523",
"booking_site_url": "http://www.expedia.com/",
"supplier_conf_num": "1863563972",
"supplier_name": "Alamo",
"is_purchased": "true",
"total_cost": "$412.90",
"is_tripit_booking": "false",
"StartDateTime": {
"date": "2020-01-03",
"time": "13:30:00",
"timezone": "Europe/Berlin",
"utc_offset": "+01:00"
},
"EndDateTime": {
"date": "2020-01-20",
"time": "10:30:00",
"timezone": "Europe/Berlin",
"utc_offset": "+01:00"
},
"StartLocationAddress": {
"address": "Munich (Franz Josef Strauss Intl.) WHEN YOU ARRIVE This location",
"city": "Freising",
"state": "BY",
"zip": "85356",
"country": "DE",
"latitude": "48.353662",
"longitude": "11.775028"
},
"EndLocationAddress": {
"address": "Munich (Franz Josef Strauss",
"city": "Munich",
"country": "DE",
"latitude": "48.353005",
"longitude": "11.790143"
},
"Driver": {
"first_name": "ERIC",
"middle_name": "ALLEN -------------------------",
"last_name": "Price",
"frequent_traveler_supplier": "Alamo"
},
"start_location_name": "Munich (Franz Josef Strauss Intl.) WHEN YOU ARRIVE This location",
"end_location_name": "Munich (Franz Josef Strauss",
"car_description": "Air conditioning Manual transmission",
"car_type": "Compact Vw Golf or similar"
}
}
EOF;
        /*echo $json;
        die;*/
        $res = $tripit->create($json, 'json');
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        die;
        $trip_itinerary_obj->put_data($res);
    }
}

$tripit = new TripIt($oauth_credential);

$filter = [];
$filter["traveler"] = "false";
$filter['format'] = 'json';
$unfiled_items = $tripit->list_object($filter);

/*echo json_encode($unfiled_items);
die;*/

/*echo "<pre>";
print_r($unfiled_items);
echo "</pre>";
die;*/

if (!empty($unfiled_items['CarObject'][0])) {
    foreach ($unfiled_items['CarObject'] as $item) {
        saveObject($tripit, $item);
    }
} else {
    $item = $unfiled_items['CarObject'];
    saveObject($tripit, $item);
}