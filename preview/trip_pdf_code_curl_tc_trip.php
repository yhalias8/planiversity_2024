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
	<h1><b> $trip->trip_title</b></h1>
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


$drive = K_PATH_IMAGES . 'drive.png';
$return_drive = K_PATH_IMAGES . 'return_drive.png';
$hotel_layout = K_PATH_IMAGES . 'hotel.png';
$car_layout = K_PATH_IMAGES . 'car.png';
$multistop = K_PATH_IMAGES . 'multistop.png';
$train = K_PATH_IMAGES . 'train.png';
$return_train = K_PATH_IMAGES . 'return_train.png';
$flight = K_PATH_IMAGES . 'flight.png';
$return_flight = K_PATH_IMAGES . 'return_flight.png';


$flight_portion = K_PATH_IMAGES . 'flight_portion.png';
$vehicle_portion = K_PATH_IMAGES . 'vehicle_portion.png';
$train_portion = K_PATH_IMAGES . 'train_portion.png';


$transport = "Flight";
$return_info = $return_flight;
if ($trip->trip_transport == "vehicle") {
	$transport = "Drive";
	$return_info = $return_drive;
} else if ($trip->trip_transport == "train") {
	$transport = "Train";
	$return_info = $return_train;
}

$watermark = K_PATH_IMAGES . 'watermark.png';

$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
$flight_image = K_PATH_IMAGES . 'flight.png';
$car_image = K_PATH_IMAGES . 'car.png';
$train_image = K_PATH_IMAGES . 'train.png';
$hotel_image = K_PATH_IMAGES . 'hotel.png';
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
		$dep_time = $trip->trip_location_datel_deptime ? date('H:i:s A', strtotime($trip->trip_location_datel_deptime)) : null;

		$ari_date = $trip->trip_location_datel_arr ? date('F d, Y', strtotime($trip->trip_location_datel_arr)) : null;
		$ari_time = $trip->trip_location_datel_arrtime ? date('h:i:s A', strtotime($trip->trip_location_datel_arrtime)) : null;


		if ($trip->trip_transport == "vehicle") {
			$departure_information .= '

			<table>
				<tr>
					<td width="45%">						
						<img src="' . $drive . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		    </tr>
			
			<tr>
			<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $dep_date . '</span></td>
			<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $dep_time . '</span></td>
			</tr>

			<tr nobr="true">
			<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		    </tr>			
			
			<tr>
			<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $ari_date . '</span></td>
			<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $ari_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>	
			

				';
		} elseif ($trip->trip_transport == "train") {
			$departure_information .= '				
				
				<table>
				<tr>
					<td width="45%">						
						<img src="' . $train . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		    </tr>
			
			<tr>
			<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $dep_date . '</span></td>
			<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $dep_time . '</span></td>
			</tr>

			<tr nobr="true">
			<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		    </tr>			
			
			<tr>
			<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $ari_date . '</span></td>
			<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $ari_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>


				';
		} else {


			$departure_information .= '
			<table>
				<tr>
					<td width="45%">						
						<img src="' . $flight . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		    </tr>
			
			<tr>
			<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $dep_date . '</span></td>
			<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $dep_time . '</span></td>
			</tr>

			<tr nobr="true">
			<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		    </tr>			
			
			<tr>
			<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $ari_date . '</span></td>
			<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $ari_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>	
		
				';
		}
	} else {
		$departure_information .= '
			<table class="body">
				<tr>
					<td width="10%">
						<div class="double" style="text-align:center;">
							   <img src="' . $flight_image . '" width="40px" height="40px">	
						</div>
					</td>
					<td width="90%" rowspan="2">
						<p style="color:#0D256E; font-size:25px; font-weight:bold;">Flight Information</p>
						   <table width="100%">
							   <tr class="title">
								   <td width="50%"><p style="color:#3E4754; font-size:14px;">Flight: ' . $trip->trip_dep_flight_no . '/Seat ' . $trip->trip_dep_seat_no . '</p></td>
								   <td width="50%"><p style="color:#F39F32; font-size:14px; text-align:right">Date:' . $trip->trip_location_datel . '</p></td>
							   </tr>
							   <p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
							   <p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
							   <div></div>
							   <tr class="content-1">
								   <td width="80%"><p style="color:#67758D; font-size:14px"><b>Departure: </b> <span>' . $trip->trip_location_from . '</span></p></td>
								   <td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">' . $trip->trip_location_datel_deptime . ' (' . $acronym_from . ')</p></td>
							   </tr>
							   <p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
							   <tr class="content-1">
								   <td width="80%"><p style="color:#67758D; font-size:14px"><b>Arrival: </b> <span>' . $trip->trip_location_to . '</span></p></td>
								   <td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">' . $trip->trip_location_datel_arrtime . ' (' . $acronym_to . ')</p></td>
							   </tr>		   						   		
							<div></div>
						   </table>
					</td>
				</tr>
			</table>		
			';
	}
}

$multi_waypoint = "";
if (json_decode($trip->location_multi_waypoint) <> NULL &&  $trip->location_multi_waypoint != NULL) {
	//var_dump($trip->location_multi_waypoint);
	//$multi_waypoint = '<table class="body">';
	$location_multi_waypoint = json_decode($trip->location_multi_waypoint);
	$location_multi_waypoint_date = json_decode($trip->location_multi_waypoint_date);

	$multi_waypoint = '
	<table class="table">
	<tr>
					<td width="45%">						
						<img src="' . $multistop . '" width="500px" >							
					</td>
	</tr>
	<tr>
	<td width="8%"></td>
	<td width="92%" rowspan="2">
	';
	for ($i = 0; $i < count($location_multi_waypoint); $i++) {

		$multidate = $location_multi_waypoint_date[$i] ? date('F d, Y', strtotime($location_multi_waypoint_date[$i])) : null;

		$multi_waypoint .= '    

		<tr nobr="true" width="100%">
		<th colspan="1"><span class="title_label">- ' . $text[$i] . ' Stop </span></th>
		</tr>
				
		<tr nobr="true" width="100%">
		<th colspan="1"><span class="title_label">Destination :</span> <span class="pad-left item_details">' . $location_multi_waypoint[$i] . '</span></th>
		</tr>

		<tr>
		<th><span class="title_label">Date :</span> <span class="pad-left item_details">' . $multidate . '</span></th>		
		</tr>
				
		';
	}
	$multi_waypoint .= '</td></tr></table>';
}
if (($trip->trip_location_to != NULL || $trip->trip_location_to != '') && ($trip->trip_location_from != NULL || $trip->trip_location_from != '')) {
	if (!empty($trip->trip_location_to_flightportion) || !empty($trip->trip_location_to_drivingportion) || !empty($trip->trip_location_to_trainportion)) {

		$return_dep_date = $trip->trip_location_dater ? date('F d, Y', strtotime($trip->trip_location_dater)) : null;
		$return_dep_time = $trip->trip_location_dater_deptime ? date('h:i:s A', strtotime($trip->trip_location_dater_deptime)) : null;

		$return_ari_date = $trip->trip_location_dater_arr ? date('F d, Y', strtotime($trip->trip_location_dater_arr)) : null;
		$return_ari_time = $trip->trip_location_dater_arrtime ? date('h:i:s A', strtotime($trip->trip_location_dater_arrtime)) : null;

		if (($trip->trip_location_to_flightportion != NULL || $trip->trip_location_to_flightportion != '')) {
			$retrun_information .= '

			<table>
			<tr>
				<td width="45%">						
					<img src="' . $return_info . '" width="500px" >							
				</td>
			</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
			</tr>
			
			<tr>
			<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
			<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
			</tr>

			<tr nobr="true">
			<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
			</tr>			
			
			<tr>
			<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
			<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>
			
			
			
			';
		} elseif (($trip->trip_location_to_drivingportion != NULL || $trip->trip_location_to_drivingportion != '')) {


			$retrun_information .= '
			
		<table>
			<tr>
				<td width="45%">						
					<img src="' . $return_info . '" width="500px" >							
				</td>
			</tr>			
		<tr>
		<td width="8%"></td>
		
		<td width="92%" rowspan="2">
		<table class="table">
		<tr nobr="true">
		<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		</tr>
		
		<tr>
		<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
		<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>

		<tr nobr="true">
		<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		</tr>			
		
		<tr>
		<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
		<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>
		</table>
		</td>

		</tr>

		</table>

			';
		} elseif (($trip->trip_location_to_trainportion != NULL || $trip->trip_location_to_trainportion != '')) {

			$retrun_information .= '
			<table>
			<tr>
				<td width="45%">						
					<img src="' . $return_train . '" width="500px" >							
				</td>
			</tr>			
		<tr>
		<td width="8%"></td>
		
		<td width="92%" rowspan="2">
		<table class="table">
		<tr nobr="true">
		<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		</tr>
		
		<tr>
		<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
		<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>

		<tr nobr="true">
		<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		</tr>			
		
		<tr>
		<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
		<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>
		</table>
		</td>

		</tr>

		</table>		
			';
		}
	} else {
		if ($trip->trip_transport == "plane") {
			$retrun_information .= '
			<table>
			<tr>
				<td width="45%">						
					<img src="' . $return_flight . '" width="500px" >							
				</td>
			</tr>			
		<tr>
		<td width="8%"></td>
		
		<td width="92%" rowspan="2">
		<table class="table">
		<tr nobr="true">
		<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		</tr>
		
		<tr>
		<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
		<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>

		<tr nobr="true">
		<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		</tr>			
		
		<tr>
		<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
		<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>
		</table>
		</td>

		</tr>

		</table>
		
			';
		} elseif ($trip->trip_transport == "vehicle") {


			$retrun_information .= '
			<table>
				<tr>
					<td width="45%">						
						<img src="' . $return_drive . '" width="500px" >							
					</td>
				</tr>			
			<tr>
			<td width="8%"></td>
			
			<td width="92%" rowspan="2">
			<table class="table">
			<tr nobr="true">
			<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		    </tr>
			
			<tr>
			<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
			<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
			</tr>

			<tr nobr="true">
			<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		    </tr>			
			
			<tr>
			<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
			<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
			</tr>
			</table>
			</td>

			</tr>

			</table>					
			';
		} else {
			$retrun_information .= '				
            <table>
			<tr>
				<td width="45%">						
					<img src="' . $return_train . '" width="500px" >							
				</td>
			</tr>			
		<tr>
		<td width="8%"></td>
		
		<td width="92%" rowspan="2">
		<table class="table">
		<tr nobr="true">
		<th colspan="2"><span class="title_label">Departure :</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
		</tr>
		
		<tr>
		<td><span class="title_label">Departure date :</span> <span class="pad-left item_details">' . $return_dep_date . '</span></td>
		<td><span class="title_label">Departure time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>

		<tr nobr="true">
		<th colspan="2"><span class="title_label">Arrival :</span> <span class="pad-left item_details">' . $trip->trip_location_from . '</span></th>
		</tr>			
		
		<tr>
		<td><span class="title_label">Arrival Date :</span> <span class="pad-left item_details">' . $return_ari_date . '</span></td>
		<td><span class="title_label">Arrival time :</span> <span class="pad-left item_details">' . $return_dep_time . '</span></td>
		</tr>
		</table>
		</td>

		</tr>

		</table>
			';
		}
	}
}
$html .= '

<style>
.table{		
	font-family: ' . $fontname_regular . ';
	padding: 4.5px;		
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
		<br><br><br>
        ' . $departure_information . ' 		        
		' . $multi_waypoint . ' 
        ' . $hotel . ' 
        ' . $rental . ' 
		' . $retrun_information . ' 
    </div>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;


//////////////////////////////////////Itinerary slide with all stops info end//////////////////////
//////////////////////////////////////Traveling Portion Start//////////////////////
if (!empty($trip->trip_location_to_flightportion) || !empty($trip->trip_location_to_drivingportion) || !empty($trip->trip_location_to_trainportion)) {
	$pdf->AddPage('P', 'A4');
	$flightportion = '';
	$carportion = '';
	$trainportion = '';
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$flight_image = K_PATH_IMAGES . 'flight.png';
	$car_image = K_PATH_IMAGES . 'car.png';
	$train_image = K_PATH_IMAGES . 'train.png';
	$b_left = K_PATH_IMAGES . 'bottom_left.png';
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
	if (($trip->trip_location_to_flightportion != NULL || $trip->trip_location_to_flightportion != '')) {
		$flightportion .= '		
		<table class="table">
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
		<table class="table">
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

		<table class="table">
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
}
//////////////////////////////////////Traveling Portion End//////////////////////

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
	$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
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
	$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
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
	$pdf->Image($watermark, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
}
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map One over the world end/////////////////////////




///////////////////////////////////////Map Route Destination start////////////////////////
if ($trip->trip_hotel_address != NULL || $trip->trip_hotel_address != '') {
	$hotelLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $trip->trip_hotel_address . '&sensor=false&key=' . $key);
	$outputFrom = json_decode($hotelLocation);
	$latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
	$longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
	$destinataion_update = "$longitudeFrom,$latitudeFrom";
} else {
	if ($trip->trip_location_to_latlng_drivingportion != NULL || $trip->trip_location_to_latlng_drivingportion != '') {
		$origin_r = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
		$origin_r = str_replace(')', '', $origin_r);
		$destinataion_update = explode(",", $origin_r)[1] . ',' . explode(",", $origin_r)[0];
	} else {
		$destinataion_update = explode(",", $destination)[1] . ',' . explode(",", $destination)[0];
	}
}


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
if ($trip->trip_hotel_address != NULL || $trip->trip_hotel_address != '') {
	$hotelLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $trip->trip_hotel_address . '&sensor=false&key=' . $key);
	$outputFrom = json_decode($hotelLocation);
	$latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
	$longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
	$destinataion_update = "$longitudeFrom,$latitudeFrom";
} else {
	if ($trip->trip_location_to_latlng_drivingportion != NULL || $trip->trip_location_to_latlng_drivingportion != '') {
		$origin_r = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
		$origin_r = str_replace(')', '', $origin_r);
		$destinataion_update = explode(",", $origin_r)[1] . ',' . explode(",", $origin_r)[0];
	} else {
		$destinataion_update = explode(",", $destination)[1] . ',' . explode(",", $destination)[0];
	}
}
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
//$pdf->writeHTML($html, true, false, true, false, '');
//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');
