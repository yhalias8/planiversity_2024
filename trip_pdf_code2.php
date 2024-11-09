<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
   $_SESSION['redirect'] = 'trip/pdf/'.$_GET['idtrip'];
   header("Location:".SITE."login");
}

include("class/class.Plan.php");
$plan = new Plan();
if (!$plan->check_plan($userdata['id']))
   { header("Location:".SITE."billing/".$_GET['idtrip']);     
   }

include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip)) header("Location:".SITE."trip/how-are-you-traveling");
$trip->get_data($id_trip);

$plan->change_status_plan($userdata['id']);   
  
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

$_to = explode(',',$trip->trip_location_to);

$html = '<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
           <img src="'.SITE.'images/pdf_mg1.jpg" width="100%" />           
         </div>
         <div align="center" style="font-size:56px; position:fixed; width:100%; padding:60px 0 0 0; color:#FFF"><p align="center"><b>'.$trip->trip_title.'</b></p></div>'; 

$mpdf->WriteHTML($html); 
$html = '';

// header
$mpdf->SetHTMLHeader('<div width="100%" align="center" style="border-bottom:1px solid #86BFEA; padding-bottom:10px">
     <img src="'.SITE.'images/pdf_innerlogo.png" /><br />
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
if ($tmp && $stmt->rowCount()>0)
   { // add new page
     $mpdf->AddPage();
     $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PASSPORT</b></p>';
          $j=0;
     foreach ($documents as $document){
         $mpdf->AddPage();
         $j++;
         if($j==1) $mpdf->WriteHTML($html);  
         if(strstr($document->name,'.pdf')){
        //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
        $mpdf->SetImportUse();
        $file = './ajaxfiles/uploads/'.$document->name;
        $pagecount = $mpdf->SetSourceFile($file);
            for ($i=1; $i<=$pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
         }else
            $html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
     }     
   }
    
// Driver's License
$mpdf->WriteHTML($html); 
$html = '';   
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");                  
$stmt->bindValue(1, 'driver', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount()>0)
   { $mpdf->AddPage();
     $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DRIVER\'S LICENSE</b></p>';
     $j=0;
     foreach ($documents as $document){
                 $mpdf->AddPage();
         $j++;
         if($j==1) $mpdf->WriteHTML($html);  
         if(strstr($document->name,'.pdf')){
        //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
        $mpdf->SetImportUse();
        $file = './ajaxfiles/uploads/'.$document->name;
        $pagecount = $mpdf->SetSourceFile($file);
            for ($i=1; $i<=$pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
         }else
           $html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
     }
     $mpdf->WriteHTML($html);     
   }
   
// additional documents
$html = '';   
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");                  
$stmt->bindValue(1, 'additional', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount()>0)
   { //$mpdf->AddPage();
     $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Additional Documents</b></p>';
     $j=0;
     foreach ($documents as $document){
         $mpdf->AddPage();
         $j++;
         if($j==1) $mpdf->WriteHTML($html);  
         if(strstr($document->name,'.pdf')){
        //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
        $mpdf->SetImportUse();
        $file = './ajaxfiles/uploads/'.$document->name;
        $pagecount = $mpdf->SetSourceFile($file);
            for ($i=1; $i<=$pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
         }else{
          $html = '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';   
          $mpdf->WriteHTML($html);
         }
         
       }
   }
  
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
           <img src="'.SITE.'images/pdf_mg2.jpg" width="100%" />           
         </div>
         <div align="center" style="font-size:30px; width:100%; position:fixed; padding:320px 0 0 0; color:#FFF">
            <table align="center" width="60%" style="font-size:30px; color:#FFF" cellpadding="10">
             <tr>
              <td align="center"><b style="font-size:66px;">Itinerary:</b></td>
             </tr>';
            if($trip->trip_location_datel && $trip->trip_location_datel!='0000-00-00')
             $html.='
             <tr> 
              <td align="center">Leaving: <b>'.date("F j, Y",strtotime($trip->trip_location_datel)).'</b></td>
             </tr>'; 
             
            if($trip->trip_location_dater && $trip->trip_location_dater!='0000-00-00')
             $html.='
             <tr> 
              <td align="center">Coming back: <b>'.date("F j, Y",strtotime($trip->trip_location_dater)).'</b></td>
             </tr>'; 
             
            $html.=' <tr> 
              <td align="center" style="border-bottom:1px solid #FFF">From : <b>'.$trip->trip_location_from.'</b></td>
             </tr>
             <tr> 
              <td align="center">To : <b>'.$trip->trip_location_to.'</b></td>
             </tr>';
             if($trip->trip_location_to_drivingportion)
             $html.='
             <tr> 
              <td align="center">To : <b>'.$trip->trip_location_to_drivingportion.'</b></td>
             </tr>';
             if($trip->trip_location_to_flightportion)
             $html.='
              <tr> 
              <td align="center">To : <b>'.$trip->trip_location_to_flightportion.'</b></td>
             </tr>';
             if($trip->trip_location_to_trainportion)
             $html.='
              <tr> 
              <td align="center">To : <b>'.$trip->trip_location_to_trainportion.'</b></td>
             </tr>';
         
         $html.='    
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
if ($tmp && $stmt->rowCount()>0)
   { $mpdf->AddPage();
     $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOTEL ITINERARY</b></p>';
          $j=0;
     foreach ($documents as $document){
         $mpdf->AddPage();
         $j++;
         if($j==1) $mpdf->WriteHTML($html);  
         if(strstr($document->name,'.pdf')){
        //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
        $mpdf->SetImportUse();
        $file = './ajaxfiles/uploads/'.$document->name;
        $pagecount = $mpdf->SetSourceFile($file);
            for ($i=1; $i<=$pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
         }else
            $html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
     }
     $mpdf->WriteHTML($html);     
   }
      
// Flight Itinerary
$html = '';  
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");                  
$stmt->bindValue(1, 'fitinerary', PDO::PARAM_STR);
$stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount()>0)
   { $mpdf->AddPage();
     $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>FLIGHT ITINERARY</b></p>';
          $j=0;
     foreach ($documents as $document){
         $mpdf->AddPage();
         $j++;
         if($j==1) $mpdf->WriteHTML($html);  
         if(strstr($document->name,'.pdf')){
        //------CODE TO READ A PDF AND PRINT IT INTO THE CURRENT ONE--------//
        $mpdf->SetImportUse();
        $file = './ajaxfiles/uploads/'.$document->name;
        $pagecount = $mpdf->SetSourceFile($file);
            for ($i=1; $i<=$pagecount; $i++) {
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);

                if ($i < $pagecount)
                    $mpdf->AddPage();
            }
         }else
            $html .= '<div align="center"><img src="'.SITE.'ajaxfiles/uploads/'.$document->name.'" width="100%" style="max-height:530px" /></div>';
     }
     $mpdf->WriteHTML($html);     
   }  
 


$origin = str_replace('(','',$trip->trip_location_from_latlng);
$origin = str_replace(')','',$origin);
$destination = str_replace('(','',$trip->trip_location_to_latlng);
$destination = str_replace(')','',$destination);

if(!empty($trip->trip_option_circle)){
                    $circle_data = explode('::',$trip->trip_option_circle);
                    $lat_to = $circle_data[0];
                    $lng_to = $circle_data[1];
                    $radius = $circle_data[2];
                    $radius = round($radius*$factor);
                    $showclear =1;    
                    $destination_circle = $lat_to.','.$lng_to;
                }

        if($trip->trip_transport=='plane'){
             if($trip->trip_location_from_latlng_drivingportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_drivingportion);
                $destination = str_replace(')','',$destination);
             }
             if($trip->trip_location_from_latlng_trainportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_trainportion);
                $destination = str_replace(')','',$destination);
             }
         }               
                        
        if($trip->trip_transport=='vehicle'){
            if($trip->trip_location_from_latlng_flightportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_flightportion);
                $destination = str_replace(')','',$destination);
             }
            if($trip->trip_location_from_latlng_trainportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_trainportion);
                $destination = str_replace(')','',$destination);
             }
         }   
         if($trip->trip_transport=='train'){
             if($trip->trip_location_from_latlng_flightportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_flightportion);
                $destination = str_replace(')','',$destination);
             }
            if($trip->trip_location_from_latlng_drivingportion){
                $destination = str_replace('(','',$trip->trip_location_to_latlng_drivingportion);
                $destination = str_replace(')','',$destination);
             }
         } 
 
                           
                

$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';

//Travel Advisor from State Department
$html = '';   
$html .= '<div style="padding:60px;">';   
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>Travel Advisories</b></p>';
$have_advice=0;
$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key);
         if($xml->status=='OK'){
             foreach($xml->result->address_component as $value){
                 if($value->type=='country'){
                      $country_long_name= $value->long_name;
                      $country_short_name= $value->short_name;
                 }
             }
             
             $xml_Advisories=simplexml_load_file("https://travel.state.gov/_res/rss/TAsTWs.xml");
             if($xml_Advisories){
                 //echo $country_long_name.'('.$country_short_name.')<br>';
                $country_long_name = trim($country_long_name);
                foreach($xml_Advisories->channel->item as $val){
                    $currtitle = $val->title;
                    if(strstr($val->title,$country_long_name)){ 
                      $html.=$val->title.'<br><br>';
                      $html.=$val->pubDate.'<br><br>';
                      $html.='<a href="'.$val->link.'">'.$val->link.'</a><br><br>';
                      $desc = strip_tags($val->description);
                      $desc = substr($desc,0,1400);
                      $html.=trim($desc);
                      $have_advice =1;                      
                   }
                }
                                          
             }
        }
$html .= '</div>';
if($have_advice){
$mpdf->AddPage();
$mpdf->WriteHTML($html);
//$mpdf->AddPage();
}


// Reminders 
$html = ''; 
$stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");                  
$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount()>0)
   { //$mpdf->WriteHTML($html);   
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
     foreach ($timelines as $timeline){
         $html .= '<tr>
                    <td bgcolor="#EFF7FC">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                         <tr>
                           <td width="30px" valign="middle"><img src="'.SITE.'images/pdf_icon1.png" style="vertical-align:central" /></td>
                           <td valign="middle" style="font-family:OpenSansRegular; font-size:16px; color:#545454">'.$timeline->title.'</td>
                         </tr>
                      </table>
                    </td>
                    <td></td>
                    <td bgcolor="#EFF7FC">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                         <tr>
                           <td width="30px" valign="middle"></td>
                           <td valign="middle" style="font-family:OpenSansRegular; font-size:16px; color:#545454">'.date('d F Y h:i a',strtotime($timeline->date)).'</td>
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
if ($tmp && $stmt->rowCount()>0)
   { //$mpdf->WriteHTML($html);   
     $mpdf->AddPage();
     //$html = '';
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TRIP NOTES<b></p>';
     $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
     $html .= '<table width="65%" align="center" cellspacing="10">';
     foreach ($notes as $note){
         $html .= '<tr>
                   <td width="10%" align="center" valign="top"><img src="'.SITE.'images/pdf_icon2.png" /></td>
                   <td width="90%" align="left" valign="top" style="color:#444444; font-family: OpenSans; font-size:20px">'.$i.'. '.$note->text.'</td>
                   </tr>';
         $i++;          
     }
     $html .= '</table>';
     $mpdf->WriteHTML($html);
   }
   

   if ($trip->trip_option_busmap || $trip->trip_option_weather){
    //echo "https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key.'<br><br>';
//$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key) or die("Error: Cannot create object xml");
$xml=simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=".$destination."&sensor=false&key=".$key);
        if($xml->status=='OK'){
                foreach($xml->result[1]->address_component as $value){
                    //echo $value->type.', '.$value->long_name.'<br>';
                if($value->type=='locality'){
                    $locality_long_name= trim($value->long_name);
                    $locality_short_name= $value->short_name;
                }
                 if($value->type=='political'){
                    $political_long_name= trim($value->long_name);
                    $political_short_name= $value->short_name;
                }
        }
     }
}

$html = '';

// Filters
     
if ($trip->trip_option_weather)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>WEATHER AT '.$locality_long_name.'</b><p>';
     $html .= $trip->getWeatherFilters($locality_long_name,1);
     $mpdf->WriteHTML($html); 
   }

   $html = '';
if ($trip->trip_option_embassis)
   { $mpdf->AddPage();
     $html .= $trip->getMapEmbassis($destination,$key,$trip->trip_list_embassis);
     $mpdf->WriteHTML($html);
   }
 
 //subway map
   
$html = '';   
if ($trip->trip_option_subway)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SUBWAY MAP</b></p>';
     $dir = "subwaymap/";
     $indir = 0;
     if (is_dir($dir)) 
        { if ($dh = opendir($dir)) 
             { while (($file = readdir($dh)) !== false) 
                   { if (filetype($dir . $file)=='file')
                        { $filename = substr($file, 0, -4);
                          $filename = str_replace('-',' ',$filename);
                          if (stristr($trip->trip_location_to,$filename))
                              { $html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px" /></div>';
                                $indir = 1;
                                break;
                              }
                        }
                   }
                closedir($dh);
             }
        }
     if (!$indir) $html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&style=feature:transit.line|element:all|visibility:simplified|color:0xFF6319&zoom=13&size=640x300&scale=2&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y" width="100%" />';
     $mpdf->WriteHTML($html);
   }
   
  //bus map 
  $html = '';   
if ($trip->trip_option_busmap)
   { //$mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS MAP IMAGE</b></p>';
     

     
     $dir = "busmap/";
     $indir = 0;
     if (is_dir($dir)) 
        { if ($dh = opendir($dir)) 
             { while (($file = readdir($dh)) !== false) 
                   {  if (filetype($dir . $file)=='file')
                            { $filename = substr($file, 0, -4);
                              $filename = str_replace('-',' ',$filename);
                              $filename = str_replace(',',' ',$filename);
                               if(!empty($political_long_name)){
                              if (stristr($filename,$political_long_name))
                                  { $html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
                                    $indir = 1;
                                    break;
                                  }
                              }else{
                                    if (stristr($filename,$locality_long_name))
                                      { $html .= '<div align="center"><img src="'.SITE.$dir.$file.'" width="100%" style="max-height:530px"/></div>';
                                        $indir = 1;
                                        break;
                                      }
                                  }
                            }
                   }
                  
                closedir($dh);
             }
        }
     if (!$indir)
          $html .= '<div align="center"><img src="'.SITE.$dir.'noimage.png" width="100%" style="max-height:530px" /></div>';
     
     $mpdf->WriteHTML($html);
   }

 // Route 
$mpdf->AddPage();
$html = '';   
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE</b></p>';
$origin_r = str_replace('(','',$trip->trip_location_from_latlng);
$origin_r = str_replace(')','',$origin_r);
$destination_r = str_replace('(','',$trip->trip_location_to_latlng);
$destination_r = str_replace(')','',$destination_r);
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$origin_r), str_replace(' ','',$destination_r), $key, $trip->trip_transport);
$html .= '<img src="'.$map_url.'" width="100%" />';
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
$key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$origind), str_replace(' ','',$destinationd), $key, 'vehicle');
$html .= '<img src="'.$map_url.'" width="100%" />';
$mpdf->WriteHTML($html);
}*/

if ($trip->trip_option_directions)
   {
            // Route Directions
            $mpdf->AddPage();
            $html = '';   
            $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DRIVING</b></p>';
        if($trip->trip_transport=='vehicle'){
            $origin_D = str_replace('(','',$trip->trip_location_from_latlng);
            $origin_D = str_replace(')','',$origin_D);
            $destination_D = str_replace('(','',$trip->trip_location_to_latlng);
            $destination_D = str_replace(')','',$destination_D);
        }elseif($trip->trip_location_from_drivingportion && $trip->trip_location_to_drivingportion){
            $origin_D = str_replace('(','',$trip->trip_location_from_latlng_drivingportion);
            $origin_D = str_replace(')','',$origin_D);
            $destination_D = str_replace('(','',$trip->trip_location_to_latlng_drivingportion);
            $destination_D = str_replace(')','',$destination_D);
        }
        $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
        $key2 = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';
        $map_url_D = $trip->getStaticGmapForDirections(str_replace(' ','',$origin_D), str_replace(' ','',$destination_D), $key, $key2);
        $html .= '<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center">
          <tr >
            <td colspan="3" align="center" valign="top" width="100%"><img width="100%" src="'.$map_url_D.'"/></td>
          </tr>
         <tr >
           <td width="20%">&nbsp;</td>
           <td valign="top" width="60%">'.$trip->trip_directions_text.'</td>
           <td width="20%">&nbsp;</td>
          </tr>

        </table>
        ';
        $mpdf->WriteHTML($html);

   }elseif($trip->trip_location_from_latlng_drivingportion){
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
            }
   

if($trip->trip_location_from_latlng_trainportion){
// portion Route by train
$mpdf->AddPage();
$html = '';   
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE by TRAIN</b></p>'; 
$origint = str_replace('(','',$trip->trip_location_from_latlng_trainportion);
$origint = str_replace(')','',$origint);
$destinationt = str_replace('(','',$trip->trip_location_to_latlng_trainportion);
$destinationt = str_replace(')','',$destinationt);
$key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$origint), str_replace(' ','',$destinationt), $key, 'train');
$html .= '<img src="'.$map_url.'" width="100%" />';
$mpdf->WriteHTML($html);
}

if($trip->trip_location_from_latlng_flightportion){
// portion Route flight
$mpdf->AddPage();
$html = '';   
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>FLIGHT ROUTE</b></p>'; 
$originf = str_replace('(','',$trip->trip_location_from_latlng_flightportion);
$originf = str_replace(')','',$originf);
$destinationf = str_replace('(','',$trip->trip_location_to_latlng_flightportion);
$destinationf = str_replace(')','',$destinationf);
$key = 'AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo';
$map_url = $trip->getStaticGmapURLForDirection(str_replace(' ','',$originf), str_replace(' ','',$destinationf), $key, 'plane');
$html .= '<img src="'.$map_url.'" width="100%" />';
$mpdf->WriteHTML($html);
}

// Route Destination  
$mpdf->AddPage();
$html = ''; 
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=12&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=13&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$destination.'&zoom=10&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C'.$destination.'&size=640x300&scale=2&maptype=roadmap&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$mpdf->WriteHTML($html);

// Detailed Route Destination  
//$mpdf->AddPage();
$html = '';
$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>DETAILED ROUTE DESTINATION</b></p>';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$trip->trip_location_to.'&zoom=14&style=feature:all|element:labels.text|visibility:off&size=640x300&scale=2&maptype=hybrid&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
//$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$destination.'&zoom=14&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C'.$destination.'&size=640x300&scale=2&maptype=roadmap&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$destination.'&zoom=15&scale=2&size=640x300&maptype=roadmap&markers=color:green%7Clabel:A%7C'.$destination.'&key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo" width="100%" />';
$mpdf->WriteHTML($html);


$html = '';       
if ($trip->trip_option_hotels)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOTELS/MOTELS</b></p>';
     $html .= $trip->getMapFilters($destination, 'lodging' , $key);
     $mpdf->WriteHTML($html);
   }
$html = '';   
if ($trip->trip_option_police)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>POLICE STATIONS</b></p>';
     $html .= $trip->getMapFilters($destination, 'police' , $key);
     $mpdf->WriteHTML($html);
   }
$html = '';   
if ($trip->trip_option_hospitals)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>HOSPITALS</b></p>';
     $html .= $trip->getMapFilters($destination, 'hospital' , $key);
     $mpdf->WriteHTML($html);
   }
$html = '';   
if ($trip->trip_option_gas)
   { $mpdf->AddPage();
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>SERVICE STATIONS (GAS/PETROL/DIESEL)</b></p>';
     $html .= $trip->getMapFilters($destination, 'gas_station' , $key);
     $mpdf->WriteHTML($html);
   }
$html = '';   
if ($trip->trip_option_taxi)
   { 
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>TAXI SERVICES</b></p>';
     $data = $trip->getMapFilters($destination, 'taxi_stand' , $key);
     if(!empty($data)){
      $mpdf->AddPage();   
       $html .= $data;
     $mpdf->WriteHTML($html);
     }
   }
$html = '';   
if ($trip->trip_option_airfields)
   { 
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>AIRFIELDS</b></p>';
     $data = $trip->getMapFilters($destination, 'airport' , $key);
     if(!empty($data)){
       $mpdf->AddPage();   
       $html .= $data;
     $mpdf->WriteHTML($html);
     }
   }
$html = ''; 
  
if ($trip->trip_option_parking)
   { 
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>PARKING</b></p>';
     $data = $trip->getMapFilters($destination, 'parking' , $key);
     if(!empty($data)){
       $mpdf->AddPage();   
       $html .= $data;
     $mpdf->WriteHTML($html);
     }
   }
$html = ''; 



  
/*if ($trip->trip_option_busmap)
   { 
     $html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>BUS STATIONS</b></p>';
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
$mpdf->AddPage('','','','','',0,0,0,0);
//$html = '<div align="center" style="font-size:40px; padding-top:360px; background-color:#1C72B4; height:100%; color:#FFF">Thank you for choosing PLANIVERSITY!</div>';
$html = '<div align="center" style="font-size:40px; padding-top:360px; background-color:#76889A; height:100%; color:#FFF">Thank you for choosing PLANIVERSITY!</div>';
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter(' ');
$triptitle = trim($trip->trip_title);
$triptitle = str_replace('&#39;','_',$triptitle);
$triptitle = str_replace(' ','_',$triptitle);

$pdfname = $triptitle.'-'.$trip->trip_id.'-'.$userdata['id'];
$pdfpath = 'pdf/'.$pdfname.'.pdf';
// delete if exist
if (file_exists($pdfpath)) unlink($pdfpath);
$mpdf->Output('pdf/'.$pdfname.'.pdf', \Mpdf\Output\Destination::FILE);

//$trip->edit_data_pdf($trip->trip_id);

//echo SITE.'pdf/'.$pdfname.'.pdf';

//$mpdf->Output();
