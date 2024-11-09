<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'trip/pdf/' . $_POST['idtrip'];
  header("Location:" . SITE . "login");
}

include("class/class.Plan.php");
$plan = new Plan();
if (!$plan->check_plan($userdata['id'])) { //header("Location:".SITE."billing/".$_POST['idtrip']);
  //exit();     
}

include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_POST["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip)) header("Location:" . SITE . "trip/how-are-you-traveling");
$trip->get_data($id_trip);

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
  if ($trip->trip_location_triptype == 'o') $enddate = $startdate;
  $day = date('j', strtotime($startdate));
  $month = date('n', strtotime($startdate));
  $year = date('Y', strtotime($startdate));
  $event['start_time'] = str_replace('-', '', $startdate); //date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(0,0,0,$month,$day,$year)).'Z'; //date('c',mktime(0,0,0,$month,$day,$year));
  $event['start_time2'] = date('D, j M Y', mktime(0, 0, 0, $month, $day, $year));
  $day = date('j', strtotime($enddate));
  $month = date('n', strtotime($enddate));
  $year = date('Y', strtotime($enddate));
  $event['end_time'] = str_replace('-', '', $enddate);; //date('Ymd',mktime(0,0,0,$month,$day,$year)).'T'.date('His',mktime(23,59,0,$month,$day,$year)).'Z'; //date('c',mktime(23,59,0,$month,$day,$year));
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
  $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
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
									  Star Date: ' . $event['start_time2'] . '<br/>
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
    'OpenSans' => [
      'R' => 'OpenSans-Light-webfont.ttf',
    ],
    'OpenSansRegular' => [
      'R' => 'OpenSans-Regular-webfont.ttf',
    ],
    'OpenSansBold' => [
      'R' => 'OpenSans-Bold-webfont.ttf',
    ],
    'MyriadProBold' => [
      'R' => 'MyriadPro-Bold_1.ttf',
    ]
  ],
  'default_font' => 'MyriadProBold'
]);

$_to = explode(',', $trip->trip_location_to);

$html = '<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
           <img src="' . SITE . 'images/pdf_mg1.jpg" width="100%" />           
         </div>
         <div align="center" style="font-size:56px; position:fixed; width:100%; padding:60px 0 0 0; color:#FFF"><p align="center"><b>' . $trip->trip_title . '</b></p></div>';

$mpdf->WriteHTML($html);
$html = '';

// header
$mpdf->SetHTMLHeader('<div width="100%" align="center" style="border-bottom:1px solid #86BFEA; padding-bottom:10px">
     <img src="' . SITE . 'images/pdf_innerlogo.png" /><br />
     <font style="font-size:20px; color:#1F72B2; font-family: OpenSans">The travel plan of tomorrow done right today</font>
</div>');

// footer         
$mpdf->SetHTMLFooter('<table width="95%">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center" style="font-size:12px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="33%" style="text-align: right; color:#666">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

// Passport
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'passport', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
  //$mpdf->AddPage();
  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PASSPORT</b></p>';
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
    } else { //$html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
      $html .= '<div align="center"><img src="' . SITE . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
      $mpdf->WriteHTML($html);
      $html = '';
    }
  }
  //$mpdf->WriteHTML($html); 
}

// Driver's License
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'driver', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
  //$mpdf->AddPage();
  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DRIVER\'S LICENSE</b></p>';
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
    } else { //$html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
      $html .= '<div align="center"><img src="' . SITE . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
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
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
// header
$mpdf->SetHTMLHeader('');
if ($tmp && $stmt->rowCount() > 0) { // add new page
  //$mpdf->AddPage();
  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
  // $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Additional Documents</b></p>';
  $j = 0;
  foreach ($documents as $document) {
    // add new page
    //$mpdf->AddPage();
    $mpdf->AddPage($document->orientation);
    $j++;
    if ($j == 1) $mpdf->WriteHTML($html);
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
    } else {
      //$html = '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';   
      $html = '<div align="center"><img src="' . SITE . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
      $mpdf->WriteHTML($html);
      $html = '';
    }
  }
  //$mpdf->WriteHTML($html); 
}

// header
$mpdf->SetHTMLHeader('<div width="100%" align="center" style="border-bottom:1px solid #86BFEA; padding-bottom:10px">
     <img src="' . SITE . 'images/pdf_innerlogo.png" /><br />
     <font style="font-size:20px; color:#1F72B2; font-family: OpenSans">The travel plan of tomorrow done right today</font>
</div>');

$html = '';

// footer         
$mpdf->SetHTMLFooter('<table width="95%">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center" style="font-size:12px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="33%" style="text-align: right; color:#666">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

// Itinerary    
$mpdf->AddPage();
$html = '<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
           <img src="' . SITE . 'images/pdf_mg2.jpg" width="100%" />           
         </div>
         <div align="center" style="font-size:30px; width:100%; position:fixed; padding:320px 0 0 0; color:#FFF">
            <table align="center" width="60%" style="font-size:30px; color:#FFF" cellpadding="10">
             <tr>
              <td align="center"><b style="font-size:66px;">Itinerary:</b></td>
             </tr>';
if ($trip->trip_location_datel && $trip->trip_location_datel != '0000-00-00')
  $html .= '
             <tr> 
              <td align="center">Leaving: <b>' . date("F j, Y", strtotime($trip->trip_location_datel)) . '</b></td>
             </tr>';

if ($trip->trip_location_dater && $trip->trip_location_dater != '0000-00-00')
  $html .= '
             <tr> 
              <td align="center">Coming back: <b>' . date("F j, Y", strtotime($trip->trip_location_dater)) . '</b></td>
             </tr>';

$html .= ' <tr> 
              <td align="center" style="border-bottom:1px solid #FFF">From : <b>' . $trip->trip_location_from . '</b></td>
             </tr>
             <tr> ';
//else{
$html .= '  <td align="center">To : <b>' . $trip->trip_location_to . '</b></td>
             </tr>';
//}
// $html.='  <td align="center">To : <b>'.$trip->trip_location_to.'</b></td>
//  </tr>';
if ($trip->trip_location_waypoint) {
  $html .= ' <tr><td align="center">To : <b>' . $trip->trip_location_waypoint . '</b></td></tr>';
}
if ($trip->trip_location_to_drivingportion)
  $html .= '
             <tr> 
              <td align="center">To : <b>' . $trip->trip_location_to_drivingportion . '</b></td>
             </tr>';
if ($trip->trip_location_to_flightportion)
  $html .= '
              <tr> 
              <td align="center">To : <b>' . $trip->trip_location_to_flightportion . '</b></td>
             </tr>';
if ($trip->trip_location_to_trainportion)
  $html .= '
              <tr> 
              <td align="center">To : <b>' . $trip->trip_location_to_trainportion . '</b></td>
             </tr>';

$html .= '    
           </table>
         </div>';
//<div align="center" style="font-size:30px; position:absolute; padding:360px 0 0 300px; color:#FFF"><b style="font-size:66px;">Itinerary:</b><br />From : <b>'.$trip->trip_location_from.'</b><br /> To : <b>'.$trip->trip_location_to.'</b></div>';   
$mpdf->WriteHTML($html);

// Hotel Itinerary 
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'hitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
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
    } else { //$html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
      $html .= '<div align="center"><img src="' . SITE . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
      $mpdf->WriteHTML($html);
      $html = '';
    }
  }
  //$mpdf->WriteHTML($html);     
}

// Flight Itinerary
$html = '';
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
$stmt->bindValue(1, 'fitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { // add new page
  //$mpdf->AddPage();
  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>FLIGHT ITINERARY</b></p>';
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
    } else { //$html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
      $html .= '<div align="center"><img src="' . SITE . 'ajaxfiles/uploads/' . $document->name . '" width="100%" style="max-height:520px" /></div>';
      $mpdf->WriteHTML($html);
      $html = '';
    }
  }
  //$mpdf->WriteHTML($html);     
}



$origin = str_replace('(', '', $trip->trip_location_from_latlng);
$origin = str_replace(')', '', $origin);
$destination = str_replace('(', '', $trip->trip_location_to_latlng);
$destination = str_replace(')', '', $destination);
if ($trip->trip_location_waypoint_latlng != '') {
  $destination = str_replace('(', '', $trip->trip_location_waypoint_latlng);
  $destination = str_replace(')', '', $destination);
}

if (!empty($trip->trip_option_circle)) {
  $circle_data = explode('::', $trip->trip_option_circle);
  $lat_to = $circle_data[0];
  $lng_to = $circle_data[1];
  $radius = $circle_data[2];
  $radius = round($radius * $factor);
  $showclear = 1;
  $destination_circle = $lat_to . ',' . $lng_to;
}

if ($trip->trip_transport == 'plane') {
  if ($trip->trip_location_from_latlng_drivingportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
    $destination = str_replace(')', '', $destination);
  }
  if ($trip->trip_location_from_latlng_trainportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_trainportion);
    $destination = str_replace(')', '', $destination);
  }
}

if ($trip->trip_transport == 'vehicle') {
  if ($trip->trip_location_from_latlng_flightportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_flightportion);
    $destination = str_replace(')', '', $destination);
  }
  if ($trip->trip_location_from_latlng_trainportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_trainportion);
    $destination = str_replace(')', '', $destination);
  }
}
if ($trip->trip_transport == 'train') {
  if ($trip->trip_location_from_latlng_flightportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_flightportion);
    $destination = str_replace(')', '', $destination);
  }
  if ($trip->trip_location_from_latlng_drivingportion) {
    $destination = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
    $destination = str_replace(')', '', $destination);
  }
}

$key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';

//Travel Advisor from State Department
$html = '';
$html .= '<div style="padding:60px;">';
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Travel Advisories</b></p>';
$have_advice = 0;
$xml = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination . "&sensor=false&key=" . $key);
if ($xml->status == 'OK') {
  foreach ($xml->result->address_component as $value) {
    if ($value->type == 'country') {
      $country_long_name = $value->long_name;
      $country_short_name = $value->short_name;
    }
  }

  $xml_Advisories = simplexml_load_file("https://travel.state.gov/_res/rss/TAsTWs.xml");
  if ($xml_Advisories) {
    //echo $country_long_name.'('.$country_short_name.')<br>';
    $country_long_name = trim($country_long_name);
    foreach ($xml_Advisories->channel->item as $val) {
      $currtitle = $val->title;
      if (strstr($val->title, $country_long_name)) {
        $html .= $val->title . '<br><br>';
        $html .= $val->pubDate . '<br><br>';
        $html .= '<a href="' . $val->link . '">' . $val->link . '</a><br><br>';
        $desc = strip_tags($val->description);
        $desc = substr($desc, 0, 1400);
        $html .= trim($desc);
        $have_advice = 1;
      }
    }
  }
}
$html .= '</div>';
if ($have_advice) {
  $mpdf->AddPage();
  $mpdf->WriteHTML($html);
  //$mpdf->AddPage();
}


// Reminders 
$html = '';
$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount() > 0) { //$mpdf->WriteHTML($html);   
  $mpdf->AddPage();
  //$html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>REMINDERS</b></p>';
  $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
  $html .= '<table width="90%" align="center" cellspacing="5" cellpadding="10">
               <tr>
                 <td width="49%">                 
                 <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="30px"></td>
                      <td style="font-family:OpenSansBold; font-size:15px; color:#444444"><b>Event Title</b></td>
                    </tr>
                 </table>
                 </td>
                 <td width="2%"></td>
                 <td width="49%">
                 <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="30px"></td>
                      <td style="font-family:OpenSansBold; font-size:15px; color:#444444"><b>Event Date & Time</b></td>
                    </tr>
                 </table></td>
               </tr>';
  foreach ($timelines as $timeline) {
    $html .= '<tr>
                    <td bgcolor="#EFF7FC">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                         <tr>
                           <td width="30px" valign="middle"><img src="' . SITE . 'images/pdf_icon1.png" style="vertical-align:central" /></td>
                           <td valign="middle" style="font-family:OpenSansRegular; font-size:16px; color:#545454">' . $timeline->title . '</td>
                         </tr>
                      </table>
                    </td>
                    <td></td>
                    <td bgcolor="#EFF7FC">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                         <tr>
                           <td width="30px" valign="middle"></td>
                           <td valign="middle" style="font-family:OpenSansRegular; font-size:16px; color:#545454">' . date('d F Y h:i a', strtotime($timeline->date)) . '</td>
                         </tr>
                      </table>
                    </td>                    
                   </tr>';
  }
  $html .= '</table>';
  $mpdf->WriteHTML($html);
}


// Trip Notes
$html = '';
$stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=? ORDER BY date");
$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
//$_tmp = $stmt->errorInfo(); $aux = '----------- '.$id_trip.'--------'.$_tmp[0].'--------'.$_tmp[1].'--------'.$_tmp[2].'--------'.$stmt->rowCount(); $mpdf->WriteHTML($aux);
$i = 1;
if ($tmp && $stmt->rowCount() > 0) { //$mpdf->WriteHTML($html);   
  $mpdf->AddPage();
  //$html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TRIP NOTES<b></p>';
  $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
  $html .= '<table width="65%" align="center" cellspacing="10">';
  foreach ($notes as $note) {
    $html .= '<tr>
                   <td width="10%" align="center" valign="top"><img src="' . SITE . 'images/pdf_icon2.png" /></td>
                   <td width="90%" align="left" valign="top" style="color:#444444; font-family: OpenSans; font-size:20px">' . $i . '. ' . $note->text . '</td>
                   </tr>';
    $i++;
  }
  $html .= '</table>';
  $mpdf->WriteHTML($html);
}


if ($trip->trip_option_busmap || $trip->trip_option_weather) {
  //echo "https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key.'<br><br>';
  //$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key) or die("Error: Cannot create object xml");
  $xml = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination . "&sensor=false&key=" . $key);
  if ($xml->status == 'OK') {
    foreach ($xml->result[1]->address_component as $value) {
      //echo $value->type.', '.$value->long_name.'<br>';
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

$html = '';

// Filters  
if ($trip->trip_option_weather) {
  $mpdf->AddPage();
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>WEATHER AT ' . $locality_long_name . '</b><p>';
  //$html .= $trip->getWeatherFilters($locality_long_name,1);
  $html .= $trip->getAccuWeatherFilters(1);
  $mpdf->WriteHTML($html);
}

$html = '';
if ($trip->trip_option_embassis) {
  $mpdf->AddPage();
  $html .= $trip->getMapEmbassis($destination, $key, $trip->trip_list_embassis);
  $mpdf->WriteHTML($html);
}

//subway map  
$html = '';
if ($trip->trip_option_subway) {
  $mpdf->AddPage();
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SUBWAY MAP</b></p>';
  $dir = "subwaymap/";
  $indir = 0;
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if (filetype($dir . $file) == 'file') {
          $filename = substr($file, 0, -4);
          $filename = str_replace('-', ' ', $filename);
          if (stristr($trip->trip_location_to, $filename)) { //$html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px" /></div>';
            $html .= '<div align="center"><img src="' . SITE . $dir . $file . '" width="100%" style="max-height:520px" /></div>';
            $indir = 1;
            break;
          }
        }
      }
      closedir($dh);
    }
  }
  if (!$indir) $html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $trip->trip_location_to . '&style=feature:transit.line|element:all|visibility:simplified|color:0xFF6319&zoom=13&size=640x300&scale=2&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
  $mpdf->WriteHTML($html);
}

//bus map 
$html = '';
if ($trip->trip_option_busmap) { //$mpdf->AddPage();
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS MAP IMAGE</b></p>';

  $dir = "busmap/";
  $indir = 0;
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if (filetype($dir . $file) == 'file') {
          $filename = substr($file, 0, -4);
          $filename = str_replace('-', ' ', $filename);
          $filename = str_replace(',', ' ', $filename);
          if (!empty($political_long_name)) {
            if (stristr($filename, $political_long_name)) { //$html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
              $html .= '<div align="center"><img src="' . SITE . $dir . $file . '" width="100%" style="max-height:520px"/></div>';
              $indir = 1;
              break;
            }
          } else {
            if (stristr($filename, $locality_long_name)) { //$html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
              $html .= '<div align="center"><img src="' . SITE . $dir . $file . '" width="100%" style="max-height:520px"/></div>';
              $indir = 1;
              break;
            }
          }
        }
      }

      closedir($dh);
    }
  }
  if (!$indir) { //$html .= '<div align="center"><img src="'.SITE.$dir.'noimage.png" width="100%" style="max-height:530px" /></div>';
    $html .= '<div align="center"><img src="' . SITE . $dir . 'noimage.png" width="100%" style="max-height:520px" /></div>';
  }

  $mpdf->WriteHTML($html);
}

// Route 
$mpdf->AddPage();
$html = '';
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE</b></p>';
$origin_r = str_replace('(', '', $trip->trip_location_from_latlng);
$origin_r = str_replace(')', '', $origin_r);
$destination_r = str_replace('(', '', $trip->trip_location_to_latlng);
$destination_r = str_replace(')', '', $destination_r);
$thewaypt = '';
if ($trip->trip_location_waypoint_latlng != '') {
  $thewaypt = str_replace('(', '', $trip->trip_location_waypoint_latlng);
  $thewaypt = str_replace(')', '', $thewaypt);
}


$key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origin_r), str_replace(' ', '', $destination_r), $key, $trip->trip_transport, '640x300', str_replace(' ', '', $thewaypt));
$html .= '<img src="' . $map_url . '" width="100%" />';
$mpdf->WriteHTML($html);

/*if($trip->trip_location_from_latlng_drivingportion){
// portion Route by driving
$mpdf->AddPage();
$html = '';   
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>'; 
$origind = str_replace('(','',$trip->trip_location_from_latlng_drivingportion);
$origind = str_replace(')','',$origind);
$destinationd = str_replace('(','',$trip->trip_location_to_latlng_drivingportion);
$destinationd = str_replace(')','',$destinationd);
$key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$origind), str_replace(' ','',$destinationd), $key, 'vehicle');
$html .= '<img src="'.$map_url.'" width="100%" />';
$mpdf->WriteHTML($html);
}*/

if ($trip->trip_option_directions) {
  // Route Directions
  $mpdf->AddPage();
  $html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
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
  $key2 = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
  $map_url_D = $trip->getStaticGmapForDirections(str_replace(' ', '', $origin_D), str_replace(' ', '', $destination_D), $key, $key2);
  $html .= '<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center">
          <tr >
            <td colspan="3" align="center" valign="top" width="100%"><img width="100%" src="' . $map_url_D . '"/></td>
          </tr>
         <tr >
            <td width="20%">&nbsp;</td>
           <td width="80%" colspan="2" valign="top">' . $trip->trip_directions_text . '</td>
          </tr>

        </table>
        ';
  $mpdf->WriteHTML($html);
  $html = '';
} elseif ($trip->trip_location_from_latlng_drivingportion) {
  // portion Route by driving
  $mpdf->AddPage();
  $html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
  $origind = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion);
  $origind = str_replace(')', '', $origind);
  $destinationd = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion);
  $destinationd = str_replace(')', '', $destinationd);
  $key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
  $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origind), str_replace(' ', '', $destinationd), $key, 'vehicle');
  $html .= '<img src="' . $map_url . '" width="100%" />';
  $mpdf->WriteHTML($html);
}


if ($trip->trip_location_from_latlng_trainportion) {
  // portion Route by train
  $mpdf->AddPage();
  $html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE by TRAIN</b></p>';
  $origint = str_replace('(', '', $trip->trip_location_from_latlng_trainportion);
  $origint = str_replace(')', '', $origint);
  $destinationt = str_replace('(', '', $trip->trip_location_to_latlng_trainportion);
  $destinationt = str_replace(')', '', $destinationt);
  $key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
  $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $origint), str_replace(' ', '', $destinationt), $key, 'train');
  $html .= '<img src="' . $map_url . '" width="100%" />';
  $mpdf->WriteHTML($html);
}

if ($trip->trip_location_from_latlng_flightportion) {
  // portion Route flight
  $mpdf->AddPage();
  $html = '';
  $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>FLIGHT ROUTE</b></p>';
  $originf = str_replace('(', '', $trip->trip_location_from_latlng_flightportion);
  $originf = str_replace(')', '', $originf);
  $destinationf = str_replace('(', '', $trip->trip_location_to_latlng_flightportion);
  $destinationf = str_replace(')', '', $destinationf);
  $key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
  $map_url = $trip->getStaticGmapURLForDirection(str_replace(' ', '', $originf), str_replace(' ', '', $destinationf), $key, 'plane');
  $html .= '<img src="' . $map_url . '" width="100%" />';
  $mpdf->WriteHTML($html);
}

// Route Destination  
$mpdf->AddPage();
$html = '';
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=12&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=13&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $destination . '&zoom=10&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C' . $destination . '&size=640x300&scale=2&maptype=roadmap&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$mpdf->WriteHTML($html);

// Detailed Route Destination  
//$mpdf->AddPage();
$html = '';
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DETAILED ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$destination.'&zoom=14&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C'.$destination.'&size=640x300&scale=2&maptype=roadmap&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $destination . '&zoom=15&scale=2&size=640x300&maptype=roadmap&markers=color:green%7Clabel:A%7C' . $destination . '&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$mpdf->WriteHTML($html);


$html = '';
if ($trip->trip_option_hotels) {
  $data = $trip->getMapFilters($destination, 'lodging', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOTELS/MOTELS</b></p>';
    $html .= $data; //$trip->getMapFilters($destination, 'lodging' , $key);
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_police) {
  $data = $trip->getMapFilters($destination, 'police', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>POLICE STATIONS</b></p>';
    $html .= $data; //$trip->getMapFilters($destination, 'police' , $key);
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_hospitals) {
  $data = $trip->getMapFilters($destination, 'hospital', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOSPITALS</b></p>';
    $html .= $data; //$trip->getMapFilters($destination, 'hospital' , $key);
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_gas) {
  $data = $trip->getMapFilters($destination, 'gas_station', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SERVICE STATIONS (GAS/PETROL/DIESEL)</b></p>';
    $html .= $data; //$trip->getMapFilters($destination, 'gas_station' , $key);
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_taxi) {
  $data = $trip->getMapFilters($destination, 'taxi_stand', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TAXI SERVICES</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_airfields) {
  $data = $trip->getMapFilters($destination, 'airport', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>AIRFIELDS</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_parking) {
  $data = $trip->getMapFilters($destination, 'parking', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PARKING</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_university) {
  $data = $trip->getMapFilters($destination, 'school', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>UNIVERSITIES</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_atm) {
  $data = $trip->getMapFilters($destination, 'atm', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ATM</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_subway_station) {
  $data = $trip->getMapFilters($destination, 'subway_station', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Subway Stations</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_metro) {
  $data = $trip->getMapFilters($destination, 'train_station', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Metro Stations</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_playground) {
  $data = $trip->getMapFilters($destination, 'park', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Parks</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_museum) {
  $data = $trip->getMapFilters($destination, 'museum', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>MUSEUMS</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_library) {
  $data = $trip->getMapFilters($destination, 'library', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>LIBRARIES</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_pharmacy) {
  $data = $trip->getMapFilters($destination, 'pharmacy', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PHARMACIES</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';
if ($trip->trip_option_church) {
  $data = $trip->getMapFilters($destination, 'church', $key);
  if (!empty($data)) {
    $mpdf->AddPage();
    $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>RELIGIOUS INSTITUTIONS</b></p>';
    $html .= $data;
    $mpdf->WriteHTML($html);
  }
}
$html = '';

/*if ($trip->trip_option_busmap)
   { $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS STATIONS</b></p>';
     $data = $trip->getMapFilters($destination, 'bus_station' , $key);
     if(!empty($data)){
       $mpdf->AddPage();   
       $html .= $data;
     $mpdf->WriteHTML($html);
     }
   }*/

// footer         
$mpdf->SetHTMLFooter('<table width="95%">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center" style="font-size:12px; color:#666">&copy; {DATE Y} Planiversity, LLC. All rights reserved.</td>
        <td width="33%" style="text-align: right; color:#666">{PAGENO}/{nbpg}</td>
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

$pdfname = $triptitle . '-' . $trip->trip_id . '-' . $userdata['id'];
$pdfpath = 'pdf/' . $pdfname . '.pdf';
// delete if exist
if (file_exists($pdfpath)) unlink($pdfpath);
$mpdf->Output('pdf/' . $pdfname . '.pdf', \Mpdf\Output\Destination::FILE);

$trip->edit_data_pdf($trip->trip_id);

//echo SITE.'pdf/'.$pdfname.'.pdf';

//$mpdf->Output();
