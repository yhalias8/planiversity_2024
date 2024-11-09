<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

// get params
parse_str($argv[1], $params);
$idtrip = $params['idtrip'];
parse_str($argv[2], $params);
$uid = $params['uid'];

include_once("config.ini.curl.php");

// define("WEB_HOSTING_URL", "/home/planiv5/public_html/"); // live version
define("WEB_HOSTING_URL", "/home/planiv5/public_html/"); // dev version

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/pdf/' . $idtrip;
    header("Location:" . WEB_HOSTING_URL . "login");
}

include("class/class.Plan.php");
$plan = new Plan();
if (!$plan->check_plan($userdata['id'])) { //header("Location:".WEB_HOSTING_URL."billing/".$_POST['idtrip']);
    //exit();     
}
//   exit;
include("class/class.TripPlan.php");
$trip = new TripPlan();
$idtrip = filter_var($idtrip, FILTER_SANITIZE_STRING);
if (empty($idtrip))
    header("Location:" . WEB_HOSTING_URL . "trip/how-are-you-traveling");
echo $idtrip.",";
$trip->get_data($idtrip);
$trip->setProgressing($idtrip, 0);
echo "0,";

$plan->change_status_plan($userdata['id']);

// BEGIN --- add event to google calendar
if ($userdata['sync_googlecalendar']) {
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
    $mail->Subject = 'Planiversity.com - Calendar Event';
    $mail->Body = $event_text;
    $mail->send();
}
// END --- add event to google calendar
$trip->setProgressing($idtrip, 5);
echo "5,";
/////////////////////////////////////////////////////////////////////// PDF /////////////////////////////////////////////////////////////////////// 
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    //'format' => [270, 200],
    'format' => 'A4-L',
    'margin_right' => '0',
    'margin_left' => '0',
    'margin_top' => '40',
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/font',
    ]),
    'fontdata' => $fontData + [
'Montserrat-Light' => [
    'R' => 'Montserrat-Light.ttf',
],
 'Montserrat-Regular' => [
    'R' => 'Montserrat-Regular.ttf',
],
 'Montserrat-Medium' => [
    'R' => 'Montserrat-Medium.ttf',
],
 'MyriadProBold' => [
    'R' => 'MyriadPro-Bold_1.ttf',
]
    ],
    'default_font' => 'MyriadProBold'
        ]);

$_to = explode(',', $trip->trip_location_to);

$html = '<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
           <img src="' . WEB_HOSTING_URL . 'images/aaa.jpg" width="100%"  />           
         </div>
         <div align="center" style="font-size:48px;font-family:Montserrat-Medium; position:fixed; width:100%; padding:5px 0 0 0; color:#444444"><p align="center"><b>' . $trip->trip_title . '</b></p></div>
';
$mpdf->WriteHTML($html);
$html = '';


// header
//$mpdf->SetHTMLHeader('<div width="100%" align="center" style="border-bottom:1px solid #86BFEA; padding-bottom:10px">
//     <img src="'.WEB_HOSTING_URL.'images/round.png" /><br />
//     <font style="font-size:20px; color:#1F72B2; font-family:Montserrat-Regular;color: white;">The travel plan of tomorrow done right today</font>
//</div>');


$html = '';

// footer         
//$mpdf->SetHTMLFooter('<table width="95%">
//    <tr>
//        <td width="33%"></td>
//        <td width="33%" align="center" style="font-size:12px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
//        <td width="33%" style="text-align: right; color:#666">{PAGENO}/{nbpg}</td>
//    </tr>
//</table>');
$mpdf->SetHTMLHeader('<div><div style="background: #2372b4;padding: 10px 0;height: 60px;text-align: left;">
            <div>
                <div>
                    <div style="margin-top:10px;font-size:30px;font-family:Montserrat-Regular;color: white;margin-left:20px;"><b>Your Itinerary</b></div>
                </div>
            </div>
        </div>
        <div  align="right">
                    <img style="max-height: 100px;margin-top: -55px;margin-right: 20px;" src="' . WEB_HOSTING_URL . 'images/round.png" alt="" title="" /></img>
         </div></div>');
$mpdf->SetHTMLFooter('<table width="100%">
    <tr>
        
        <td width="50%" align="left" style="padding-left:25px;text-align:left;font-size:14px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="50%" align="right" style="padding-right:25px;text-align: right; color:#666">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

// Itinerary   



$mpdf->AddPage();
$latLng_timezone = trim($trip->trip_location_from_latlng,'()');
$from_timestamp = implode('',explode(':',$trip->trip_location_datel_deptime));
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$url = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $latLng_timezone . "&timestamp=" . $from_timestamp . "&key=" . $key;
$url = preg_replace("/ /", "%20", $url);
//  print_r($url);
$result = TripPlan::curlRequest($url);
$googleDirection = json_decode($result, true);
// print_r($googleDirection['timeZoneName']);
$test_timezone_from = ($googleDirection['timeZoneName']);
// print_r($test);	

$words = explode(" ", $test_timezone_from);
    $acronym_from = "";

    foreach ($words as $w) {
      $acronym_from .= $w[0];
    }
// print_r('acronym_from_timezone=',$acronym_from);
$trip->setProgressing($idtrip, 10);
echo "10,";

$latLng_timezone_to = trim($trip->trip_location_to_latlng,'()');
$from_timestamp_to = implode('',explode(':',$trip->trip_location_datel_arrtime));
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$url = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $latLng_timezone_to . "&timestamp=" . $from_timestamp_to . "&key=" . $key;
$url = preg_replace("/ /", "%20", $url);
//  print_r($url);
$result = TripPlan::curlRequest($url);
$googleDirection = json_decode($result, true);
// print_r($googleDirection['timeZoneName']);
$test_timezone_to = ($googleDirection['timeZoneName']);
// print_r($test);	

$words = explode(" ", $test_timezone_to);
    $acronym_to = "";

    foreach ($words as $w) {
      $acronym_to .= $w[0];
      
}

$hotel = '';
$rental = '';
$hotel_oneway = '';
$rental_oneway = '';
if(!$trip->trip_hotel_name == NULL || !$trip->trip_hotel_name == ''){
    $hotel .= '<div style="display: flex;">
    <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
    border-color: #ffffff;
    border-width: 5px;
    margin-left:25px;
    margin-right:25px;
   border-radius: 50%;margin-bottom:5px"><img style="position:absolute;margin-left:6px;margin-top:5px" height="35px" width="35px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/22.png" /></div>
</div>
    
    <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-50px">
        <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Hotel Booking</b></p>
    </div>
<div style="border-left: 6px solid #f3a02e;height: 50px;margin-left:45px">
<div style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;" >
    <div style="margin: 2px;"><b>Hotel Name: </b>' . $trip->trip_hotel_name . '</div>
    <div style="border-bottom: medium solid gray;margin: 2px;"><b>Hotel Address: </b>' . $trip->trip_hotel_address . '</div>
    
    <div style="margin: 2px;"><b>Check-in: </b>' . $trip->trip_hotel_date_checkin . '<div align="right" style="margin-top:-16px;font-weight: bolder;"><b>Check-out: </b>' . $trip->trip_hotel_date_checkout . '</div></div>
</div>
</div>
';

$hotel_oneway .= '<div style="display: flex;">
<div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
border-color: #ffffff;
border-width: 5px;
margin-left:25px;
margin-right:25px;
border-radius: 50%;margin-bottom:5px"><img style="position:absolute;margin-left:6px;margin-top:5px" height="35px" width="35px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/22.png" /></div>
</div>

<div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-50px">
    <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Hotel Booking</b></p>
</div>
<div style="margin-left:45px">
<div style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;" >
<div style="margin: 2px;"><b>Hotel Name: </b>' . $trip->trip_hotel_name . '</div>
<div style="border-bottom: medium solid gray;margin: 2px;"><b>Hotel Address: </b>' . $trip->trip_hotel_address . '</div>

<div style="margin: 2px;"><b>Check-in: </b>' . $trip->trip_hotel_date_checkin . '<div align="right" style="margin-top:-16px;font-weight: bolder;"><b>Check-out: </b>' . $trip->trip_hotel_date_checkout . '</div></div>
</div>
</div>
';
}
if(!$trip->trip_rental_agency == NULL || !$trip->trip_rental_agency == ''){
    $rental .= '<div style="display: flex;">
                    
    <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
    border-color: #ffffff;
    border-width: 5px;
    margin-left:25px;
    margin-right:25px;
   border-radius: 50%;margin-bottom:5px"><img style="position:absolute;margin-left:6px;margin-top:5px" height="40px" width="40px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/21.png" /></div>
</div>
    
    <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-50px">
        <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Rental Car Information</b></p>
    </div>

 <div style="border-left: 6px solid #f3a02e;height: 50px;margin-left:45px">
<div style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;" >
    <div style="margin: 2px;"><b>Agency Name: </b>' . $trip->trip_rental_agency . '</div>
    <div style="border-bottom: medium solid gray;margin: 2px;"><b>Location: </b>' . $trip->trip_rental_agency_address . '</div>
    
    <div style="margin: 2px;"><b>Pick-up: </b>' . $trip->trip_rental_date_pick . '<div align="right" style="margin-top:-16px;font-weight: bolder;"><b>Drop-off: </b>' . $trip->trip_rental_date_drop . '</div></div>
</div>
</div>';

}

$trip->setProgressing($idtrip, 15);
echo "15,";

if($trip->trip_location_triptype == 'r'){

    $html = '<div style="display: flex;">
                        <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
                        border-color: #ffffff;
                        border-width: 5px;
                        margin-left:25px;
                        margin-right:25px;
                    border-radius: 50%;"><img style="position:absolute;margin-top:3px;margin-left:2px" height="45px" width="45px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/20.png" /></div>
                                
                    </div>

                        <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-45px">

                            <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Flight Information</b></p>
                        </div>
                    
                    <div style="border-left: 6px solid #f3a02e;height: 80px;margin-left:45px">
                    <div  style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;">

                        <div style="border-bottom: medium solid gray;margin: 2px;"><b>Flight: </b>' . $trip->trip_dep_flight_no . '/Seat ' . $trip->trip_dep_seat_no . '
                        <div align="right" style="margin-top:-16px;text-align:right;">Date:' . $trip->trip_location_datel . '</div></div>
                        <div style="margin: 2px;"><b>Departure: </b>' . $trip->trip_location_from . '<div align="right" style="margin-top:-16px;font-weight: bolder;">' . $trip->trip_location_datel_deptime . '('.$acronym_from.')</div></div>
                        <div style="margin: 2px;"><b>Arrival: </b>' . $trip->trip_location_to . '<div align="right" style="margin-top:-16px;font-weight: bolder;">' . $trip->trip_location_datel_arrtime . '('.$acronym_to.')</div></div>
                        
                    </div>
    </div>
                '.$rental.' 
                    
                '.$hotel.'

                
                    <div style="display: flex;">
                    
                        <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
                        border-color: #ffffff;
                        border-width: 5px;
                        margin-left:25px;
                        margin-right:25px;
                    border-radius: 50%;margin-bottom:8px"><img style="position:absolute;margin-top:5px;margin-left:2px" height="45px" width="45px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/23.png" /></div>
                    </div>
                        <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-53px">
                            <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Return Flight Information</b></p>
                        </div>
                    <div style="margin-left:30px">
                    <div style="margin-left:7%;margin-right:5%;font-family:Montserrat-Regular;" >
                        <div  style="white-space: nowrap;display:inline-block;text-align:left;border-bottom: medium solid gray;margin: 2px;"><b>Flight: </b>AA765/Seat 15A<div align="right" style="margin-top:-16px;text-align:right;font-weight: bolder;">Date:' . $trip->trip_location_dater . '</div></div>

                        <div style="margin: 2px;"><b>Departure: </b>' . $trip->trip_location_to . '<div align="right" style="margin-top:-16px;font-size:14px">' . $trip->trip_location_dater_deptime . ' ('.$acronym_to.')</div></div>
                        <div style="margin: 2px;"><b>Arrival: </b>' . $trip->trip_location_from . '<div align="right" style="margin-top:-16px;font-size:14px">' . $trip->trip_location_dater_arrtime . ' ('.$acronym_from.')</div></div>
                    </div> 
                    </div>  ';
    }


else{
    if($rental || $hotel_oneway){
        $one_way = 'border-left: 6px solid #f3a02e;height: 80px;';
    }
    else{
        $one_way = '';
    }
    if($rental && !$hotel_oneway){
        $rental = '<div style="display: flex;">
                    
        <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
        border-color: #ffffff;
        border-width: 5px;
        margin-left:25px;
        margin-right:25px;
       border-radius: 50%;margin-bottom:5px"><img style="position:absolute;margin-left:6px;margin-top:5px" height="40px" width="40px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/21.png" /></div>
    </div>
        
        <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-50px">
            <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Rental Car Information</b></p>
        </div>
    
     <div style="margin-left:45px">
    <div style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;" >
        <div style="margin: 2px;"><b>Agency Name: </b>' . $trip->trip_rental_agency . '</div>
        <div style="border-bottom: medium solid gray;margin: 2px;"><b>Location: </b>' . $trip->trip_rental_agency_address . '</div>
        
        <div style="margin: 2px;"><b>Pick-up: </b>' . $trip->trip_rental_date_pick . '<div align="right" style="margin-top:-16px;font-weight: bolder;"><b>Drop-off: </b>' . $trip->trip_rental_date_drop . '</div></div>
    </div>
    </div>';
    }
    $html = '<div style="display: flex;">
                    <div style="height: 50px;width: 50px;   background-color: #f3a02e;position: absolute;
                    border-color: #ffffff;
                    border-width: 5px;
                    margin-left:25px;
                    margin-right:25px;
                   border-radius: 50%;"><img style="position:absolute;margin-top:3px;margin-left:2px" height="45px" width="45px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/20.png" /></div>
                            
                </div>

                    <div  style="background-color: #0f6fc6;width: 340px;height: 40px;position: relative;border-top-right-radius: 40px;border-bottom-right-radius: 40px;margin-left: 3%;margin-top:-45px">

                        <p style="font-size:18px;color:white;margin:0px;padding:5px;font-family:Montserrat-Medium;text-align:center;"><b>Flight Information</b></p>
                    </div>
                 
                  <div style="'.$one_way.'margin-left:45px">
                <div  style="margin-left:5%;margin-right:5%;font-family:Montserrat-Regular;">

                    <div style="border-bottom: medium solid gray;margin: 2px;"><b>Flight: </b>' . $trip->trip_dep_flight_no . '/Seat ' . $trip->trip_dep_seat_no . '
                    <div align="right" style="margin-top:-16px;text-align:right;">Date:' . $trip->trip_location_datel . '</div></div>
                    <div style="margin: 2px;"><b>Departure: </b>' . $trip->trip_location_from . '<div align="right" style="margin-top:-16px;font-weight: bolder;">' . $trip->trip_location_datel_deptime . '('.$acronym_from.')</div></div>
                    <div style="margin: 2px;"><b>Arrival: </b>' . $trip->trip_location_to . '<div align="right" style="margin-top:-16px;font-weight: bolder;">' . $trip->trip_location_datel_arrtime . '('.$acronym_to.')</div></div>
                    
                </div>
                </div>
                
                '.$rental.'
                '.$hotel_oneway.'

               
                 ';
}
$trip->setProgressing($idtrip, 20);
echo "20,";
//$html = '<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
//           <img src="'.WEB_HOSTING_URL.'images/pdf_mg2.jpg" width="100%" />           
//         </div>
//         <div align="center" style="font-size:30px; width:100%; position:fixed; padding:320px 0 0 0; color:#FFF">
//            <table align="center" width="60%" style="font-size:30px; color:#FFF" cellpadding="10">
//             <tr>
//              <td align="center"><b style="font-size:66px;">Itinerary:</b></td>
//             </tr>';
//            if($trip->trip_location_datel && $trip->trip_location_datel!='0000-00-00')
//             $html.='
//             <tr> 
//              <td align="center">Leaving: <b>'.date("F j, Y",strtotime($trip->trip_location_datel)).'</b></td>
//             </tr>'; 
//             
//            if($trip->trip_location_dater && $trip->trip_location_dater!='0000-00-00')
//             $html.='
//             <tr> 
//              <td align="center">Coming back: <b>'.date("F j, Y",strtotime($trip->trip_location_dater)).'</b></td>
//             </tr>'; 
//             
//            $html.=' <tr> 
//              <td align="center" style="border-bottom:1px solid #FFF">From : <b>'.$trip->trip_location_from.'</b></td>
//             </tr>
//             <tr> ';
//             //else{
//                 $html.='  <td align="center">To : <b>'.$trip->trip_location_to.'</b></td>
//             </tr>';
//             //}
//            // $html.='  <td align="center">To : <b>'.$trip->trip_location_to.'</b></td>
//            //  </tr>';
//            if($trip->trip_location_waypoint){
//                 $html.=' <tr><td align="center">To : <b>'.$trip->trip_location_waypoint.'</b></td></tr>';
//             }
//             if($trip->trip_location_to_drivingportion)
//             $html.='
//             <tr> 
//              <td align="center">To : <b>'.$trip->trip_location_to_drivingportion.'</b></td>
//             </tr>';
//             if($trip->trip_location_to_flightportion)
//             $html.='
//              <tr> 
//              <td align="center">To : <b>'.$trip->trip_location_to_flightportion.'</b></td>
//             </tr>';
//             if($trip->trip_location_to_trainportion)
//             $html.='
//              <tr> 
//              <td align="center">To : <b>'.$trip->trip_location_to_trainportion.'</b></td>
//             </tr>';
//         
//         $html.='    
//           </table>
//         </div>';  
//<div align="center" style="font-size:30px; position:absolute; padding:360px 0 0 300px; color:#FFF"><b style="font-size:66px;">Itinerary:</b><br />From : <b>'.$trip->trip_location_from.'</b><br /> To : <b>'.$trip->trip_location_to.'</b></div>';   
$mpdf->WriteHTML($html);
echo "25,";


//Travel Advisor from State Department

$origin_r = str_replace('(', '', $trip->trip_location_from_latlng);
$origin_r = str_replace(')', '', $origin_r);
$destination_r = str_replace('(', '', $trip->trip_location_to_latlng);
$destination_r = str_replace(')', '', $destination_r);
$destination = $destination_r;
$thewaypt = '';
if ($trip->trip_location_waypoint_latlng != '') {
    $thewaypt = str_replace('(', '', $trip->trip_location_waypoint_latlng);
    $thewaypt = str_replace(')', '', $thewaypt);
    $destination = $destination_r = $thewaypt;
}
$trip->setProgressing($idtrip, 25);

$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$html = '';
//$html .= '<div style="padding:20px;">';
$html .= '<div style=" margin-right: -15px;margin-left: -15px;">
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                    <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>TRAVEL ADVISORIES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 20px;"></div>
            </div>
        </div>';
$html .= '<div style="margin-left:25px;margin-right:25px;padding:30px;margin-bottom: 30px;margin-top: 3%;color: inherit;background-color: #eee;font-family: Montserrat-Light;">';
$have_advice = 0;
$url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination . "&sensor=false&key=" . $key;
$xml = TripPlan::getXmlFromUrl($url);
// var_dump($xml);
if ($xml->status == 'OK') {
    foreach ($xml->result->address_component as $value) {
        if ($value->type == 'country') {
            $country_long_name = $value->long_name;
            $country_short_name = $value->short_name;
        }
    }
    $url = "https://travel.state.gov/_res/rss/TAsTWs.xml";
    // print_r($url);
    $xml_Advisories = TripPlan::getXmlFromUrl($url);
    if ($xml_Advisories) {
        // echo $country_long_name.'('.$country_short_name.')<br>';
        $country_long_name = trim($country_long_name);
        foreach ($xml_Advisories->channel->item as $val) {
            $currtitle = $val->title;
            if (strstr($val->title, $country_long_name)) {

                if (strstr($val->title, ':')) {
                    $html .= '<b>' . strstr($val->title, ':', true) . '</b>';
                    $html .= strstr($val->title, ':');
                } else {
                    $html .= $val->title;
                }
                $html.='<br><br>';
//                $html.=$val->title . '</b><br><br>';
                $html.=$val->pubDate . '<br><br>';
                $html.='<a href="' . $val->link . '">' . $val->link . '</a><br><br>';
                $desc = strip_tags($val->description);
                $desc = substr($desc, 0, 1400);
                $html.=trim($desc);
                $have_advice = 1;
            }
        }
    }
}
$html .= '</div></div>';
if ($have_advice) {
    // header
    $mpdf->SetHTMLHeader('<div><div style="background: #2372b4;padding: 10px 0;height: 60px;text-align: center;">
                <div>
                    <div>
                        <div style="margin-top:10px;margin-top:10px;font-size:30px;font-family:Montserrat-Regular;color: white;">The travel plan of tomorrow done right today</div>
                    </div>
                </div>
            </div>
            <div  align="right">
                        <img style="max-height: 100px;margin-top: -55px;margin-right: 20px;" src="' . WEB_HOSTING_URL . 'images/round.png" alt="" title="" /></img>
            </div></div>');
    $mpdf->AddPage();
    $mpdf->WriteHTML($html);
}
//$html = '';   
//$html .= '<div style="padding:60px;">';   
//$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Travel Advisories</b></p>';
//$have_advice=0;
//$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key);
//         if($xml->status=='OK'){
//             foreach($xml->result->address_component as $value){
//                 if($value->type=='country'){
//                      $country_long_name= $value->long_name;
//                      $country_short_name= $value->short_name;
//                 }
//             }
//             
//             $xml_Advisories=simplexml_load_file("https://travel.state.gov/_res/rss/TAsTWs.xml");
//             if($xml_Advisories){
//                 //echo $country_long_name.'('.$country_short_name.')<br>';
//                $country_long_name = trim($country_long_name);
//                foreach($xml_Advisories->channel->item as $val){
//                    $currtitle = $val->title;
//                    if(strstr($val->title,$country_long_name)){ 
//                      $html.=$val->title.'<br><br>';
//                      $html.=$val->pubDate.'<br><br>';
//                      $html.='<a href="'.$val->link.'">'.$val->link.'</a><br><br>';
//                      $desc = strip_tags($val->description);
//                      $desc = substr($desc,0,1400);
//                      $html.=trim($desc);
//                      $have_advice =1;                      
//                   }
//                }
//                                          
//             }
//        }
//$html .= '</div>';
//if($have_advice){
//  $mpdf->AddPage();
//  $mpdf->WriteHTML($html);
//  //$mpdf->AddPage();
//}
// Flight Itinerary
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'fitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$trip->setProgressing($idtrip, 30);
echo "30,";
if ($tmp && $stmt->rowCount() > 0) { // add new page
    //$mpdf->AddPage();
    $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Additional Documents</b></p>';
    $html .= '<div style=" margin-right: -15px;margin-left: -15px;">
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>Flight Itinerary</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        </div>';
    $j = 0;
    foreach ($documents as $document) {
        // add new page
        //$mpdf->AddPage();
        $mpdf->AddPage($document->orientation);
        $j++;
        if ($j == 1)
            $mpdf->WriteHTML($html);
        if (strstr($document->name, '.pdf')) {
            //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
            // add new page
            //$mpdf->AddPage();
            $mpdf->SetImportUse();
            $file = './ajaxfiles/uploads/' . $document->name;
            $pagecount = $mpdf->SetSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage($document->orientation);
            }
        }else {
            //$html = '<div align="center"><img src="'.WEB_HOSTING_URL.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';   
            $html = '<div align="center" style="margin-top: 3%;"><img src="' . WEB_HOSTING_URL . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:420px" /></div>';
            $mpdf->WriteHTML($html);
            $html = '';
        }
    }
//    $mpdf->WriteHTML($html); 
}

// Hotel Itinerary 

$mpdf->SetHTMLHeader('<div><div style="background: #2372b4;padding: 10px 0;height: 60px;text-align: center;">
            <div>
                <div>
                    <div style="margin-top:10px;font-size:30px;font-family:Montserrat-Regular;color: white;">The travel plan of tomorrow done right today</div>
                </div>
            </div>
        </div>
        <div  align="right">
                    <img style="max-height: 100px;margin-top: -55px;margin-right: 20px;" src="' . WEB_HOSTING_URL . 'images/round.png" alt="" title="" /></img>
         </div></div>');
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'hitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
    //$mpdf->AddPage();
    $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOTEL ITINERARY</b></p>';
    $j = 0;
    foreach ($documents as $document) {
        // add new page
        $mpdf->AddPage();
        $j++;
        //if($j==1) $mpdf->WriteHTML($html);  
        if (strstr($document->name, '.pdf')) {
            //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
            // add new page
            //$mpdf->AddPage();
            $mpdf->SetImportUse();
            $file = './ajaxfiles/uploads/' . $document->name;
            $pagecount = $mpdf->SetSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
        }else { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
            $html .= '<div align="center" style="margin-top: 3%;"><img src="' . WEB_HOSTING_URL . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
            $mpdf->WriteHTML($html);
            $html = '';
        }
    }
    //$mpdf->WriteHTML($html);     
}

// additional documents
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'additional', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$trip->setProgressing($idtrip, 35);
echo "35,";
if ($tmp && $stmt->rowCount() > 0) { // add new page
    //$mpdf->AddPage();
    $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Additional Documents</b></p>';
    $html .= '<div style=" margin-right: -15px;margin-left: -15px;">
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>Additional Documents</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        </div>';
    $j = 0;
    foreach ($documents as $document) {
        // add new page
        //$mpdf->AddPage();
        $mpdf->AddPage($document->orientation);
        $j++;
        if ($j == 1)
            $mpdf->WriteHTML($html);
        if (strstr($document->name, '.pdf')) {
            //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
            // add new page
            //$mpdf->AddPage();
            $mpdf->SetImportUse();
            $file = './ajaxfiles/uploads/' . $document->name;
            $pagecount = $mpdf->SetSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage($document->orientation);
            }
        }else {
            //$html = '<div align="center"><img src="'.WEB_HOSTING_URL.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';   
            $html = '<div align="center" style="margin-top: 3%;"><img src="' . WEB_HOSTING_URL . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:420px" /></div>';
            $mpdf->WriteHTML($html);
            $html = '';
        }
    }
    //$mpdf->WriteHTML($html); 
}

// Schedule 
$html = '';
$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { //$mpdf->WriteHTML($html);   
    $mpdf->AddPage();
    $html = '';
    // $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>REMINDERS</b></p>';
    $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>SCHEDULE</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
    $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    $html .= '<div style="margin-left:25px;margin-right:25px">
            <table  style="font-family:Montserrat-Regular;width: 100%;margin-top: 3%;">
                <tr>
                    <th style="padding-top:15px;padding-bottom:15px;text-align: center;background-color: #f3a02e;font-size:xx-large; color: white;width: 50%;"
                        ><img style="margin-right:5px" height="30px" width="30px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/4.png" />Event Title
                    </th>
                    <th  style="padding-top:15px;padding-bottom:15px;text-align: center;background-color: #f3a02e;font-size:xx-large; color: white;width: 50%;"
                        ><img style="margin-right:5px" height="30px" width="30px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/3.png" />Event Date & Time
                    </th>
                </tr>

            
            ';
    foreach ($timelines as $timeline) {
        $html .= '
                    <tr>
                    <td  style="padding-top:15px;padding-bottom:15px;border-bottom:3px solid #444444;text-align: center;width:50%">' . $timeline->title . '</td>
                    <td  style="padding-top:15px;padding-bottom:15px;border-bottom:3px solid #444444;text-align: center;width:50%">' . date('d F Y h:i a', strtotime($timeline->date)) . '</td>
                   </tr>';
    }
    $html .= '</table></div>';
    $mpdf->WriteHTML($html);
}

// Trip Notes
$html = '';
$stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
//$_tmp = $stmt->errorInfo(); $aux = '----------- '.$idtrip.'--------'.$_tmp[0].'--------'.$_tmp[1].'--------'.$_tmp[2].'--------'.$stmt->rowCount(); $mpdf->WriteHTML($aux);
$i = 1;
$trip->setProgressing($idtrip, 40);
echo "40,";
if ($tmp && $stmt->rowCount() > 0) { //$mpdf->WriteHTML($html);   
    $mpdf->AddPage();
    //$html = '';
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TRIP NOTES<b></p>';
    $html .= '<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>TRIP NOTES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>';
    $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    //$html .= '<table width="65%" align="center" cellspacing="10">';
    $html .= '<div style="font-family: Montserrat-Regular;border:1px solid #0f6fc6; padding-top: 30px;padding-bottom: 30px;margin-bottom: 30px;color: inherit;background-color: #ffffff;margin-left:25px;margin-right:25px;margin-top: 3%;">';
    foreach ($notes as $note) {
//        $html .= '<tr>
//                   <td width="10%" align="center" valign="top"><img src="' . WEB_HOSTING_URL . 'images/pdf_icon2.png" /></td>
//                   <td width="90%" align="left" valign="top" style="color:#444444; font-family: OpenSans; font-size:20px">' . $i . '. ' . $note->text . '</td>
//                   </tr>';
        $html .= '
	<div style="margin-top:5px;margin-left: 25px;margin-right: 25px;"> 
    <div style="float: right; width: 95%;padding-top:15px">' . $note->text . '</div>
        <div style="float: left; width:5%;">
        <div  style="background-color: #f3a02e;width: 40px;height: 40px;position: relative;border-radius: 50%;margin-top:7px">
                     <div style="color:#ffffff;margin-top:8px;font-size:18px" align="center" ><b> ' . $i . ' </b></div>
                    </div>
        </div>
        <div style="clear: both; margin: 0pt; padding: 0pt; "></div>
        </div>
     ';
        $i++;
    }
    $html .= '</div>';
//    $html .= '</tabl  e>';
    $mpdf->WriteHTML($html);
}


// Route 
$mpdf->AddPage();
$html = '';
$html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ROUTE</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
//$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE</b></p>';
$origin_r = str_replace('(', '', $trip->trip_location_from_latlng);
$origin_r = str_replace(')', '', $origin_r);
$destination_r = str_replace('(', '', $trip->trip_location_to_latlng);
$destination_r = str_replace(')', '', $destination_r);
$destination = $destination_r;
$location_multi_waypoint_latlng = $trip->location_multi_waypoint_latlng;
$trip_via_waypoints = $trip->trip_via_waypoints;

$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
// $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origin_r), str_replace(' ', '', $destination_r), $key, $trip->trip_transport, '440x200', str_replace(' ', '', $thewaypt));
$map_url = $trip->getStaticGmapURLForDirectionV2(str_replace(' ', '', $origin_r), str_replace(' ', '', $destination_r), $key, $trip->trip_transport, '440x200', str_replace(' ', '', $location_multi_waypoint_latlng), $trip_via_waypoints);
$html .= '<img style="margin-left:25px;margin-right:25px;margin-top: 3%;" src="' . $map_url . '" width="100%" />';
$mpdf->WriteHTML($html);

/* if($trip->trip_location_from_latlng_drivingportion){
  // portion Route by driving
  $mpdf->AddPage();
  $html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
  $origind = str_replace('(','',$trip->trip_location_from_latlng_drivingportion);
  $origind = str_replace(')','',$origind);
  $destinationd = str_replace('(','',$trip->trip_location_to_latlng_drivingportion);
  $destinationd = str_replace(')','',$destinationd);
  $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
  $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$origind), str_replace(' ','',$destinationd), $key, 'vehicle');
  $html .= '<img src="'.$map_url.'" width="100%" />';
  $mpdf->WriteHTML($html);
  } */
if ($trip->trip_option_directions) {
    // Route Directions
    $mpdf->AddPage();
    $html = '';
    // $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
    //             <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ROUTE DRIVING</b></p>
    //             <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
    //         </div>
    //     ';
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
    if ($trip->trip_transport == 'vehicle') {
        $origin_D = str_replace('(', '', $trip->trip_location_from_latlng);
        $origin_D = str_replace(')', '', $origin_D);
        $destination_D = str_replace('(', '', $trip->trip_location_to_latlng);
        if ($trip->trip_location_waypoint_latlng != '') {
            $destination_D = str_replace('(', '', $trip->trip_location_to_latlng);
        }
        $destination_D = str_replace(')', '', $destination_D);
    } elseif ($trip->trip_location_from_drivingportion && $trip->trip_location_to_drivingportion) {
        $origin_D = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion);
        $origin_D = str_replace(')', '', $origin_D);
        $destination_D = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
        $destination_D = str_replace(')', '', $destination_D);
    }
    $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
    $key2 = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
    $map_data = $trip->getStaticGmapForDirections(str_replace(' ', '', $origin_D), str_replace(' ', '', $destination_D), $key, $key2,'640x430');
    // print_r($map_data);
    $html .= '<div style="background-image:url(\''.$map_data['url'].'\');background-repeat: no-repeat;background-color:white;background-position: center;height: 700px;background-size:contain;">' .
                '<p style="margin-top: 300px; margin-left: 500px;width: 120px;background-color: white;color: black;font-size: 16px;padding: 8px 10px;">' .
                    '<img src="'.WEB_HOSTING_URL.'images/car_icon.png" style="height:16px;width: auto;"> '.$map_data['distance'].'<br>'.$map_data['estimateTime'] .
                '</p></div>';
    $mpdf->WriteHTML($html);
    $html = '';
} elseif ($trip->trip_location_from_latlng_drivingportion) {
    // portion Route by driving
    $mpdf->AddPage();
    $html = '';
    //  $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
    //             <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ROUTE DRIVING</b></p>
    //             <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
    //         </div>
    //     ';
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
    $origind = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion);
    $origind = str_replace(')', '', $origind);
    $destinationd = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
    $destinationd = str_replace(')', '', $destinationd);
    $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
    $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origind), str_replace(' ', '', $destinationd), $key,'vehicle', '440x200');
    $html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;"  width="440" height="200"  src="' . $map_url . '" width="100%" />';
    $mpdf->WriteHTML($html);
}
$trip->setProgressing($idtrip, 45);
echo "45,";

if ($trip->trip_location_from_latlng_trainportion) {
// portion Route by train
    $mpdf->AddPage();
    $html = '';
     $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ROUTE by TRAIN</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
   // $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE by TRAIN</b></p>';
    $origint = str_replace('(', '', $trip->trip_location_from_latlng_trainportion);
    $origint = str_replace(')', '', $origint);
    $destinationt = str_replace('(', '', $trip->trip_location_to_latlng_trainportion);
    $destinationt = str_replace(')', '', $destinationt);
    $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
    $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origint), str_replace(' ', '', $destinationt), $key, 'train','440x200');
    $html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;"  width="440" height="200"  src="' . $map_url . '" width="100%" />';
    $mpdf->WriteHTML($html);
}

if ($trip->trip_location_from_latlng_flightportion) {
// portion Route flight
    $mpdf->AddPage();
    $html = '';
    $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>FLIGHT ROUTE</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
   // $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>FLIGHT ROUTE</b></p>';
    $originf = str_replace('(', '', $trip->trip_location_from_latlng_flightportion);
    $originf = str_replace(')', '', $originf);
    $destinationf = str_replace('(', '', $trip->trip_location_to_latlng_flightportion);
    $destinationf = str_replace(')', '', $destinationf);
    $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
    $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $originf), str_replace(' ', '', $destinationf), $key, 'plane','440x200');
    $html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;"   src="' . $map_url . '" width="100%" />';
    $mpdf->WriteHTML($html);
}
// print_r($mpdf);
// Route Destination  
$mpdf->AddPage();
$html = '';
$html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ROUTE DESTINATION</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
//$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=12&size=640x300&scale=2&maptype=hybrid&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=13&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
// $html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $destination . '&zoom=10&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C' . $destination . '&size=440x200&scale=2&maptype=roadmap&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
$html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $destination . '&zoom=10&style=feature:all|element:labels.text&markers=color:0xff0000%7C' . $destination . '&size=440x200&scale=2&maptype=roadmap&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';

$mpdf->WriteHTML($html);

// Detailed Route Destination  
$mpdf->AddPage();
$html = '';
$html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>DETAILED ROUTE DESTINATION</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
//$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DETAILED ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&size=640x300&scale=2&maptype=hybrid&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$destination.'&zoom=14&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C'.$destination.'&size=640x300&scale=2&maptype=roadmap&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
$html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px;" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $destination . '&zoom=15&scale=2&size=440x200&maptype=roadmap&markers=color:green%7Clabel:A%7C' . $destination . '&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
$mpdf->WriteHTML($html);

$trip->setProgressing($idtrip, 50);
echo "50,";

if ($trip->trip_option_busmap || $trip->trip_option_weather) {
//echo "https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key.'<br><br>';
//$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key) or die("Error: Cannot create object xml");
    $url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination . "&sensor=false&key=" . $key;
    $xml = TripPlan::getXmlFromUrl($url);
    if ($xml->status == 'OK') {
        foreach ($xml->result[1]->address_component as $value) {
            // echo $value->type.', '.$value->long_name.'<br>';
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
}

//embassies
$html = '';
if ($trip->trip_option_embassis) {
   // $mpdf->AddPage();
    $html = $trip->getMapEmbassis($destination, $key, $trip->trip_list_embassis);
   // $mpdf->WriteHTML($html);
   if($html!=NULL){
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
    }
    
}

$html = '';
// weather  
if ($trip->trip_option_weather) {
    $mpdf->AddPage();
//    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>WEATHER AT ' . $locality_long_name . '</b><p>';
    //$html .= $trip->getWeatherFilters($locality_long_name,1);
    $html .= '<div style="margin-left: 25px;margin-right: 25px;">
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>WEATHER AT ' . $locality_long_name . '</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>';
    $html .= $trip->getAccuWeatherFilters(1);
    $html .= '</div>';
    $mpdf->WriteHTML($html);
}



// header
$mpdf->SetHTMLHeader('<div><div style="background: #2372b4;padding: 10px 0;height: 60px;text-align: center;">
            <div>
                <div>
                    <div style="margin-top:10px;font-size:30px;font-family:Montserrat-Regular;color: white;">The travel plan of tomorrow done right today</div>
                </div>
            </div>
        </div>
        <div  align="right">
                    <img style="max-height: 100px;margin-top: -55px;margin-right: 20px;" src="' . WEB_HOSTING_URL . 'images/round.png" alt="" title="" /></img>
         </div></div>');

// footer         
$mpdf->SetHTMLFooter('<table width="100%">
    <tr>
        
        <td width="50%" align="left" style="padding-left:25px;text-align:left;font-size:14px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="50%" align="right" style="padding-right:25px;text-align: right; color:#666">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

// Passport
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'passport', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
    //$mpdf->AddPage();
    $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
    $j = 0;
    foreach ($documents as $document) {
        // add new page
        $mpdf->AddPage();
        //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PASSPORT</b></p>';
        $html .= '<div style=" margin-right: -15px;margin-left: -15px;">
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>PASSPORT</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        </div>';
        $j++;
        //if($j==1) $mpdf->WriteHTML($html);  
        if (strstr($document->name, '.pdf')) {
            //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
            // add new page
            //$mpdf->AddPage();
            $mpdf->SetImportUse();
            $file = './ajaxfiles/uploads/' . $document->name;
            $pagecount = $mpdf->SetSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
        }else { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
            $html .= '<div align="center" style="margin-top: 3%;"><img src="' . WEB_HOSTING_URL . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:420px" /></div>';
            $mpdf->WriteHTML($html);
            $html = '';
        }
    }
    //$mpdf->WriteHTML($html); 
}
$trip->setProgressing($idtrip, 55);
echo "55,";
// Driver's License
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'driver', PDO::PARAM_STR);
$stmt->bindValue(2, $idtrip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
    //$mpdf->AddPage();
    $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DRIVER\'S LICENSE</b></p>';
    $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>DRIVER\'S LICENSE</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
       ';
    $j = 0;
    foreach ($documents as $document) {
        // add new page
        $mpdf->AddPage();
        $j++;
        //if($j==1) $mpdf->WriteHTML($html);  
        if (strstr($document->name, '.pdf')) {
            //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
            // add new page
            $mpdf->AddPage();
            $mpdf->SetImportUse();
            $file = './ajaxfiles/uploads/' . $document->name;
            $pagecount = $mpdf->SetSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
        }else { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
            $html .= '<div align="center" style="margin-top: 3%;"><img src="' . WEB_HOSTING_URL . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:420px" /></div>';
            $mpdf->WriteHTML($html);
            $html = '';
        }
    }
    //$mpdf->WriteHTML($html);     
}


$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';




//subway map  
$html = '';
if ($trip->trip_option_subway) {
    $mpdf->AddPage();
    //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SUBWAY MAP</b></p>';
    $html .='<div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>SUBWAY MAP</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
        ';
    $dir =  WEB_HOSTING_URL . "subwaymap/";
    $indir = 0;
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (filetype($dir . $file) == 'file') {
                    $filename = substr($file, 0, -4);
                    $filename = str_replace('-', ' ', $filename);
                    if (stristr($trip->trip_location_to, $filename)) { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.$dir.$file.'" width="100%" style="max-height:530px" /></div>';
                        $html .= '<div style="margin-top: 3%;" align="center" ><img style="margin-top: 3%;margin-left:25px;margin-right:25px" src="' . $dir . $file . '" width="100%" style="max-height:460px" /></div>';
                        $indir = 1;
                        echo $filename;
                        break;
                    }
                }
            }
            closedir($dh);
        }
    }
    if (!$indir)
        $html .= '<img style="margin-top: 3%;margin-left:25px;margin-right:25px" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $trip->trip_location_to . '&style=feature:transit.line|element:all|visibility:simplified|color:0xFF6319&zoom=13&size=440x200&scale=2&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
        $mpdf->WriteHTML($html);
}
$trip->setProgressing($idtrip, 60);
echo "60,";
//bus map 
$html = '';
if ($trip->trip_option_busmap) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS MAP IMAGE</b></p>';

    $dir =  WEB_HOSTING_URL . "busmap/";
    $indir = 0;
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (filetype($dir . $file) == 'file') {
                    $filename = substr($file, 0, -4);
                    $filename = str_replace('-', ' ', $filename);
                    $filename = str_replace(',', ' ', $filename);
                    if (!empty($political_long_name)) {
                        if (stristr($filename, $political_long_name)) { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
                            $html .= '<div align="center"><img src="' . $dir . $file . '" width="100%" style="max-height:520px"/></div>';
                            $indir = 1;
                            break;
                        }
                    } else {
                        if (stristr($filename, $locality_long_name)) { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
                            $html .= '<div align="center"><img src="' . $dir . $file . '" width="100%" style="max-height:520px"/></div>';
                            $indir = 1;
                            break;
                        }
                    }
                }
            }

            closedir($dh);
        }
    }
    if (!$indir) { //$html .= '<div align="center"><img src="'.WEB_HOSTING_URL.$dir.'noimage.png" width="100%" style="max-height:530px" /></div>';
        $html .= '<div align="center"><img src="' . $dir . 'noimage.png" width="100%" style="max-height:420px" /></div>';
    }

    $mpdf->WriteHTML($html);
}



//filters
$html = '';
if ($trip->trip_option_hotels) {
    $data = $trip->getMapFilters($destination, 'lodging', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//		  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOTELS/MOTELS</b></p>';
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>HOTELS/MOTELS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/0.png"/></i></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                    ' . $data . '
                </table>
        </div>';
//        $html .= $data; //$trip->getMapFilters($destination, 'lodging' , $key);
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_police) {
    $data = $trip->getMapFilters($destination, 'police', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>POLICE STATIONS</b></p>';
//        $html .= $data; //$trip->getMapFilters($destination, 'police' , $key);
        $html = '<div>
                <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                    <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>POLICE STATIONS</b></p>
                    <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
                </div>
                    <div  style="margin-top: 3%;text-align: center;width:100%" >
                        <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/7.png"/></div>
                    </div>
                    <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                        <tr style="background-color: #faddb6;">
                            <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                            <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                            <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                            <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                        </tr>

                        ' . $data . '
                    </table>
            </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 65);
echo "65,";
$html = '';
if ($trip->trip_option_hospitals) {
    $data = $trip->getMapFilters($destination, 'hospital', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOSPITALS</b></p>';
//        $html .= $data; //$trip->getMapFilters($destination, 'hospital' , $key);
        $html = '<div>
             <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>HOSPITALS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/6.png"/></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_gas) {
    $data = $trip->getMapFilters($destination, 'gas_station', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SERVICE STATIONS (GAS/PETROL/DIESEL)</b></p>';
//        $html .= $data; //$trip->getMapFilters($destination, 'gas_station' , $key);
        $html = '<div>
             <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>SERVICE STATIONS (GAS/PETROL/DIESEL)</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/10.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_taxi) {
    $data = $trip->getMapFilters($destination, 'taxi_stand', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TAXI SERVICES</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>TAXI SERVICES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/11.png"/></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_airfields) {
    $data = $trip->getMapFilters($destination, 'airport', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>AIRFIELDS</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>AIRFIELDS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/12.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 70);
echo "70,";
$html = '';
if ($trip->trip_option_parking) {
    $data = $trip->getMapFilters($destination, 'parking', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PARKING</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>PARKING</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/13.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_university) {
    $data = $trip->getMapFilters($destination, 'school', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>UNIVERSITIES</b></p>';
//        $html .= $data;
        $html = '<div>
           <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>UNIVERSITIES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/14.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_atm) {
    $data = $trip->getMapFilters($destination, 'atm', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ATM</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>ATM</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/15.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 75);
echo "75,";
$html = '';
if ($trip->trip_option_subway_station) {
    $data = $trip->getMapFilters($destination, 'subway_station', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Subway Stations</b></p>';
//        $html .= $data;
        $html = '<div>
           <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>SUBWAY STATIONS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/8.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_metro) {
    $data = $trip->getMapFilters($destination, 'train_station', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Metro Stations</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>METRO STATIONS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/16.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_playground) {
    $data = $trip->getMapFilters($destination, 'park', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Parks</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>PARKS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/17.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 80);
echo "80,";
$html = '';
if ($trip->trip_option_museum) {
    $data = $trip->getMapFilters($destination, 'museum', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>MUSEUMS</b></p>';
//        $html .= $data;
        $html = '<div>
           <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>MUSEUMS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/9.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 85);
echo "85,";
$html = '';
if ($trip->trip_option_library) {
    $data = $trip->getMapFilters($destination, 'library', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>LIBRARIES</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>LIBRARIES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/18.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
if ($trip->trip_option_pharmacy) {
    $data = $trip->getMapFilters($destination, 'pharmacy', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PHARMACIES</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>PHARMACIES</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/2.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$trip->setProgressing($idtrip, 90);
echo "90,";
$html = '';
if ($trip->trip_option_church) {
    $data = $trip->getMapFilters($destination, 'church', $key);
    if (!empty($data)) {
        $mpdf->AddPage();
//        $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>RELIGIOUS INSTITUTIONS</b></p>';
//        $html .= $data;
        $html = '<div>
            <div style="position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                <p style="margin-bottom:5px;font-family: Montserrat-Medium;color: #343b4a; font-size: 26px;color: #111;text-transform: uppercase;text-align: center;"><b>RELIGIOUS INSTITUTIONS</b></p>
                <div style=" width:50px;height: 3px;background: #f3a02e;margin: 0 auto;margin-bottom: 10px;"></div>
            </div>
                <div  style="margin-top: 3%;text-align: center;width:100%" >
                    <div style="margin: 0 auto;width: 100px;height: 50px;background-color: #faddb6;border-top-left-radius: 200px;border-top-right-radius: 200px;border: 0px solid gray;border-left: 0;text-align: center;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"><img style="position:absolute;margin-top:5px" height="55px" width="55px" src="' . WEB_HOSTING_URL . 'images/pdf-icon/19.png" /></div>
                </div>
                <table style="margin-left:25px;margin-right:25px;font-family:Montserrat-Regular;width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;background-color: transparent;border-color: white" >
                    <tr style="background-color: #faddb6;">
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Name</th>
                        <th style="text-align: left;width: 30%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Address</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Distance from destination point</th>
                        <th style="text-align: left;width: 20%;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;">Destination</th>
                    </tr>
                   
                    ' . $data . '
                </table>
        </div>';
        $mpdf->WriteHTML($html);
    }
}
$html = '';
$trip->setProgressing($idtrip, 95);
echo "95,";
/* if ($trip->trip_option_busmap)
  { $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS STATIONS</b></p>';
  $data = $trip->getMapFilters($destination, 'bus_station' , $key);
  if(!empty($data)){
  $mpdf->AddPage();
  $html .= $data;
  $mpdf->WriteHTML($html);
  }
  } */

// footer         
$mpdf->SetHTMLFooter('<table width="100%">
    <tr>
        
        <td width="50%" align="left" style="padding-left:25px;text-align:left;font-size:14px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="50%" align="right" style="padding-right:25px;text-align: right; color:#666">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

//$mpdf->WriteHTML($html); 
$html = '';

//Thank you for choosing PLANIVERSITY!
$mpdf->SetHTMLHeader(' ');
$mpdf->AddPage('', '', '', '', '', 0, 0, 0, 0);
//$html = '<div align="center" style="font-size:40px; padding-top:360px; background-color:#1C72B4; height:100%; color:#FFF">Thank you for choosing PLANIVERSITY!</div>';
$html = '<div align="center" style="font-size:40px; padding-top:360px; background-color:#76889A; height:100%; color:#FFF">Thank you for choosing PLANIVERSITY!</div>';
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter(' ');
$triptitle = trim($trip->trip_title);
$triptitle = str_replace('&#39;', '_', $triptitle);
$triptitle = str_replace(' ', '_', $triptitle);

$pdfname = $triptitle . '-' . $idtrip . '-' . $userdata['id'];
echo "96,";
$pdfpath = 'pdf/' . $pdfname . '.pdf';
// delete if exist
if (file_exists($pdfpath))
    unlink($pdfpath);
try {
    $mpdf->Output(WEB_HOSTING_URL.'pdf/' . $pdfname . '.pdf', \Mpdf\Output\Destination::FILE);
}
catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}

$trip->edit_data_pdf($idtrip);

//echo WEB_HOSTING_URL.'pdf/'.$pdfname.'.pdf';
//$mpdf->Output();
$trip->setProgressing($idtrip, 100);
print_r($trip->error);
echo "100,";
echo "OK";
?>