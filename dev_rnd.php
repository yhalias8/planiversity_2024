<?php
//use setasign\Fpdi\Tcpdf\Fpdi;
session_start();
include_once("config.ini.php");
include("class/class.TripPlan.php");



if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/name/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$idtrip = $_GET['idtrip'];

$mapBoxKey  = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";
$key        = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';

$trip = new TripPlan();
//$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
//$userdata['id'];

$idtrip = filter_var($idtrip, FILTER_SANITIZE_STRING);
// if (empty($idtrip)) {
//  header("Location:" . SITE . "trip/how-are-you-traveling");
// }

$trip->get_data($idtrip);

//echo '<pre>';
//print_r($trip);





///////////////////////////////////////weather start//////////////////////////
if ($trip->trip_option_weather) {
    $origin_r = str_replace('(', '', $trip->trip_location_from_latlng);
    $origin_r = str_replace(')', '', $origin_r);
    $destination_r = str_replace('(', '', $trip->trip_location_to_latlng);
    $destination_r = str_replace(')', '', $destination_r);
    $destination = $destination_r;
    $locality_long_name = "";
    $url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination . "&sensor=false&key=" . $key;
    $xml = $trip->getXmlFromUrl($url);
    if ($xml->status == 'OK') {
        foreach ($xml->result[1]->address_component as $value) {
            if ($value->type == 'locality') {
                $locality_long_name = trim($value->long_name);
                $locality_short_name = $value->short_name;
            }
            if ($value->type == 'political') {
                $political_long_name = trim($value->long_name);
                $political_short_name = $value->short_name;
            }
        }
    }
    $weatherData = $trip->getWeatherInformation(1);
    if ($weatherData[1]["Code"] == "Unauthorized") {
    } else {
        $headerWeatherInfo = $weatherData[0];
        $weatherInfo = $weatherData[1];
        //$pdf->AddPage('P', 'A4');
        //$pdf->SetMargins(10, 12, 10);
        // $top_bar_image = K_PATH_IMAGES . 'top_bar.png';
        // $calendar_image = K_PATH_IMAGES . 'calendar.png';
        // $note_number_image = K_PATH_IMAGES . 'note_number.png';
        // $sunrise_image = K_PATH_IMAGES . 'sunrise.png';
        // $sunrise_bg = K_PATH_IMAGES . 'sunrise_bg.png';
        // $bottom_bg = K_PATH_IMAGES . 'bottom_bg.png';
        // $table_bg =  K_PATH_IMAGES . 'table_bg.png';
        // $cloud =  K_PATH_IMAGES . 'cloud.png';
        // $rain =  K_PATH_IMAGES . 'rain.png';
        // $sun =  K_PATH_IMAGES . 'sun.png';
        // $sun_raise =  K_PATH_IMAGES . 'sun_raise.png';
        //$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
        //$pdf->Image($sunrise_bg, 0, 100, 0, 100, '', '', '', false, 300, '', false, false, 0);
        //$pdf->Image($bottom_bg, -2, 132, 0, 300, '', '', '', false, 300, '', false, false, 0);
        //$pdf->Image($b_left1, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
        //$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
        //$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
        // for ($i = 0; $i <= 4; $i++) {
        //     $pdf->Image($table_bg, 40 + ($i * 27.1), 121, 27.5, 53, '', '', '', false, 300, '', false, false, 0);
        // }
        //$section_2 = new top_bar("Weather at $locality_long_name", 'The travel plan of tomorrow done right today');
        //$html = $section_2->html_content();
        $html_main .= '
            <div>
            <br><br><br><br><br>
                <div style="text-align:center;">
                    <img src="' . $sunrise_image . '" width="300px" height="160px" />
                    <p style="font-size:20px; color:#0D256E; text-align:center;">Sunrise: 06:35 Sunset: 20:00</p>           
                </div>      
            </div>
        ';
        // $pdf->writeHTML($html, true, false, true, false, '');
        // $pdf->SetTextColor(103, 117, 141);
        // $pdf->SetXY(10, 55);
        // $pdf->writeHTML($headerWeatherInfo[6], true, false, true, false, '');
        // $pdf->SetXY(10, 62);
        // $pdf->writeHTML($headerWeatherInfo[7], true, false, true, false, '');
        // $pdf->SetXY(10, 69);
        // $pdf->writeHTML(explode(",", $headerWeatherInfo[0])[0], true, false, true, false, '');
        // $pdf->SetXY(10, 76);
        // $pdf->writeHTML(explode(",", $headerWeatherInfo[0])[1], true, false, true, false, '');
        // $pdf->SetTextColor(243, 159, 50);
        // $pdf->SetXY(160, 55);
        // $pdf->SetFont($fontname, '', 28, '', false);
        // $pdf->writeHTML(round((float) explode('&', $headerWeatherInfo[1])[0], 0) . "ьз╕C", true, false, true, false, '');
        // $pdf->SetFont($fontname, '', 14, '', false);
        // $pdf->SetTextColor(103, 117, 141);
        // $pdf->SetXY(145, 68);
        // $pdf->writeHTML("Current: $headerWeatherInfo[2]", true, false, true, false, '');
        // $pdf->SetXY(145, 75);
        // $pdf->writeHTML("Wind:" . explode(' ', $headerWeatherInfo[3])[0] . round((float) explode(' ', $headerWeatherInfo[3])[1], 0) . explode(' ', $headerWeatherInfo[3])[2], true, false, true, false, '');
        // $pdf->SetXY(154, 82);
        // $pdf->writeHTML("Humidity: $headerWeatherInfo[4]", true, false, true, false, '');
        // $pdf->SetXY(80, 123);
        $html_main = '<div style="text-align:center;"><table><tr>';
        $html_main .= '<td width="105px"></td>';
        for ($i = 0; $i <= 4; $i++) {
            $temp_max = number_format($weatherInfo['DailyForecasts'][$i]['Temperature']['Maximum']['Value'], 0) . '&#176;' . $weatherInfo['DailyForecasts'][$i]['Temperature']['Maximum']['Unit'];
            $temp_min = number_format($weatherInfo['DailyForecasts'][$i]['Temperature']['Minimum']['Value'], 0) . '&#176;' . $weatherInfo['DailyForecasts'][$i]['Temperature']['Minimum']['Unit'];
            $date_from = str_replace('T', ' ', $weatherInfo['DailyForecasts'][$i]['Date']);
            $from = date('D', strtotime($date_from));
            $imgname = $weatherInfo['DailyForecasts'][$i]['Day']['Icon'];
            $html_main .= '<td style="color:#fff; text-align:center;" width="97px">
                            <p>' . $from . '</p>
                            <img src="./images/weather-icons/acc/' . $imgname . '.png" style="width:42.5px" height="33px" />
                            <p>' . $temp_max . '</p>
                            <p>' . $temp_min . '</p>
                        </td>';
        }
        $html_main .= '</tr></table></div>';
        // $pdf->writeHTML($html, true, false, true, false, '');
        // $pdf->SetXY(192, 285);
        // $pdf->SetTextColor(13, 37, 110);
        // $pdf->writeHTML("$currentPage", true, false, true, false, '');
        // $currentPage = $currentPage + 1;
        echo $html_main;
    }
}
///////////////////////////////////////weather end//////////////////////////




///////////////////////////////////////Map One over the world start///////
$origin_r = str_replace('(', '', $trip->trip_location_from_latlng);
$origin_r = str_replace(')', '', $origin_r);
$destination_r = str_replace('(', '', $trip->trip_location_to_latlng);
$destination_r = str_replace(')', '', $destination_r);
$destination = $destination_r;
$origin_lon = trim(explode(',', $origin_r)[1]);
$origin_lat = trim(explode(',', $origin_r)[0]);
$destination_lon = trim(explode(',', $destination)[1]);
$destination_lat = trim(explode(',', $destination)[0]);
if ($trip->trip_transport == "vehicle") {
    $full_route = "";
    $multi_waypoint = json_decode($trip->location_multi_waypoint);
    $make_text = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    $i = 1;
    $mark_position_text = "";
    foreach ($multi_waypoint as $address) {
        if ($address != "") {
            $addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&key=' . $key);
            $outputFrom = json_decode($addressLocation);
            $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
            $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
            $mark_position_text .= ",pin-s-$make_text[$i]+007acc($longitudeFrom,$latitudeFrom)";
            $i++;
        }
    }
    if (count($multi_waypoint) == 1 && $multi_waypoint[0]  == "") {
        $distance = "";
        $duration = "";
        $drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$origin_lon%2C$origin_lat%3B$destination_lon%2C$destination_lat?alternatives=true&geometries=geojson&steps=true&access_token=$mapBoxKey";
        $api = $trip->curlRequest($drivingDirectionApi);
        $drivingDirectionResult = json_decode($api, true);
        if ($drivingDirectionResult["code"] == "Ok") {
            $distance = end($drivingDirectionResult["routes"])["distance"] / 1000;
            $distance = round((float) $distance, 0);
            $duration = end($drivingDirectionResult["routes"])["duration"] / 60;
            $duration = round((float) $duration, 0);
            $full_route = json_encode($drivingDirectionResult['routes'][0]["geometry"]["coordinates"]);
        }
    } else {
        $full_route = $trip->location_full_path;
    }

    $route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . $full_route . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";


}

if ($trip->trip_transport == "train") {
    $full_route = "";
    $multi_waypoint = json_decode($trip->location_multi_waypoint);
    $make_text = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    $i = 1;
    $mark_position_text = "";
    foreach ($multi_waypoint as $address) {
        if ($address != "") {
            $addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&key=' . $key);
            $outputFrom = json_decode($addressLocation);
            $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
            $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
            $mark_position_text .= ",pin-s-$make_text[$i]+007acc($longitudeFrom,$latitudeFrom)";
            $i++;
        }
    }
    if (count($multi_waypoint) == 1 && $multi_waypoint[0]  == "") {
        $distance = "";
        $duration = "";
        $drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$origin_lon%2C$origin_lat%3B$destination_lon%2C$destination_lat?alternatives=true&geometries=geojson&steps=true&access_token=$mapBoxKey";
        $api = $trip->curlRequest($drivingDirectionApi);
        $drivingDirectionResult = json_decode($api, true);
        if ($drivingDirectionResult["code"] == "Ok") {
            $distance = end($drivingDirectionResult["routes"])["distance"] / 1000;
            $distance = round((float) $distance, 0);
            $duration = end($drivingDirectionResult["routes"])["duration"] / 60;
            $duration = round((float) $duration, 0);
            $full_route = json_encode($drivingDirectionResult['routes'][0]["geometry"]["coordinates"]);
        }
    } else {
        $full_route = $trip->location_full_path;
    }

    $route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . $full_route . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";

}

if ($trip->trip_transport == "plane") {

    $full_route = "";
    $multi_waypoint = json_decode($trip->location_multi_waypoint);
    $make_text = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    $i = 1;
    $mark_position_text = "";
    foreach ($multi_waypoint as $address) {
        if ($address != "") {
            $addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&key=' . $key);
            $outputFrom = json_decode($addressLocation);
            $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
            $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
            $mark_position_text .= ",pin-s-$make_text[$i]+007acc($longitudeFrom,$latitudeFrom)";
            $i++;
        }
    }
    if (count($multi_waypoint) == 1 && $multi_waypoint[0]  == "") {
        $distance = "";
        $duration = "";
        $drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$origin_lon%2C$origin_lat%3B$destination_lon%2C$destination_lat?alternatives=true&geometries=geojson&steps=true&access_token=$mapBoxKey";
        $api = $trip->curlRequest($drivingDirectionApi);
        $drivingDirectionResult = json_decode($api, true);
        if ($drivingDirectionResult["code"] == "Ok") {
            $distance = end($drivingDirectionResult["routes"])["distance"] / 1000;
            $distance = round((float) $distance, 0);
            $duration = end($drivingDirectionResult["routes"])["duration"] / 60;
            $duration = round((float) $duration, 0);
            $full_route = json_encode($drivingDirectionResult['routes'][0]["geometry"]["coordinates"]);
        }
    } else {
        $full_route = $trip->location_full_path;
    }
    

        $array=[];
        if ($trip->location_multi_waypoint_latlng) {            

        $multi_r = str_replace('[', '', $trip->location_multi_waypoint_latlng);
        $multi_r = str_replace(']', '', $multi_r);
        $array=json_decode($trip->location_multi_waypoint_latlng);

        $location_multi_waypoint_latlng =$array;
        } else {
            $location_multi_waypoint_latlng = $array;
        }

        $data_array;
        $data_array[]=[(double) $origin_lon, (double) $origin_lat];
        foreach ($location_multi_waypoint_latlng as $key => $value){
            $multi_lon = trim(explode(',', $value->location_multi_waypoint_latlng)[1]);
            $multi_lat = trim(explode(',', $value->location_multi_waypoint_latlng)[0]);
            $data_array[]=[(double) $multi_lon,(double) $multi_lat];
        }
        $data_array[]=[(double) $destination_lon,(double) $destination_lat];
        $data_route=json_encode($data_array);



    $route_list ="%22coordinates%22:" . $data_route . "%7D";

    $route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,".$route_list.",%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/520x640@2x?access_token=$mapBoxKey";




        $html="";

        $html .= '        
        <img src="' . $route_map . '" width ="740px" height="920px" />
    ';

    echo $html;



}

///////////////////////////////////////Map One over the world end/////////////////////////




// ///////////////////////////////////////End PDF page start////////////////////////
// $pdf->AddPage('P', 'A4');
// $pdf->SetAutoPageBreak(false, 0);
// $img_file = K_PATH_IMAGES . 'background.png';
// $pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
// $html = "
//     <style>
//         h1{
//             text-align:center;
//             color:white;
//             font-size: 50px;
//             font-family:'$fontname';
//             font-weight:bold;
//         }
//     </style>
//     <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
//     <h1><b>Thank you for choosing PLANIVERSITY!</b></h1>";
// $pdf->writeHTML($html, true, false, true, false, '');

