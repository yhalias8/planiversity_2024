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
$title 	= $_GET['title'];
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


//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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



if ($trip->cover_image) {

	$parmas = $trip->cover_image_type ? "" : "&q=80&w=400";
	$img_file = $trip->cover_image_url . $parmas;

	$title_img1 = K_PATH_IMAGES . 'botttom_left_placeholder.png';
	$transp = K_PATH_IMAGES . 'transparent_background.png';
	$top_right = K_PATH_IMAGES . 'top_right_placeholder.png';
	$logo_img = K_PATH_IMAGES . 'header-master-logo.png';


	$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($transp, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($top_right, 174, 0, 40, 40, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($title_img1, 0, 230, 70, 70, '', '', '', false, 300, '', false, false, 0);
	$html = "
<style>
.heading{
	padding-left:100px
}
p{	
	color:white;
	font-size: 18px;
	font-family:'.$fontname_regular'.;
	line-height:0px;
	padding-left:100px
}
h1{	
	color:white;
	font-size: 60px;
	font-family:'.$fontname_bold'.;
	font-weight:bold;
}

</style>
<div class='heading'>
<br><br><br>
<p>       Your Planiversity Document</p>
<h1><b> $title</b></h1>
<div>
";
} else {


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
		font-family:'.$fontname'.;
		font-weight:bold;
	}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<h1><b> $title</b></h1>
	";
}


$pdf->writeHTML($html, true, false, true, false, '');
$currentPage = $currentPage + 1;

/////////////////////////////////////cover page end/////////////////////////////////////////////////



//////////////////////////////////////Itinerary slide with all stops info start//////////////////////
$pdf->AddPage('P', 'A4');
$latLng_timezone = trim($trip->trip_location_from_latlng, '()');
$from_timestamp = implode('', explode(':', $trip->trip_location_datel_deptime));
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$url = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $latLng_timezone . "&timestamp=" . $from_timestamp . "&key=" . $key;
$url = preg_replace("/ /", "%20", $url);
$result = $trip->curlRequest($url);
$googleDirection = json_decode($result, true);
$test_timezone_from = ($googleDirection['timeZoneName']);
$words = explode(" ", $test_timezone_from);
$acronym_from = "";

foreach ($words as $w) {
	$acronym_from .= $w[0];
}
$latLng_timezone_to = trim($trip->trip_location_to_latlng, '()');
$from_timestamp_to = implode('', explode(':', $trip->trip_location_datel_arrtime));
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$url = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $latLng_timezone_to . "&timestamp=" . $from_timestamp_to . "&key=" . $key;
$url = preg_replace("/ /", "%20", $url);
$result = $trip->curlRequest($url);
$googleDirection = json_decode($result, true);
$test_timezone_to = ($googleDirection['timeZoneName']);
$words = explode(" ", $test_timezone_to);
$acronym_to = "";
foreach ($words as $w) {
	$acronym_to .= $w[0];
}
$hotel = '';
$rental = '';
$departure_information = '';
$retrun_information = '';
$flightportion = '';
$carportion = '';
$trainportion = '';


$transport = "Flight";
if ($trip->trip_transport == "vehicle") {
	$transport = "Drive";
} else if ($trip->trip_transport == "train") {
	$transport = "Train";
}

$watermark = K_PATH_IMAGES . 'watermark.png';

$event_layout = K_PATH_IMAGES . 'event.png';
$hotel_layout = K_PATH_IMAGES . 'hotel.png';
$car_layout = K_PATH_IMAGES . 'car.png';
$flight_portion = K_PATH_IMAGES . 'flight_portion.png';
$vehicle_portion = K_PATH_IMAGES . 'vehicle_portion.png';
$train_portion = K_PATH_IMAGES . 'train_portion.png';


$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
$flight_image = K_PATH_IMAGES . 'flight.png';
$car_image = K_PATH_IMAGES . 'car.png';
$train_image = K_PATH_IMAGES . 'train.png';
//$hotel_image = K_PATH_IMAGES . 'hotel.png';
$dot_image = K_PATH_IMAGES . 'dot_bar.png';
$dot_image1 = K_PATH_IMAGES . 'dot_bar1.png';
$b_left = K_PATH_IMAGES . 'bottom_left.png';
$b_left1 = K_PATH_IMAGES . 'bottom_left1.png';
$b_left_text = K_PATH_IMAGES . 'bottom_left_text.png';
$b_right = K_PATH_IMAGES . 'bottom_right_img.png';
$dark_back = K_PATH_IMAGES . 'dark_back.png';
$left_card = K_PATH_IMAGES . 'left_card.png';
$right_card = K_PATH_IMAGES . 'right_card.png';
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Your Itinerary', 'The travel plan of tomorrow done right today');
$html = $section_2->html_content();



if ($trip->trip_hotel_address != NULL || $trip->trip_hotel_address != '') {

	$checkin_date = $trip->trip_hotel_date_checkin ? date('F d, Y', strtotime($trip->trip_hotel_date_checkin)) : null;
	$checkout_date = $trip->trip_hotel_date_checkout ? date('F d, Y', strtotime($trip->trip_hotel_date_checkout)) : null;

	$hotel .= '

	<table>
				<tr>
					<td width="45%">						
						<img src="' . $hotel_layout . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Hotel Name :</span> <span class="pad-left item_details">' . $trip->trip_hotel_name . '</span></th>
		    </tr>			

			<tr>
			<th colspan="2"><span class="title_label">Hotel Address :</span> <span class="pad-left item_details">' . $trip->trip_hotel_address . '</span></th>		
			</tr>
			
			<tr>
			<td><span class="title_label">Check-in :</span> <span class="pad-left item_details">' . $checkin_date . '</span></td>
			<td><span class="title_label">Check-out :</span> <span class="pad-left item_details">' . $checkout_date . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>
	
		';
}
if ($trip->trip_rental_agency_address != NULL || $trip->trip_rental_agency_address != '') {

	$pickup_date = $trip->trip_rental_date_pick ? date('F d, Y', strtotime($trip->trip_rental_date_pick)) : null;
	$dropoff_date = $trip->trip_rental_date_drop ? date('F d, Y', strtotime($trip->trip_rental_date_drop)) : null;

	$rental .= '
	
	<table>
				<tr>
					<td width="45%">						
						<img src="' . $car_layout . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Agency Name :</span> <span class="pad-left item_details">' . $trip->trip_rental_agency . '</span></th>
		    </tr>
			

			<tr>
			<th colspan="2"><span class="title_label">Address :</span> <span class="pad-left item_details">' . $trip->trip_rental_agency_address . '</span></th>	</tr>
			
			<tr>
			<td><span class="title_label">Pick-up :</span> <span class="pad-left item_details">' . $pickup_date . '</span></td>
			<td><span class="title_label">Drop-off :</span> <span class="pad-left item_details">' . $dropoff_date . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>
	';
}
if (($trip->trip_location_from != NULL || $trip->trip_location_from != '') && ($trip->trip_location_to != NULL || $trip->trip_location_to != '')) {
	if (($trip->trip_hotel_name != NULL || $trip->trip_hotel_name != '') || ($trip->trip_rental_agency != NULL || $trip->trip_rental_agency != '') || (($trip->trip_location_to != NULL || $trip->trip_location_to != '') && ($trip->trip_location_from != NULL || $trip->trip_location_from != ''))) {

		$dep_date = $trip->trip_location_datel ? date('F d, Y', strtotime($trip->trip_location_datel)) : null;
		$dep_time = $trip->trip_location_datel_deptime ? date('h:i:s A', strtotime($trip->trip_location_datel_deptime)) : null;

		$ari_date = $trip->trip_location_datel_deptime ? date('F d, Y', strtotime($trip->trip_location_datel_deptime)) : null;
		$ari_time = $trip->trip_location_datel_arrtime ? date('h:i:s A', strtotime($trip->trip_location_datel_arrtime)) : null;

		$return_dep_date = $trip->trip_location_dater ? date('F d, Y', strtotime($trip->trip_location_dater)) : null;
		$return_dep_time = $trip->trip_location_dater_deptime ? date('h:i:s A', strtotime($trip->trip_location_dater_deptime)) : null;


		if ($trip->trip_transport == "vehicle") {
			$departure_information .= '			
			
			<table>
				<tr>
					<td width="45%">						
						<img src="' . $event_layout . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Destination :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		    </tr>
			
			<tr>
			<td><span class="title_label">Starting date :</span> <span class="pad-left item_details">' . $dep_date . '</span></td>
			<td><span class="title_label">Starting time :</span> <span class="pad-left item_details">' . $dep_time . '</span></td>
			</tr>
			
			<tr>
			<td><span class="title_label">Ending Date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
			<td><span class="title_label">Ending time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>

			

				';
		}
	}
}



if (!empty($trip->trip_location_to_flightportion) || !empty($trip->trip_location_to_drivingportion) || !empty($trip->trip_location_to_trainportion)) {

	if (($trip->trip_location_to_flightportion != NULL || $trip->trip_location_to_flightportion != '')) {
		$flightportion .= '
		
		<table>
				<tr>
					<td width="45%">						
						<img src="' . $flight_portion . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">From :</span> <span class="pad-left item_details">' . $trip->trip_location_from_flightportion . '</span></th>
		    </tr>
			
			<tr>
			<th colspan="2"><span class="title_label">To :</span> <span class="pad-left item_details">' . $trip->trip_location_to_flightportion . '</span></th>	
			</tr>
			
			</table>
			</td>

			</tr>

			</table>
	
		';
	}
	if (($trip->trip_location_to_drivingportion != NULL || $trip->trip_location_to_drivingportion != '')) {
		$carportion .= '		
		<table>
				<tr>
					<td width="45%">						
						<img src="' . $vehicle_portion . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">From :</span> <span class="pad-left item_details">' . $trip->trip_location_from_drivingportion . '</span></th>
		    </tr>
			
			<tr>
			<th colspan="2"><span class="title_label">To :</span> <span class="pad-left item_details">' . $trip->trip_location_to_drivingportion . '</span></th>	
			</tr>
			
			</table>
			</td>

			</tr>

			</table>



		';
	}
	if (($trip->trip_location_to_trainportion != NULL || $trip->trip_location_to_trainportion != '')) {
		$trainportion .= '		

		<table>
				<tr>
					<td width="45%">						
						<img src="' . $train_portion . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">From :</span> <span class="pad-left item_details">' . $trip->trip_location_from_trainportion . '</span></th>
		    </tr>
			
			<tr>
			<th colspan="2"><span class="title_label">To :</span> <span class="pad-left item_details">' . $trip->trip_location_to_trainportion . '</span></th>	
			</tr>
			
			</table>
			</td>

			</tr>

			</table>



		';
	}
}



$html .= '

<style>
.table{		
	font-family: ' . $fontname_regular . ';
	padding: 5px;		
	color:#000;
	font-size:13px;    	  	
}
.table img{
	margin:0;
	padding:0;
}
.title_label{
	font-family: ' . $fontname_bold . ',
}
.text{
	font-family: ' . $fontname_regular . ',
}
.item_details{
	font-weight: 200;
	vertical-align: middle;
}

.divider{
	background-color: rgb(255, 255, 255);		
	font-size:8px !important;
}
</style>

    <div class="section">
		<br><br><br><br>
        ' . $departure_information . ' 		        
        ' . $hotel . ' 
        ' . $rental . ' 
		' . $flightportion . ' 
		' . $carportion . ' 
		' . $trainportion . ' 

    </div>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;


//////////////////////////////////////Itinerary slide with all stops info end//////////////////////



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


$destinataion_update = $destination_lon . ',' . $destination_lat;

$pdf->AddPage('P', 'A4');
$pdf->SetMargins(10, 12, 10);
$top_bar_image = K_PATH_IMAGES . 'top_bar.png';

$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9(" . trim($destinataion_update, " ") . ")/" . trim($destinataion_update, " ") . ",11,0/740x940@2x?access_token=$mapBoxKey";

$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Route Destination', 'The travel plan of tomorrow done right today');
$html = $section_2->html_content();
$html .= '
	<br><br><br><br>
	<img src="' . $destination_route_map . '" width ="740px" height="920px" />
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map Route Destination end////////////////////////


///////////////////////////////////////Map Detailed Route Destination start////////////////////////


$pdf->AddPage('P', 'A4');
$pdf->SetMargins(10, 12, 10);
$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9(" . trim($destinataion_update, " ") . ")/" . trim($destinataion_update, " ") . ",16,0/740x940@2x?access_token=$mapBoxKey";
//$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9($destinataion_update)/$destinataion_update,16,0/740x940@2x?access_token=$mapBoxKey";
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Detailed Route Destination', 'The travel plan of tomorrow done right today');
$html = $section_2->html_content();
$html .= '
	<br><br><br>

	<img src="' . $destination_route_map . '" width ="740px" height="920px" />
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map Detailed Route Destination end////////////////////////



///////////////////////////////////////Schedule start//////////////////////////

$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$i = 1;


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
	$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);

	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
	///////////////////////////////////////Schedule end//////////////////////////

}





///////////////////////////////////////Contine page start////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false, 0);
$html = "
	<style>
		h1{
			text-align:center;
			color:white;
			font-size: 40px;
			font-family:'.$fontname_bold'.;
			font-weight:bold;
			color:#d1d1d1;
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<h1><b>To continue, export your packet...</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');

///////////////////////////////////////Contine page end////////////////////////


///////////////////////////////////////End PDF page start////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false, 0);
$img_file = K_PATH_IMAGES . 'background.png';
$pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$html = "
	<style>
		h1{
			text-align:center;
			color:white;
			font-size: 40px;
			font-family:'.$fontname_bold'.;
			font-weight:bold;
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br>
	<h1><b>Once the packet is exported,The water mark preview will be removed.</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');



ob_end_clean();
$pdf->Output('example_002.pdf', 'I');
