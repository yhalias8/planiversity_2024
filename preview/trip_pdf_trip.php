<?php

use setasign\Fpdi\Tcpdf\Fpdi;

include_once("../config.ini.php");
include_once("../config.ini.curl.php");
include("../class/class.Plan.php");
include("../class/class.TripPlan.php");


ini_set('memory_limit', '1280M');
ini_set('precision', 17);
ini_set('serialize_precision', -1);

$uid 	= $_GET['uid'];
$idtrip = $_GET['idtrip'];

$mapBoxKey 	= "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";
$key 		= 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';

$text 		= array("First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh");


$plan = new Plan();
$trip = new TripPlan();

$idtrip = filter_var($idtrip, FILTER_SANITIZE_STRING);

if (empty($idtrip)) {
	header("Location:" . SITE . "trip/how-are-you-traveling");
}

$trip->get_data($idtrip);

$currentPage = 0;

require_once('../tcpdf/tcpdf.php');
require_once('../FPDI/src/autoload.php');

class top_bar
{
	public $title;
	public $content;
	public $subtext;
	public function __construct($title, $content, $subtext = "")
	{
		$this->title = $title;
		$this->content = $content;
		$this->subtext = $subtext;
	}
	public function html_content()
	{
		$html = '<style>
		.header{
		}
		.header td{
			color: white;
		}
		</style>
		<table class="header" width="100%">
		<tr nobr="true" width="100%">
		<td width="30%" style="text-align:left">' . $this->title . '</td>
		<td width="70%" style="text-align:right">' . $this->content . '</td>
		<span style = "color: white;">' . $this->subtext . '</span>
		</tr>
		</table>
		';
		return $html;
	}
}



$fontname_regular = TCPDF_FONTS::addTTFfont('../tcpdf/examples/images/custom_regular.ttf', 'TrueTypeUnicode', '', 96);
$fontname_bold = TCPDF_FONTS::addTTFfont('../tcpdf/examples/images/custom_bold.ttf', 'TrueTypeUnicode', '', 96);

$pdf = new FPDI('P', 'mm', 'A4'); //FPDI extends TCPDF


$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(10, 12, 10);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once(dirname(__FILE__) . '/lang/eng.php');
	$pdf->setLanguageArray($l);
}
$pdf->SetFont($fontname_regular, '', 14, '', false);
$pdf->SetFont($fontname_bold, '', 14, '', false);
//////////////////////////////////////cover page start/////////////////////////////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false, 0);
$img_file = K_PATH_IMAGES . 'background.png';
$title_img1 = K_PATH_IMAGES . 'title_img1.png';
$botoom_right = K_PATH_IMAGES . 'botoom_right.png';
$logo_img = K_PATH_IMAGES . 'header-master-logo.png';
$pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($title_img1, 0, 0, 100, 80, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($logo_img, 70, 70, 70, 47, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($botoom_right, 150, 240, 60, 60, '', '', '', false, 300, '', false, false, 0);
$html = "
<style>
h1{
	text-align:center;
	color:white;
	font-size: 50px;
	font-family:'.$fontname_bold'.;
	font-weight:bold;
}
</style>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<h1><b> $trip->itinerary_type</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');
$currentPage = $currentPage + 1;
//////////////////////////////////////cover page end/////////////////////////////////////////////////



///////////////////////////////////////Schedule start//////////////////////////

$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$i = 1;
//$section_2 = new top_bar('Schedule Itinerary', 'The travel plan of tomorrow done right today');
//$html = $section_2->html_content();


if ($tmp && $stmt->rowCount() > 0) { //$mpdf->WriteHTML($html);  
	$pdf->AddPage('P', 'A4');
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$calendar_image = K_PATH_IMAGES . 'calendar.png';
	$section_2 = new top_bar('Schedule Itinerary', 'The travel plan of tomorrow done right today');
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->SetMargins(10, 12, 10);
	$html = "";
	$html = $section_2->html_content();
	$html .= '<div>
		<br><br><br><br><br><br>
		<table class="body">
		<tr>
			<td width="60%">
				<p style="color:#0D256E; font-size:25px; font-weight:bold;">Event Title in Here</p>
			</td>
			<td colspan="2" width="40%">
				<p style="color:#0688E9; font-size:25px; font-weight:bold;">Event Date & Time</p>			   		
			</td>
		</tr>
		<p style="width:90%; background-color:#67758D; font-size:0.5px">e</p>
		<div></div>
	';
	// $stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
	// $stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
	// $tmp = $stmt->execute();

	$all_timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
	foreach ($all_timelines as $timeline) {

		$html .= '
			<tr>
				<td width="55%">
					<p style="color:#67758D; font-size:16px;">' . $timeline->title . '</p>
				</td>
				<td width="8%">					
   					<img src="' . $calendar_image . '" width="42px" height="40px">	
				</td>
				<td width="37%">
					<p style="color:#0688E9; font-size:16px; line-height:2.0;">' . date('d F Y h:i a', strtotime($timeline->date)) . '</p>
				</td>
			</tr>
			<div></div>
			<p style="width:90%; background-color:#67758D; font-size:0.5px">e</p>
			<div></div>';
	}

	$html .= '</table></div>';
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);

	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
	///////////////////////////////////////Schedule end//////////////////////////	
}



///////////////////////////////////////notes start//////////////////////////
$stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$i = 1;
$section_2 = new top_bar('Trip Notes', 'The travel plan of tomorrow done right today');
$html = $section_2->html_content();
if ($tmp && $stmt->rowCount() > 0) {
	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$calendar_image = K_PATH_IMAGES . 'calendar.png';
	//$section_2 = new top_bar('Trip Notes', 'The travel plan of tomorrow done right today');
	$note_number_image = K_PATH_IMAGES . 'note_number.png';
	$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$html .= '<div>
		<br><br><br><br><br><br>
	';
	$notes = $stmt->fetchAll(PDO::FETCH_OBJ);
	$len = count($notes);
	$i = 0;
	foreach ($notes as $note) {
		$i++;
		if ($i != $len) { // list of end
			$html .= '
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="20%" style="">
							<span>&nbsp;</span><img src="../images/note_number/' . $i . '.png" style="width:40px; height:40px;" >
						</td>
						<td rowspan="2" style="height:100%; color:#67758D" width="80%">
							<br>' . $note->text . '
						</td>
					</tr>
					<tr style="text-align:center;" cellpadding="3">
						<td style="border-right:2px dashed #868686;width:25px;"></td>
					</tr>
				</table>
			';
		} else {
			$html .= '
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="20%" style="">
							<span>&nbsp;</span><img src="../images/note_number/' . $i . '.png" style="width:40px; height:40px;" >
						</td>
						<td rowspan="2" style="height:100%; color:#67758D" width="80%">
							<br>' . $note->text . '
						</td>
					</tr>
					<tr style="text-align:center;" cellpadding="3">
						<td style="border-right:2px dashed #fff;width:25px;"></td>
					</tr>
				</table>
			';
		}
	}
	$html .= '</div>';


	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}

///////////////////////////////////////notes end//////////////////////////





///////////////////////////////////////Map One over the world start////////////////////////
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

	if (empty($trip->location_multi_waypoint)) {
		$multi_waypoint = [];
	} else {
		$multi_waypoint = json_decode($trip->location_multi_waypoint);
	}

	$make_text = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$i = 1;
	$mark_position_text = "";

	if ($multi_waypoint) {
		$multi_data = [];
		foreach ($multi_waypoint as $address) {
			if ($address != "") {
				$addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&key=' . $key);
				$multi_data = json_decode($addressLocation);
				$latitudeFrom = $multi_data->results[0]->geometry->location->lat;
				$longitudeFrom = $multi_data->results[0]->geometry->location->lng;
				$mark_position_text .= ",pin-s-$make_text[$i]+007acc($longitudeFrom,$latitudeFrom)";
				$i++;
			}
		}
	}

	$array_list = [];
	if (!empty(json_decode($trip->location_multi_waypoint_latlng))) {
		$array_hold = json_decode($trip->location_multi_waypoint_latlng);

		foreach ($array_hold as $f => $val) {
			$multi_lon = trim(explode(',', $val->location_multi_waypoint_latlng)[1]);
			$multi_lat = trim(explode(',', $val->location_multi_waypoint_latlng)[0]);
			$d_array[] = [(float) $multi_lon, (float) $multi_lat];
		}
		$location_multi_waypoint_latlng = $d_array;
	} else {
		$location_multi_waypoint_latlng = $array_list;
	}

	if (!empty(json_decode($trip->trip_via_waypoints))) {
		$array_hold = json_decode($trip->trip_via_waypoints);

		foreach ($array_hold as $f => $val) {
			$data_array[] = [(float) $val->lng, (float) $val->lat];
		}
		$via_waypoints = $data_array;
	} else {
		$via_waypoints = $array_list;
	}


	$mSlice = array_merge($location_multi_waypoint_latlng, $via_waypoints);
	rsort($mSlice);
	$slice = "";
	$clength = count($mSlice);
	for ($x = 0; $x < $clength; $x++) {
		$slice .= "%3B" . $mSlice[$x][0] . "%2C" . $mSlice[$x][1];
	}

	$waypoint = "$origin_lon%2C$origin_lat$slice%3B$destination_lon%2C$destination_lat";

	if (!$trip->location_full_path || !empty(json_decode($trip->location_full_path))) {
		//if (!$trip->location_full_path) {
		$distance = "";
		$duration = "";

		$drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$waypoint?alternatives=true&geometries=geojson&steps=false&access_token=$mapBoxKey";

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

	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';

	$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . $full_route . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";

	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Route by Vehicle', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$html .= '
			<br><br><br><br>
			<img src="' . $route_map . '" width ="740px" height="920px" />
		';
	$pdf->writeHTML($html, true, false, true, false, '');
	// }
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
	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . $full_route . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Route by TRAIN', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$html .= '
		<br><br><br><br>
		<img src="' . $route_map . '" width ="740px" height="920px" />
	';
	$pdf->writeHTML($html, true, false, true, false, '');
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

	$array_list = [];
	if ($trip->location_multi_waypoint_latlng) {
		$array_hold = json_decode($trip->location_multi_waypoint_latlng);
		$location_multi_waypoint_latlng = $array_hold;
	} else {
		$location_multi_waypoint_latlng = $array_list;
	}


	$data_array = [];
	$data_array[] = [(float) $origin_lon, (float) $origin_lat];
	foreach ($location_multi_waypoint_latlng as $k => $val) {
		$multi_lon = trim(explode(',', $val->location_multi_waypoint_latlng)[1]);
		$multi_lat = trim(explode(',', $val->location_multi_waypoint_latlng)[0]);
		$data_array[] = [(float) $multi_lon, (float) $multi_lat];
	}
	$data_array[] = [(float) $destination_lon, (float) $destination_lat];
	$data_route = json_encode($data_array);


	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';

	$route_list = "%22coordinates%22:" . $data_route . "%7D";
	$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22," . $route_list . ",%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat)$mark_position_text,pin-s-$make_text[$i]+007acc($destination_lon,$destination_lat)/auto/520x640@2x?access_token=$mapBoxKey";

	//$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:[[$origin_lon,$origin_lat],[$destination_lon,$destination_lat]]%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat),pin-s-b+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";

	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Route by PLANE', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$html .= '
		<br><br><br><br>
		<img src="' . $route_map . '" width ="740px" height="920px" />
	';
	$pdf->writeHTML($html, true, false, true, false, '');
}
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map One over the world end/////////////////////////



///////////////////////////////////////End PDF page start////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false, 0);
//$img_file = K_PATH_IMAGES . 'background.png';
//$pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$html = "
	<style>
		h1{
			text-align:center;
			color:black;
			font-size: 50px;
			font-family:'.$fontname_bold'.;
			font-weight:bold;
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>	
	<h1><b>Thank you for choosing PLANIVERSITY!</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');
///////////////////////////////////////End PDF page end////////////////////////


ob_end_clean();
//$pdf->writeHTML($html, true, false, true, false, '');
//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');
