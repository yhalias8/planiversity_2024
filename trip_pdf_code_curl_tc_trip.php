<?php

use setasign\Fpdi\Tcpdf\Fpdi;



ini_set('memory_limit', '1280M');

ini_set('precision', 17);

ini_set('serialize_precision', -1);



parse_str($argv[1], $params);

parse_str($argv[2], $params);



$uid     = $_GET['uid'];

$idtrip = $_GET['idtrip'];



$mapBoxKey     = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";

$key         = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';



$text         = array("First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh");



include_once("config.ini.php");

include_once("config.ini.curl.php");



define("WEB_HOSTING_URL", "/home/planiv5/public_html/staging/"); // live version
// define("WEB_HOSTING_URL", "C:/OSPanel/domains/Planiversity2021/"); // dev version
// if (!$auth->isLogged()) {
//     $_SESSION['redirect'] = 'trip/pdf/' . $idtrip;
//     header("Location:" . WEB_HOSTING_URL . "login");
// }
include("class/class.Plan.php");
$plan = new Plan();

// if (!$plan->check_plan($userdata['id'])) { //header("Location:".WEB_HOSTING_URL."billing/".$_POST['idtrip']);
//     //exit();     
// }
//   exit;

include("class/class.TripPlan.php");
$trip = new TripPlan();

include("class/class.TripResource.php");
$resource = new TripResource();

$idtrip = filter_var($idtrip, FILTER_SANITIZE_STRING);

// if (empty($idtrip)){
//     header("Location:" . WEB_HOSTING_URL . "trip/how-are-you-traveling");
// }


$trip->get_data($idtrip);
echo "0,";



$stmh = $dbh->prepare("SELECT sync_googlecalendar FROM users WHERE id=?");
$stmh->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp = $stmh->execute();
$google_object = [];
$google_object = $stmh->fetch(PDO::FETCH_OBJ);

// BEGIN --- add event to google calendar
///if ($userdata['sync_googlecalendar']) {
if ($google_object->sync_googlecalendar) {
	$user_timezone = $userdata['timezone'];
	//date_default_timezone_set($user_timezone);		
	$event_text = '';
	// trip plan
	$event['title'] = $trip->trip_title;
	$event['description'] = 'trip (from: ' . $trip->trip_location_from . ' - to: ' . $trip->trip_location_to . ') on ' . $trip->trip_transport;
	$startdate = ($trip->trip_location_datel != '0000-00-00') ? $trip->trip_location_datel : date('Y-m-d');
	$enddate = ($trip->trip_location_dater != '0000-00-00') ? $trip->trip_location_dater : date('Y-m-d');
	if ($trip->trip_location_triptype == 'o')
		$enddate = $startdate;
	$day = date('j', strtotime($startdate));
	$month = date('n', strtotime($startdate));
	$year = date('Y', strtotime($startdate));
	$event['start_time'] = str_replace('-', '', $startdate); //date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(0,0,0,$month,$day,$year)).'Z'; //date('c',mktime(0,0,0,$month,$day,$year));
	$event['start_time2'] = date('D, j M Y', mktime(0, 0, 0, $month, $day, $year));
	$day = date('j', strtotime($enddate));
	$month = date('n', strtotime($enddate));
	$year = date('Y', strtotime($enddate));
	$event['end_time'] = str_replace('-', '', $enddate);
	//date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(23,59,0,$month,$day,$year)).'Z'; //date('c',mktime(23,59,0,$month,$day,$year));
	$event['end_time2'] = date('D, j M Y', mktime(0, 0, 0, $month, $day, $year));
	$event_text = '<html>		
						  <body>
							<p>
							  Dear ' . $userdata['name'] . ', thanks for using Planiversity.com.
							</p>
							<p>
							  ' . $event['title'] . '<br/>
							  Order for: ' . $userdata['name'] . '<br/>
							  Event: ' . $event['description'] . '<br/>
							  Star Date: ' . $event['start_time2'] . '<br/>
							  End Date: ' . $event['end_time2'] . '<br/>
							  <br/>
							  <a style="padding: 15px 38px;font-size: 17px;line-height:30px;border: 2px solid #f6a027;color: #fff;outline: none;background: #f6a027;cursor: pointer;text-indent:0;text-align:center;text-transform:uppercase;text-decoration: none;" href="http://www.google.com/calendar/event?action=TEMPLATE&dates=' . $event['start_time'] . '%2F' . $event['end_time'] . '&ctz=' . urlencode($user_timezone) . '&text=' . urlencode($event['title']) . '&location=&details=' . urlencode($event['description']) . '">Add event to calendar</a>
							</p>';
	// timeline
	$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=?");
	$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
	$tmp = $stmt->execute();
	if ($tmp && $stmt->rowCount() > 0) {
		$timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($timelines as $timeline) {
			$startdate = explode(' ', $timeline->date);
			$endate = date('Y-m-d H:i:s', strtotime($timeline->date . '+1 hour'));
			$endate = explode(' ', $endate);
			$event['start_time'] = str_replace('-', '', $startdate[0]) . 'T' . str_replace(':', '', $startdate[1]);
			$event['end_time'] = str_replace('-', '', $endate[0]) . 'T' . str_replace(':', '', $endate[1]);
			$event['start_time2'] = date('D, j M Y h:i a', strtotime($timeline->date));
			$event_text .= '<p>' . $timeline->title . '<br/>
									  Order for: ' . $userdata['name'] . '<br/>
									  Start Date: ' . $event['start_time2'] . '<br/>
									  <br/>
									  <a style="padding: 15px 38px;font-size: 17px;line-height:30px;border: 2px solid #f6a027;color: #fff;outline: none;background: #f6a027;cursor: pointer;text-indent:0;text-align:center;text-transform:uppercase;text-decoration: none;" href="http://www.google.com/calendar/event?action=TEMPLATE&dates=' . $event['start_time'] . '%2F' . $event['end_time'] . '&ctz=' . urlencode($user_timezone) . '&text=' . urlencode($timeline->title) . '&location=&details=">Add event to calendar</a>
								  </p>';
		}
	}
	$event_text .= '</body>
					  </html>';

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = $auth->config->site_email;
	$mail->FromName = $auth->config->site_name;
	$mail->addAddress($userdata['email']);
	$mail->isHTML(true);
	$mail->Subject = 'Planiversity.com - Google Calendar Event';
	$mail->Body = $event_text;
	$mail->send();
}
// END --- add event to google calendar


$stmo = $dbh->prepare("SELECT sync_outlookcalendar FROM users WHERE id=?");
$stmo->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp = $stmo->execute();
$outlook_object = [];
$outlook_object = $stmo->fetch(PDO::FETCH_OBJ);

// BEGIN --- add event to outlook calendar
if ($outlook_object->sync_outlookcalendar) {
	$user_timezone = $userdata['timezone'];
	$event_text = '';
	// trip plan
	$event['title'] = $trip->trip_title;
	$event['description'] = 'Trip (from: ' . $trip->trip_location_from . ' - to: ' . $trip->trip_location_to . ') on ' . $trip->trip_transport;
	$startdate = ($trip->trip_location_datel != '0000-00-00') ? $trip->trip_location_datel : date('Y-m-d');
	$enddate = ($trip->trip_location_dater != '0000-00-00') ? $trip->trip_location_dater : date('Y-m-d');
	if ($trip->trip_location_triptype == 'o')
		$enddate = $startdate;
	$day = date('j', strtotime($startdate));
	$month = date('n', strtotime($startdate));
	$year = date('Y', strtotime($startdate));
	$event['start_time'] = $startdate;

	//date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(0,0,0,$month,$day,$year)).'Z'; //date('c',mktime(0,0,0,$month,$day,$year));

	$event['start_time2'] = date('D, j M Y', mktime(0, 0, 0, $month, $day, $year));
	$day = date('j', strtotime($enddate));
	$month = date('n', strtotime($enddate));
	$year = date('Y', strtotime($enddate));
	$event['end_time'] = $enddate;

	//date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(23,59,0,$month,$day,$year)).'Z'; //date('c',mktime(23,59,0,$month,$day,$year));

	$event['end_time2'] = date('D, j M Y', mktime(0, 0, 0, $month, $day, $year));
	$event_text = '<html>		
						  <body>
							<p>
							  Dear ' . $userdata['name'] . ', thanks for using Planiversity.com.
							</p>
							<p>
							  ' . $event['title'] . '<br/>
							  Order for: ' . $userdata['name'] . '<br/>
							  Event: ' . $event['description'] . '<br/>
							  Star Date: ' . $event['start_time2'] . '<br/>
							  End Date: ' . $event['end_time2'] . '<br/>
							  <br/>
							  <a style="padding: 15px 38px;font-size: 17px;line-height:30px;border: 2px solid #f6a027;color: #fff;outline: none;background: #f6a027;cursor: pointer;text-indent:0;text-align:center;text-transform:uppercase;text-decoration: none;" href="https://outlook.office.com/calendar/deeplink/compose?&subject=' . urlencode($event['title']) . '&location=&body=' . urlencode($event['description']) . '&startdt=' . $event['start_time'] . '&enddt=' . $event['end_time'] . '">Add event to outlook calendar</a>							  
							</p>';
	// timeline
	$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=?");
	$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
	$tmp = $stmt->execute();
	if ($tmp && $stmt->rowCount() > 0) {
		$timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($timelines as $timeline) {
			$startdate = explode(' ', $timeline->date);
			$endate = date('Y-m-d H:i:s', strtotime($timeline->date . '+1 hour'));
			$endate = explode(' ', $endate);
			$event['start_time'] = $startdate[0] . 'T' . str_replace(':', '%3A', $startdate[1]);
			$event['end_time'] = $endate[0] . 'T' . str_replace(':', '%3A', $endate[1]);
			$event['start_time2'] = date('D, j M Y h:i a', strtotime($timeline->date));
			$event_text .= '<p>' . $timeline->title . '<br/>
									  Order for: ' . $userdata['name'] . '<br/>
									  Start Date: ' . $event['start_time2'] . '<br/>
									  <br/>									 
									  <a style="padding: 15px 38px;font-size: 17px;line-height:30px;border: 2px solid #f6a027;color: #fff;outline: none;background: #f6a027;cursor: pointer;text-indent:0;text-align:center;text-transform:uppercase;text-decoration: none;" href="https://outlook.office.com/calendar/deeplink/compose?&subject=' . urlencode($timeline->title) . '&location=&body=&startdt=' . $event['start_time'] . '&enddt=' . $event['end_time'] . '">Add event to outlook calendar</a>									  									  									  
								  </p>';
		}
	}
	$event_text .= '</body>
					  </html>';

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = $auth->config->site_email;
	$mail->FromName = $auth->config->site_name;
	$mail->addAddress($userdata['email']);
	$mail->isHTML(true);
	$mail->Subject = 'Planiversity.com - Outlook Calendar Event';
	$mail->Body = $event_text;
	$mail->send();
}


$trip->setProgressing($idtrip, 5);
/////////////////////////////////////////////////////////////////////// PDF /////////////////////////////////////////////////////////////////////// 
$currentPage = 0;
//////////////////////////////////////Page Header Start/////////////////////////////////////////////////

require_once('tcpdf/examples/tcpdf_include.php');
require_once('FPDI/src/autoload.php');

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

$fontname_regular = TCPDF_FONTS::addTTFfont('tcpdf/examples/images/custom_regular.ttf', 'TrueTypeUnicode', '', 96);
$fontname_bold = TCPDF_FONTS::addTTFfont('tcpdf/examples/images/custom_bold.ttf', 'TrueTypeUnicode', '', 96);

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
	$logo_img = K_PATH_IMAGES . 'logo-white.png';

	$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($transp, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($top_right, 174, 0, 40, 40, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($title_img1, 0, 230, 70, 70, '', '', '', false, 500, '', false, false, 0);
	//$pdf->Image($logo_img, 180, 275, 12, 12, '', '', '', false, 300, '', false, false, 0);

	$html = "
<style>
.heading{
	padding-left:100px
}
h5{	
	color:white;
	font-size: 18px;
	font-family:'.$fontname_bold'.;		
	margin:160px;
}
h1{	
	color:white;
	font-size: 50px;
	font-family:'.$fontname_bold'.;
	font-weight:bold;
	line-height:50px;
}

</style>
<div class='heading'>
<br><br><br>
<h5><b>&nbsp;&nbsp;&nbsp;&nbsp;Your Planiversity Document</b></h5>
<h1><b> $trip->trip_title</b></h1>
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
		$dep_time = $trip->trip_location_datel_deptime ? date('h:i:s A', strtotime($trip->trip_location_datel_deptime)) : null;

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
if (($trip->trip_location_to != NULL || $trip->trip_location_to != '') && ($trip->trip_location_from != NULL || $trip->trip_location_from != '') && ($trip->trip_location_triptype != "o")) {
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
		<th colspan="2"><span class="title_label">Departure 4:</span> <span class="pad-left item_details">' . $trip->trip_location_to . '</span></th>
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
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
$trip->setProgressing($idtrip, 20);


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
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}
//////////////////////////////////////Traveling Portion End//////////////////////


///////////////////////////////////////Flight Itinerary start////////////////////////
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'fitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) {
	$documents = $stmt->fetchAll(PDO::FETCH_OBJ);
	$j = 0;
	foreach ($documents as $document) {
		if (strstr($document->name, '.pdf')) {
			$file = './ajaxfiles/uploads/' . $document->name;

			try {
				$pageCount = $pdf->setSourceFile($file);
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$pdf->getTemplateSize($templateId);
					//$fpdi_pdf->AddPage('L');
					$pdf->AddPage('P', 'A4');
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			} catch (Exception $e) {
				//echo $e->getMessage();
			}
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
			$section_2 = new top_bar('Flight Itinerary', 'The travel plan of tomorrow done right today');
			$html = $section_2->html_content();
			$pdf->writeHTML($html, true, false, true, false, '');
			$html = '<div style="text-align:center;">
				<br><br><br><br><br><br><br>
				<img src="./ajaxfiles/uploads/' . $document->name . '" style="max-width: 100%;height: auto;"/>
			</div>';
			$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////Flight Itinerary end////////////////////////
///////////////////////////////////////Hotel Itinerary start////////////////////////
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'hitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) {
	$documents = $stmt->fetchAll(PDO::FETCH_OBJ);
	$j = 0;
	foreach ($documents as $document) {
		if (strstr($document->name, '.pdf')) {
			$file = './ajaxfiles/uploads/' . $document->name;

			try {

				$pageCount = $pdf->setSourceFile($file);

				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$pdf->getTemplateSize($templateId);
					//$fpdi_pdf->AddPage('L');
					$pdf->AddPage('P', 'A4');
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
			$section_2 = new top_bar('Hotel Itinerary', 'The travel plan of tomorrow done right today');
			$html = $section_2->html_content();
			$pdf->writeHTML($html, true, false, true, false, '');
			$html = '<div style="text-align:center;">
				<br><br><br><br><br><br><br>
				<img src="./ajaxfiles/uploads/' . $document->name . '" style="max-width: 100%;height: auto;"/>
			</div>';
			$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////Hotel Itinerary end////////////////////////
$trip->setProgressing($idtrip, 30);


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


	$all_timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
	$len = count($all_timelines);
	if ($len > 8) {
		$pdf->SetAutoPageBreak(TRUE, 20);
	}
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
	$pdf->SetAutoPageBreak(false, 0);

	if ($len < 8) {
		$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);

		$pdf->SetXY(192, 285);
		$pdf->SetTextColor(13, 37, 110);
		$pdf->writeHTML("$currentPage", true, false, true, false, '');
		$currentPage = $currentPage + 1;
	}

	///////////////////////////////////////Schedule end//////////////////////////
	$trip->setProgressing($idtrip, 35);
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
	echo $len;
	foreach ($notes as $note) {
		$i++;
		if ($i != $len) { // list of end
			$html .= '
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="20%" style="">
							<span>&nbsp;</span><img src="./images/note_number/' . $i . '.png" style="width:40px; height:40px;" >
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
							<span>&nbsp;</span><img src="./images/note_number/' . $i . '.png" style="width:40px; height:40px;" >
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
}
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////notes end//////////////////////////
$trip->setProgressing($idtrip, 40);


///////////////////////////////////////Add Plans start////////////////////////


$trip_location_from_latlng = $trip->trip_location_from_latlng;
$trip_location_to_latlng = $trip->trip_location_to_latlng;
$trip_has_train = false;

if ($trip->trip_transport == 'plane') {
	if ($trip->trip_location_to_latlng_drivingportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
	}

	if ($trip->trip_location_to_latlng_trainportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
		$trip_has_train = true;
	}
}


if ($trip->trip_transport == 'vehicle') {
	if ($trip->trip_location_to_latlng_flightportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
	}
	if ($trip->trip_location_to_latlng_trainportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
		$trip_has_train = true;
	}
}


if ($trip->trip_transport == 'train') {
	if ($trip->trip_location_to_latlng_flightportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
	}
	if ($trip->trip_location_to_latlng_drivingportion) {
		$trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
	}
	$trip_has_train = true;
}

$tmp1 = str_replace('(', '', $trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp1 = str_replace(')', '', $tmp1);
$tmp1 = explode(',', $tmp1);

$filter_lat_to = $filter_lat_to2 = trim($tmp1[0]);
$filter_lng_to = $filter_lng_to2 = trim($tmp1[1]);

$ptd = $dbh->prepare("SELECT id_plan as id,plan_name as title,plan_lat as lat,plan_lng as lng,plan_type as type,plan_address as address FROM tripit_plans WHERE trip_id=? AND schedule_linked = 0");
$ptd->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $ptd->execute();
$aux = '';
$plans = [];
//$html = "";
$td_part = "";
$scale = "M";
$mode = "driving";
$unit = "M";
$des_address = $filter_lat_to . "," . $filter_lng_to;

$section_2 = new top_bar('Plans', 'The Travel Plan of Tomorrow');
$html = $section_2->html_content();

if ($tmp && $ptd->rowCount() > 0) {
	$plans = $ptd->fetchAll(PDO::FETCH_OBJ);

	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$calendar_image = K_PATH_IMAGES . 'calendar.png';
	$note_number_image = K_PATH_IMAGES . 'note_number.png';
	$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	// $html .= '<div>
	// 	<br><br><br><br><br><br>
	// ';
	$len = count($plans);
	$i = 0;
	$len;

	$html .= '
    <div>
    <br><br><br><br><br><br>
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>Title</td>
                <td style="color: #0688E9;">Address</td>
                <td>Distance from destination point</td>
                <td style="color: #0688E9;">Category</td>
            </tr>
            <p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
            ' . $td_part . '
        
';

	foreach ($plans as $plan) {
		$i++;
		$compare_address = $plan->lat . "," . $plan->lng;
		$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=" . $scale . "&mode=" . $mode . "&origins=" . urlencode($des_address) . "&destinations=" . urlencode($compare_address) . "&key=" . $key;
		$api = $trip->curlRequest($apiurl);
		$data1 = json_decode($api, true);
		$distance = $data1['rows'][0]['elements'][0]['distance']['text'];

		if ($i != $len) { // list of end
			$td_part .= '				
                        <div></div><tr>
                        <td style="font-size:12px; color:#67758D;">' . $plan->title . '</td>
                        <td style="font-size:12px; color:#0688E9;">' . $plan->address . '</td>
                        <td style="font-size:12px; color:#67758D; text-align:center; font-weight:bold;">' . round((float) $distance, 0) . ' ' . $unit . '</td>
                        <td style="font-size:12px; color:#0688E9;">' . $plan->type . '</td>
						</tr><div></div>
						<p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>                        				
			';
		} else {
			$td_part .= '
            <div></div><tr>
            <td style="font-size:12px; color:#67758D;">' . $plan->title . '</td>
            <td style="font-size:12px; color:#0688E9;">' . $plan->address . '</td>
            <td style="font-size:12px; color:#67758D; text-align:center; font-weight:bold;">' . round((float) $distance, 0) . ' ' . $unit . '</td>
            <td style="font-size:12px; color:#0688E9;">' . $plan->type . '</td>
            </tr><div></div>
            <p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>   				
			';
		}
	}
	//$html .= '</div>';

	$html .= '
    ' . $td_part . '
    </table>
            </div>
    ';
}


$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;


///////////////////////////////////////Add Plans End////////////////////////




///////////////////////////////////////Passport Start////////////////////////
$html = '';
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'passport', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) {
	$documents = $stmt->fetchAll(PDO::FETCH_OBJ);

	$j = 0;
	foreach ($documents as $document) {
		if (strstr($document->name, '.pdf')) {

			try {

				$file = './ajaxfiles/uploads/' . $document->name;
				$pageCount = $pdf->setSourceFile($file);
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$pdf->getTemplateSize($templateId);
					//$fpdi_pdf->AddPage('L');
					$pdf->AddPage('P', 'A4');
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
			$section_2 = new top_bar('Passport', 'The travel plan of tomorrow done right today');
			$html = $section_2->html_content();
			$pdf->writeHTML($html, true, false, true, false, '');
			$html = '<div style="text-align:center;">
				<br><br><br>
				<img src="./ajaxfiles/uploads/' . $document->name . '" style="width:550px;min-width:400px;max-width: 700px;height:auto;"/>
			</div>';
			$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////Passport End////////////////////////

///////////////////////////////////////License Start////////////////////////
$html = '';
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'driver', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) {
	$documents = $stmt->fetchAll(PDO::FETCH_OBJ);
	$j = 0;
	foreach ($documents as $document) {
		if (strstr($document->name, '.pdf')) {
			$file = './ajaxfiles/uploads/' . $document->name;
			try {
				$pageCount = $pdf->setSourceFile($file);
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$pdf->getTemplateSize($templateId);
					//$fpdi_pdf->AddPage('L');
					$pdf->AddPage('P', 'A4');
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
			$section_2 = new top_bar('Driver\'s License', 'The travel plan of tomorrow done right today');
			$html = $section_2->html_content();
			$pdf->writeHTML($html, true, false, true, false, '');
			$html = '<div style="text-align:center;">
				<br><br><br><br><br><br><br>
				<img src="./ajaxfiles/uploads/' . $document->name . '" style="max-width: 100%;height: auto;"/>
			</div>';
			$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////License End////////////////////////


///////////////////////////////////////Additional Documents Start////////////////////////
$html = '';
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'additional', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) {
	$documents = $stmt->fetchAll(PDO::FETCH_OBJ);

	$j = 0;
	foreach ($documents as $document) {
		if (strstr($document->name, '.pdf')) {
			$file = './ajaxfiles/uploads/' . $document->name;

			try {
				$pageCount = $pdf->setSourceFile($file);
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$pdf->getTemplateSize($templateId);
					//$fpdi_pdf->AddPage('L');
					$pdf->AddPage('P', 'A4');
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
			$section_2 = new top_bar('Addtional Documents', 'The travel plan of tomorrow done right today');
			$html = $section_2->html_content();
			$pdf->writeHTML($html, true, false, true, false, '');
			$html = '<div style="text-align:center;">
				<br><br><br><br><br><br><br>
				<img src="./ajaxfiles/uploads/' . $document->name . '" style="max-width: 100%;height: auto;"/>
			</div>';
			$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
			$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////Additional Documents End////////////////////////


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
		$expression = $weatherData[2];
		$pdf->AddPage('P', 'A4');
		$pdf->SetMargins(10, 12, 10);
		$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
		$calendar_image = K_PATH_IMAGES . 'calendar.png';
		$note_number_image = K_PATH_IMAGES . 'note_number.png';
		$sunrise_image = K_PATH_IMAGES . 'sunrise.png';
		$sunrise_bg = K_PATH_IMAGES . 'sunrise_bg.png';
		$bottom_bg = K_PATH_IMAGES . 'bottom_bg.png';
		$table_bg =  K_PATH_IMAGES . 'table_bg.png';
		$cloud =  K_PATH_IMAGES . 'cloud.png';
		$rain =  K_PATH_IMAGES . 'rain.png';
		$sun =  K_PATH_IMAGES . 'sun.png';
		$sun_raise =  K_PATH_IMAGES . 'sun_raise.png';
		$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($sunrise_bg, 0, 100, 0, 100, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($bottom_bg, -2, 132, 0, 300, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_left1, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
		for ($i = 0; $i <= 4; $i++) {
			$pdf->Image($table_bg, 40 + ($i * 27.1), 121, 27.5, 53, '', '', '', false, 300, '', false, false, 0);
		}
		$section_2 = new top_bar("Weather at $locality_long_name", 'The travel plan of tomorrow done right today');
		$html = $section_2->html_content();
		$html .= '
			<div>
			<br><br><br><br><br>
				<div style="text-align:center;">
					<img src="' . $sunrise_image . '" width="300px" height="160px" />
					<p style="font-size:20px; color:#0D256E; text-align:center;">Sunrise: 06:35 Sunset: 20:00</p>			
				</div>		
			</div>
		';
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->SetTextColor(103, 117, 141);
		$pdf->SetXY(10, 55);
		$pdf->writeHTML($headerWeatherInfo[6], true, false, true, false, '');
		$pdf->SetFont($fontname, '', 12, '', false);
		$pdf->SetXY(10, 62);
		$pdf->writeHTML($headerWeatherInfo[7], true, false, true, false, '');
		$pdf->SetFont($fontname, '', 12, '', false);
		$pdf->SetXY(10, 69);
		$pdf->writeHTML(explode(",", $headerWeatherInfo[0])[0], true, false, true, false, '');
		$pdf->SetXY(10, 76);
		$pdf->writeHTML(explode(",", $headerWeatherInfo[0])[1], true, false, true, false, '');
		$pdf->SetFont($fontname, '', 12, '', false);
		$pdf->SetTextColor(243, 159, 50);
		$pdf->SetXY(160, 55);
		$pdf->SetFont($fontname, '', 24, '', false);
		//$pdf->writeHTML(round((float) explode('&', $headerWeatherInfo[1])[0], 0) . "ьз╕C", true, false, true, false, '');
		$pdf->writeHTML(round((float) explode('&', $headerWeatherInfo[1])[0], 0) . "°$expression", true, false, true, false, '');
		$pdf->SetFont($fontname, '', 12, '', false);
		$pdf->SetTextColor(103, 117, 141);
		$pdf->SetXY(148, 68);
		$pdf->writeHTML("Current: $headerWeatherInfo[2]", true, false, true, false, '');
		$pdf->SetXY(148, 75);
		$pdf->writeHTML("Wind: " . explode(' ', $headerWeatherInfo[3])[0] . ' ' . round((float) explode(' ', $headerWeatherInfo[3])[1], 0) . '  ' . explode(' ', $headerWeatherInfo[3])[2], true, false, true, false, '');
		$pdf->SetXY(148, 82);
		$pdf->writeHTML("Humidity: $headerWeatherInfo[4]", true, false, true, false, '');
		$pdf->SetXY(80, 123);
		$html = '<div style="text-align:center;"><table><tr>';
		$html .= '<td width="105px"></td>';
		for ($i = 0; $i <= 4; $i++) {
			$temp_max = number_format($weatherInfo['DailyForecasts'][$i]['Temperature']['Maximum']['Value'], 0) . '&#176;' . $weatherInfo['DailyForecasts'][$i]['Temperature']['Maximum']['Unit'];
			$temp_min = number_format($weatherInfo['DailyForecasts'][$i]['Temperature']['Minimum']['Value'], 0) . '&#176;' . $weatherInfo['DailyForecasts'][$i]['Temperature']['Minimum']['Unit'];
			$date_from = str_replace('T', ' ', $weatherInfo['DailyForecasts'][$i]['Date']);
			$from = date('D', strtotime($date_from));
			$imgname = $weatherInfo['DailyForecasts'][$i]['Day']['Icon'];
			$html .= '<td style="color:#fff; text-align:center;" width="97px">
							<p>' . $from . '</p>
							<img src="./images/weather-icons/acc/' . $imgname . '.png" style="width:42.5px" height="33px" />
							<p>' . $temp_max . '</p>
							<p>' . $temp_min . '</p>
						</td>';
		}
		$html .= '</tr></table></div>';
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->SetXY(192, 285);
		$pdf->SetTextColor(13, 37, 110);
		$pdf->writeHTML("$currentPage", true, false, true, false, '');
		$currentPage = $currentPage + 1;
	}
}
///////////////////////////////////////weather end//////////////////////////



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
	if ($via_waypoints) {
		rsort($mSlice);
	}
	$slice = "";
	$clength = count($mSlice);
	for ($x = 0; $x < $clength; $x++) {
		$slice .= $mSlice[$x][0] . "," . $mSlice[$x][1] . ";";
	}

	$waypoint = "$origin_lon,$origin_lat;$slice$destination_lon,$destination_lat";

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
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map Route Destination end////////////////////////





///////////////////////////////////////Map Detailed Route Destination for stop start////////////////////////
// if (count(json_decode($trip->location_multi_waypoint))) {
//     $multi_waypoint = json_decode($trip->location_multi_waypoint);
//     foreach ($multi_waypoint as $address) {
//         if ($address != "") {
//             $addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&key=' . $key);
//             $outputFrom = json_decode($addressLocation);
//             $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
//             $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
//             $destinataion_update = "$longitudeFrom,$latitudeFrom";
//             $pdf->AddPage('P', 'A4');
//             $pdf->SetMargins(10, 12, 10);
//             $top_bar_image = K_PATH_IMAGES . 'top_bar.png';
//             $destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9($destinataion_update)/$destinataion_update,16,0/740x940@2x?access_token=$mapBoxKey";
//             $pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
//             $pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
//             $pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
//             $pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
//             $section_2 = new top_bar('Detailed Route Destination:', 'The travel plan of tomorrow done right today', $address);
//             $html = $section_2->html_content();
//             $html .= '
//     			<br><br><br>

//     			<img src="' . $destination_route_map . '" width ="740px" height="920px" />
//     		';
//             $pdf->writeHTML($html, true, false, true, false, '');
//             $pdf->SetXY(192, 285);
//             $pdf->SetTextColor(13, 37, 110);
//             $pdf->writeHTML("$currentPage", true, false, true, false, '');
//             $currentPage = $currentPage + 1;
//         }
//     }
//     $addressLocation = $trip->curlRequest('https://maps.google.com/maps/api/geocode/json?address=' . $trip->trip_location_to . '&sensor=false&key=' . $key);
//     $outputFrom = json_decode($addressLocation);
//     $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
//     $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
//     $destinataion_update = "$longitudeFrom,$latitudeFrom";
//     $pdf->AddPage('P', 'A4');
//     $pdf->SetMargins(10, 12, 10);
//     $top_bar_image = K_PATH_IMAGES . 'top_bar.png';
//     $destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9($destinataion_update)/$destinataion_update,16,0/740x940@2x?access_token=$mapBoxKey";
//     $pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
//     $pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
//     $pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
//     $pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
//     $section_2 = new top_bar('Detailed Route Destination:', 'The travel plan of tomorrow done right today', $trip->trip_location_to);
//     $html = $section_2->html_content();
//     $html .= '
// 		<br><br><br>

// 		<img src="' . $destination_route_map . '" width ="740px" height="920px" />
// 	';
//     $pdf->writeHTML($html, true, false, true, false, '');
//     $pdf->SetXY(192, 285);
//     $pdf->SetTextColor(13, 37, 110);
//     $pdf->writeHTML("$currentPage", true, false, true, false, '');
//     $currentPage = $currentPage + 1;
// }

///////////////////////////////////////Map Detailed Route Destination for stop end////////////////////////
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
$pdf->SetXY(192, 285);
$pdf->SetTextColor(13, 37, 110);
$pdf->writeHTML("$currentPage", true, false, true, false, '');
$currentPage = $currentPage + 1;
///////////////////////////////////////Map Detailed Route Destination end////////////////////////



///////////////////////////////////////Map Plan Route Destination start////////////////////////

$dtd = $dbh->prepare("SELECT id_plan as id,plan_name as title,plan_lat as lat,plan_lng as lng,plan_type as type FROM tripit_plans WHERE trip_id=? AND schedule_linked = 0");
$dtd->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $dtd->execute();
$aux = '';
$plans = [];
if ($tmp && $dtd->rowCount() > 0) {
	$plans = $dtd->fetchAll(PDO::FETCH_OBJ);
}


if ($tmp && $dtd->rowCount() > 0) {

	function iconSelect($value)
	{
		$hold = null;
		switch ($value) {
			case "Place to eat":
				$hold = "restaurant_new.png";
				break;
			case "Things to do":
				$hold = "place_new.png";
				break;
			case "People to see":
				$hold = "people_new.png";
				break;
			default:
				$hold = "restaurant_new.png";
		}

		return $hold;
	}


	$mark_position_text = "";


	$i = 0;

	foreach ($plans as $addplan) {
		if ($addplan != "") {
			$icon = iconSelect($addplan->type);
			$mark_position_text .= ",url-https%3A%2F%2Fplaniversity.com%2Fassets%2Fimages%2Ficon-pack%2F" . $icon . "($addplan->lng,$addplan->lat)";
			$i++;
		}
	}


	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';

	$destinataion_update = $filter_lng_to . ',' . $filter_lat_to;

	$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9(" . $destinataion_update . ")" . $mark_position_text . "/" . trim($destinataion_update, " ") . ",8,0/740x940?access_token=$mapBoxKey";


	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Plan Route Destination', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$html .= '
	<br><br><br><br>
	<img src="' . $destination_route_map . '" width ="740px" height="920px" />
';
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}
///////////////////////////////////////Map Plan Route Destination end////////////////////////

///////////////////////////////////////Flight Portion start////////////////////////
if (!empty($trip->trip_location_from_latlng_flightportion) && !empty($trip->trip_location_to_latlng_flightportion)) {
	$origin_r = str_replace('(', '', $trip->trip_location_from_latlng_flightportion);
	$origin_r = str_replace(')', '', $origin_r);
	$destination_r = str_replace('(', '', $trip->trip_location_to_latlng_flightportion);
	$destination_r = str_replace(')', '', $destination_r);
	$destination = $destination_r;
	$origin_lon = trim(explode(',', $origin_r)[1]);
	$origin_lat = trim(explode(',', $origin_r)[0]);
	$destination_lon = trim(explode(',', $destination)[1]);
	$destination_lat = trim(explode(',', $destination)[0]);
	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:[[$origin_lon,$origin_lat],[$destination_lon,$destination_lat]]%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat),pin-s-b+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Route by FLIGHT', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$html .= '
		<br><br><br><br>
		<img src="' . $route_map . '" width ="740px" height="920px" />
	';
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}
///////////////////////////////////////Flight Portion end////////////////////////
///////////////////////////////////////Vehicle Portion start////////////////////////
if (!empty($trip->trip_location_from_latlng_drivingportion) && !empty($trip->trip_location_to_latlng_drivingportion)) {
	$origin_r = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion);
	$origin_r = str_replace(')', '', $origin_r);
	$destination_r = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
	$destination_r = str_replace(')', '', $destination_r);
	$destination = $destination_r;
	$origin_lon = trim(explode(',', $origin_r)[1]);
	$origin_lat = trim(explode(',', $origin_r)[0]);
	$destination_lon = trim(explode(',', $destination)[1]);
	$destination_lat = trim(explode(',', $destination)[0]);
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
		if ($trip->trip_option_directions) {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
			$i = 0;
			$routeJson = "";
			foreach ($drivingDirectionResult["routes"] as $item) {
				$i = $i + 1;
				if (count($drivingDirectionResult["routes"]) == 1) {
					$routeJson .= "geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode($item["geometry"]["coordinates"]) . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D)";
				} else {
					if ($i == 1) {
						$routeJson .= "geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode($item["geometry"]["coordinates"]) . "%7D,%22properties%22:%7B%22stroke%22:%22%2332B765%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D)";
					}
					if (count($drivingDirectionResult["routes"]) == $i) {
						$routeJson .= ",geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode($item["geometry"]["coordinates"]) . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D)";
					} else {
						$routeJson .= ",geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode($item["geometry"]["coordinates"]) . "%7D,%22properties%22:%7B%22stroke%22:%22%2332B765%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D)";
					}
				}
			}
			$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/$routeJson,pin-s-a+007acc($origin_lon,$origin_lat),pin-s-b+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";
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
			$pdf->SetXY(85, 40);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("Distance: $distance km", true, false, true, false, '');
			$pdf->SetXY(85, 47);
			$pdf->writeHTML("Duration: $duration mins", true, false, true, false, '');
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		} else {
			$pdf->AddPage('P', 'A4');
			$pdf->SetMargins(10, 12, 10);
			$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
			$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode(end($drivingDirectionResult["routes"])["geometry"]['coordinates']) . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat),pin-s-b+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";
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
			$pdf->SetXY(192, 285);
			$pdf->SetTextColor(13, 37, 110);
			$pdf->writeHTML("$currentPage", true, false, true, false, '');
			$currentPage = $currentPage + 1;
		}
	}
}
///////////////////////////////////////Vehicle Portion end////////////////////////
///////////////////////////////////////Train Portion start////////////////////////
if (!empty($trip->trip_location_from_latlng_trainportion) && !empty($trip->trip_location_to_latlng_trainportion)) {
	$origin_r = str_replace('(', '', $trip->trip_location_from_latlng_trainportion);
	$origin_r = str_replace(')', '', $origin_r);
	$destination_r = str_replace('(', '', $trip->trip_location_to_latlng_trainportion);
	$destination_r = str_replace(')', '', $destination_r);
	$destination = $destination_r;
	$origin_lon = trim(explode(',', $origin_r)[1]);
	$origin_lat = trim(explode(',', $origin_r)[0]);
	$destination_lon = trim(explode(',', $destination)[1]);
	$destination_lat = trim(explode(',', $destination)[0]);
	$drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$origin_lon%2C$origin_lat%3B$destination_lon%2C$destination_lat?alternatives=true&geometries=geojson&steps=true&access_token=$mapBoxKey";
	$api = $trip->curlRequest($drivingDirectionApi);
	$drivingDirectionResult = json_decode($api, true);
	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22:%22FeatureCollection%22,%22features%22:[%7B%22type%22:%22Feature%22,%22geometry%22:%7B%22type%22:%22LineString%22,%22coordinates%22:" . json_encode(end($drivingDirectionResult["routes"])["geometry"]['coordinates']) . "%7D,%22properties%22:%7B%22stroke%22:%22%23007ACC%22,%22stroke-opacity%22:1,%22stroke-width%22:5%7D%7D]%7D),pin-s-a+007acc($origin_lon,$origin_lat),pin-s-b+007acc($destination_lon,$destination_lat)/auto/720x940@2x?access_token=$mapBoxKey";
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
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}
///////////////////////////////////////Train Portion end////////////////////////
////////////////////////////////////////driving direction start//////////////////////////////////////////

if ($trip->trip_option_directions) {
	if ($trip->trip_location_to_latlng_drivingportion != NULL || $trip->trip_location_to_latlng_drivingportion != '') {
		$origin_r1 = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion);
		$origin_r1 = str_replace(')', '', $origin_r1);
		$destination_r1 = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
		$destination_r1 = str_replace(')', '', $destination_r1);
		$destination1 = $destination_r1;
		$origin_lon1 = trim(explode(',', $origin_r1)[1]);
		$origin_lat1 = trim(explode(',', $origin_r1)[0]);
		$destination_lon1 = trim(explode(',', $destination1)[1]);
		$destination_lat1 = trim(explode(',', $destination1)[0]);
		$drivingDirectionApi = "https://api.mapbox.com/directions/v5/mapbox/driving/$origin_lon1%2C$origin_lat1%3B$destination_lon1%2C$destination_lat1?alternatives=true&geometries=geojson&steps=true&access_token=$mapBoxKey";
		$api = $trip->curlRequest($drivingDirectionApi);
		$drivingDirectionResult = json_decode($api, true);
		$tmp = '';
		if ($drivingDirectionResult['code'] == 'Ok') {
			$data_arr = end($drivingDirectionResult["routes"])["legs"][0]["steps"];
			for ($i = 0; $i < count($data_arr); $i++) {
				if ($i % 20 == 0) {
					$pdf->AddPage('P', 'A4');
					$pdf->SetMargins(10, 12, 10);
					$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
					$top_logo_img = K_PATH_IMAGES . 'hotel_filter_map.png';
					$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
					$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
					$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
					$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
					$section_2 = new top_bar('Driving directions', '');
					$html = $section_2->html_content();
					$html .= '
							<div>
							<br><br><br><br><br><br>
								<table cellspacing="0" cellpadding="0">
									' . $tmp . '									
					';
				}
				$tmp .= '<tr>
						<td style="width:10%; font-size:12px; color:#0688E9; text-align:left;">' . ($i + 1) . '</td>
						<td style="width:70%; font-size:12px; color:#67758D;">' . $data_arr[$i]["maneuver"]["instruction"] . '</td>
						<td style="width:20%; font-size:12px; color:#0688E9; text-align:right;">' . round((float) $data_arr[$i]["distance"], 0) . ' m</td>
					</tr><br>
					';
				if ($i % 20 == 19) {
					$html .= '
					' . $tmp . '
					</table>
							</div>
					';
					$pdf->writeHTML($html, true, false, true, false, '');
					$pdf->SetXY(192, 285);
					$pdf->SetTextColor(13, 37, 110);
					$pdf->writeHTML("$currentPage", true, false, true, false, '');
					$currentPage = $currentPage + 1;
					$tmp = "";
					$html = "";
				}
			}
		}
		$html .= '
			' . $tmp . '
			</table>
					</div>
			';
		$pdf->writeHTML($html, true, false, true, false, '');
		$tmp = "";
		$html = "";
		$pdf->SetXY(192, 285);
		$pdf->SetTextColor(13, 37, 110);
		$pdf->writeHTML("$currentPage", true, false, true, false, '');
		$currentPage = $currentPage + 1;
		$pdf->writeHTML($html, true, false, true, false, '');
	}
}

////////////////////////////////////////driving direction end//////////////////////////////////////////

///////////////////////////////////////emabassy map start////////////////////////
if ($trip->trip_option_embassis) {
	$resultData = $trip->getMapEmbassis($destination, $key, $trip->trip_list_embassis);
	$userdata = $resultData["userData"];
	$scale = $resultData["scale"];
	$distance = "";
	$lat = "";
	$lng = "";
	$mode = 'driving';
	if (!empty($trip->trip_list_embassis)) {
		$embassiesList = explode(',', $trip->trip_list_embassis);
		foreach ($embassiesList as $item) {
			$routeApi = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$item&key=$key";
			$api = $trip->curlRequest($routeApi);
			$embassyData = json_decode($api, true);
			if ($embassyData["status"] == "OK") {
				$lat = $embassyData["result"]["geometry"]["location"]["lat"];
				$lng = $embassyData["result"]["geometry"]["location"]["lng"];
				if ($mode) {
					$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=" . $userdata['scale'] . "&mode=" . $mode . "&origins=" . urlencode($trip->trip_location_from) . "&destinations=" . urlencode($embassyData['result']['vicinity']) . "&key=" . $key;
					$api = $trip->curlRequest($apiurl);
					$data = json_decode($api, true);
					$distance = $data['rows'][0]['elements'][0]['distance']['text'];
				} else {
					$distance = $trip->getDistance($trip->trip_location_to, $embassyData['result']['vicinity'], $scale, $key);
				}
				$pdf->AddPage('P', 'A4');
				$pdf->SetMargins(10, 12, 10);
				$html = '';
				$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
				$embassyMap = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-l-town-hall+0688e9($lng,$lat)/$lng,$lat,16,0/720x780@2x?access_token=$mapBoxKey";
				$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
				$section_2 = new top_bar('Embassy', 'The travel plan of tomorrow done right today');
				$html = $section_2->html_content();
				$html .= '
					<br><br><br><br>
					<img src="' . $embassyMap . '" width ="740px" height="780px" />
					<br><br>
					<table width="100%">
						<tr class="content-1">
							<td><p style="color:#67758D; font-size:14px"><b><span style="color:#0D256E;">Name:</span></b> <span>' . $embassyData['result']['name'] . '</span></p></td>
						</tr>
						<br>
						<tr class="content-1">
							<td><p style="color:#67758D; font-size:14px"><b><span style="color:#0D256E;">Address:</span></b> <span>' . $embassyData['result']['vicinity'] . '</span></p></td>
						</tr>		   						   		
						<br>
						<tr class="content-1">
							<td><p style="color:#67758D; font-size:14px"><b><span style="color:#0D256E;">Distance from destination point:</span></b> <span>' . round((float) $distance, 0) . ' ' . $scale . '</span></p></td>
						</tr>		   						   		
					</table>
				';
				$pdf->writeHTML($html, true, false, true, false, '');
				$pdf->SetXY(192, 285);
				$pdf->SetTextColor(13, 37, 110);
				$pdf->writeHTML("$currentPage", true, false, true, false, '');
				$currentPage = $currentPage + 1;
			}
		}
	}
}
///////////////////////////////////////emabassy map end////////////////////////

////////////////////////////////////////subway map start//////////////////////////////////////////
if ($trip->trip_option_subway) {
	$html = '';
	$pdf->AddPage('P', 'A4');
	$pdf->SetMargins(10, 12, 10);
	$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
	$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
	$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
	$section_2 = new top_bar('Subway Map', 'The travel plan of tomorrow done right today');
	$html = $section_2->html_content();
	$dir =  WEB_HOSTING_URL . "subwaymap/";
	$indir = 0;
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == 'file') {
					$filename = substr($file, 0, -4);
					$filename = str_replace('-', ' ', $filename);
					if (stristr($trip->trip_location_to, $filename)) {
						$html .= '
						<br><br><br><br>
						<img src="' . $dir . $file . '" width ="600px" height="800px" style="text-align:center;" />';
						$indir = 1;
						echo $filename;
						break;
					}
				}
			}
			closedir($dh);
		}
	}
	if (!$indir) {
		$destination_route_map = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($des_address) . "&style=feature:transit.line|element:all|visibility:simplified|color:0xFF6319&zoom=13&size=740x920&scale=2&key=$key";
		$html .= '
			<br><br><br><br>
			<img src="' . $destination_route_map . '" width ="740px" height="920px" />
		';
	}
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->SetXY(192, 285);
	$pdf->SetTextColor(13, 37, 110);
	$pdf->writeHTML("$currentPage", true, false, true, false, '');
	$currentPage = $currentPage + 1;
}
/////////////////////////////////////////subway map end///////////////////////////////////////
$trip->setProgressing($idtrip, 55);

///////////////////////////////////////Resources start////////////////////////
$resourceList = $resource->get_resources_from_trip($idtrip);
$ignoreList = [];
if (!empty($resourceList)) {

	foreach ($resourceList as $i => $resItem) {
		$tmp = '';
		$countSame = 0;

		$pdfIcon = $resource->getPdfIconByType($resItem->type);
		$pageTitle = $resource->getPlaceByType($resItem->type);

		if (in_array($resItem->type, $ignoreList)) {
			echo "$resItem->type H=IGNOR <br>";
			continue;
		} else {
			echo "$resItem->type OK <br>";
		}
		$ignoreList[] = $resItem->type;

		$pdf->AddPage('P', 'A4');
		$pdf->SetMargins(10, 12, 10);
		$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
		$calendar_image = K_PATH_IMAGES . 'calendar.png';
		$note_number_image = K_PATH_IMAGES . 'note_number.png';
		$top_logo_img = K_PATH_IMAGES . $pdfIcon;

		$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
		$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
		$pdf->Image($top_logo_img, 170, 18, 26, 25, '', '', '', false, 300, '', false, false, 0);

		$section_2 = new top_bar($pageTitle, '');
		$html = $section_2->html_content();
		$html .= '
								<div>
								<br><br><br><br><br><br>
									<table cellspacing="0" cellpadding="0">
										<tr>
											<td>Name</td>
											<td style="color: #0688E9;">Address</td>
											<td>Distance from destination point</td>
											<td style="color: #0688E9;">Destination</td>
										</tr>
										<p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
										' . $tmp . '
									
						';


		foreach ($resourceList as $j => $child) {

			if ($child->type !== $resItem->type) {
				continue;
			}

			$countSame++;

			if ($countSame % 7 === 0 && $countSame !== 0) {
				$html .= '
                ' . $tmp . '
                </table>
                        </div>
                ';

				$pdf->writeHTML($html, true, false, true, false, '');
				$tmp = "";
				$html = "";
				$pdf->SetXY(192, 285);
				$pdf->SetTextColor(13, 37, 110);
				$pdf->writeHTML("$currentPage", true, false, true, false, '');
				$currentPage = $currentPage + 1;
				//                $pdf->writeHTML($html, true, false, true, false, '');


				$pdf->AddPage('P', 'A4');
				$pdf->SetMargins(10, 12, 10);
				$top_bar_image = K_PATH_IMAGES . 'top_bar.png';
				$calendar_image = K_PATH_IMAGES . 'calendar.png';
				$note_number_image = K_PATH_IMAGES . 'note_number.png';
				$top_logo_img = K_PATH_IMAGES . $pdfIcon;

				$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
				$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
				$pdf->Image($top_logo_img, 170, 18, 26, 25, '', '', '', false, 300, '', false, false, 0);

				$section_2 = new top_bar($pageTitle, '');
				$html = $section_2->html_content();
				$html .= '
                            <div>
                            <br><br><br><br><br><br>
                                <table cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>Name</td>
                                        <td style="color: #0688E9;">Address</td>
                                        <td>Distance from destination point</td>
                                        <td style="color: #0688E9;">Destination</td>
                                    </tr>
                                    <p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
                                    ' . $tmp . '
                                
                    ';
			}

			$destination = "$child->lat,$child->lng";
			$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=" . $userdata['scale'] . "&origins=" . urlencode($trip->trip_location_from) . "&destinations=" . $destination . "&key=" . $key;
			$api = $trip->curlRequest($apiurl);
			$data = json_decode($api, true);

			$distance = $data['rows'][0]['elements'][0]['distance']['text'];

			$tmp .= '<div></div><tr>
                        <td style="font-size:12px; color:#67758D;">' . $child->title . '</td>
                        <td style="font-size:12px; color:#0688E9;">' . $child->address . '</td>
                        <td style="font-size:12px; color:#67758D; text-align:center; font-weight:bold;">' . $distance . '</td>
                        <td style="font-size:12px; color:#0688E9;">' . $trip->trip_location_from . '</td>
                    </tr><div></div>
                    <p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>';
		}

		$html .= '
			' . $tmp . '
			</table>
					</div>
			';

		$pdf->writeHTML($html, true, false, true, false, '');
		$tmp = "";
		$html = "";
		$pdf->SetXY(192, 285);
		$pdf->SetTextColor(13, 37, 110);
		$pdf->writeHTML("$currentPage", true, false, true, false, '');
		$currentPage = $currentPage + 1;
		//        $pdf->writeHTML($html, true, false, true, false, '');
	}
}
///////////////////////////////////////Resources end////////////////////////

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
			font-size: 50px;
			font-family:'.$fontname'.;
			font-weight:bold;
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<h1><b>Thank you for choosing PLANIVERSITY!</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');
///////////////////////////////////////End PDF page end////////////////////////
// $pdfpath = 'D://Planiversity.pdf';
// if (file_exists($pdfpath)){
//     unlink($pdfpath);
// }
// $pdf->Output('D://Planiversity.pdf',"F");
// exit();


$triptitle = trim($trip->trip_title);
$triptitle = str_replace('&#39;', '_', $triptitle);
$triptitle = str_replace(' ', '_', $triptitle);

$pdfname = $triptitle . '-' . $idtrip;
echo "96,";
$pdfpath = WEB_HOSTING_URL . 'pdf/' . $pdfname . '.pdf';
// delete if exist
if (file_exists($pdfpath)) unlink($pdfpath);

$pdf->Output(WEB_HOSTING_URL . 'pdf/' . $pdfname . '.pdf', "F");
$trip->edit_data_pdf($idtrip);
$trip->setProgressing($idtrip, 100);
// var_dump(WEB_HOSTING_URL . 'pdf/' . $pdfname . '.pdf');
print_r($trip->error);
echo "100!!!!";
echo "OK DONE";





// $pdfpath = 'D://Planiversity.pdf';
// if (file_exists($pdfpath)){
//     unlink($pdfpath);
// }
// $pdf->Output('D://Planiversity.pdf',"F");
// exit();
