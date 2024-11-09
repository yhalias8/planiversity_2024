<?php
 
  class TripPlan
     {
       var $trip_id = 0;
       var $user_id = 0;
       var $trip_title = '';
       var $trip_transport = '';
       var $trip_location_from = '';
       var $trip_location_to = '';
       var $trip_location_from_latlng = '';
       var $trip_location_to_latlng = '';
       
       var $trip_location_triptype = '';
       var $trip_location_datel = '';
       var $trip_location_dater = '';
       var $trip_location_from_flightportion = '';
       var $trip_location_to_flightportion = '';
       var $trip_location_from_latlng_flightportion = '';
       var $trip_location_to_latlng_flightportion = '';
       var $trip_location_from_drivingportion = '';
       var $trip_location_to_drivingportion = '';
       var $trip_location_from_latlng_drivingportion = '';
       var $trip_location_to_latlng_drivingportion = '';
       var $trip_location_from_trainportion = '';
       var $trip_location_to_trainportion = '';
       var $trip_location_from_latlng_trainportion = '';
       var $trip_location_to_latlng_trainportion = '';
       
       var $trip_option_weather = 0;
       var $trip_option_hotels = 0;
       var $trip_option_police = 0;
       var $trip_option_hospitals = 0;
       var $trip_option_gas = 0;
       var $trip_option_subway = 0;
       var $trip_option_embassis = 0;
       var $trip_option_taxi = 0;
       var $trip_option_airfields = 0;
       var $trip_option_directions = 0;
       var $trip_option_busmap = 0;
       var $trip_option_parking = 0;
       var $trip_list_embassis = '';
       var $trip_employee = 0;
       var $table = '';
       var $error = '';
      
       function TripPlan($nametable='trips')
           { $this->trip_id = 0;     
             $this->user_id = 0;     
             $this->trip_title = '';     
             $this->trip_transport = '';     
             $this->trip_location_from = '';     
             $this->trip_location_to = '';     
             $this->trip_location_from_latlng = '';     
             $this->trip_location_to_latlng = '';
             
             $this->trip_location_triptype = '';
             $this->trip_location_datel = '';
             $this->trip_location_dater = '';
             $this->trip_location_from_flightportion = '';
             $this->trip_location_to_flightportion = '';
             $this->trip_location_from_latlng_flightportion = '';
             $this->trip_location_to_latlng_flightportion = '';
             $this->trip_location_from_drivingportion = '';
             $this->trip_location_to_drivingportion = '';
             $this->trip_location_from_latlng_drivingportion = '';
             $this->trip_location_to_latlng_drivingportion = '';
             $this->trip_location_from_trainportion = '';
             $this->trip_location_to_trainportion = '';
             $this->trip_location_from_latlng_trainportion = '';
             $this->trip_location_to_latlng_trainportion = '';
                  
             $this->trip_option_weather = 0;     
             $this->trip_option_hotels = 0;     
             $this->trip_option_police = 0;     
             $this->trip_option_hospitals = 0;     
             $this->trip_option_gas = 0;     
             $this->trip_option_subway = 0;     
             $this->trip_option_embassis = 0;     
             $this->trip_option_taxi = 0;     
             $this->trip_option_airfields = 0;  
             $this->trip_option_directions = 0;   
             $this->trip_option_busmap = 0;     
             $this->trip_option_parking = 0;     
             $this->trip_option_circle = 0;     
             $this->trip_list_embassis = '';     
             $this->trip_employee = 0;     
             $this->table = $nametable; 
             $this->error = '';
           }         
                     
       function get_data($id)
           { global $dbh,$userdata;
             $stmt = $tmp = '';
             
             if (!empty($id))
                { $stmt = $dbh->prepare("SELECT * FROM ".$this->table." WHERE id_trip=? AND id_user=?");                  
                  $stmt->bindValue(1, $id, PDO::PARAM_INT);
                  $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
                  $tmp = $stmt->execute();
                  
                  if (!$tmp)
                      $this->error = 'error_fail';
                  elseif ($stmt->rowCount()>0)
                     { $trips = $stmt->fetchAll(PDO::FETCH_OBJ);
                       foreach ($trips as $trip){
                           $this->trip_id = $trip->id_trip;     
                           $this->user_id = $trip->id_user;     
                           $this->trip_title = $trip->title;     
                           $this->trip_transport = $trip->transport;     
                           $this->trip_location_from = $trip->location_from;     
                           $this->trip_location_to = $trip->location_to;     
                           $this->trip_location_from_latlng = $trip->location_from_latlng;     
                           $this->trip_location_to_latlng = $trip->location_to_latlng;
                           
                           $this->trip_location_triptype = $trip->location_triptype;
                           $this->trip_location_datel = $trip->location_datel;
                           $this->trip_location_dater = $trip->location_dater;
                           $this->trip_location_from_flightportion = $trip->location_from_flightportion;
                           $this->trip_location_to_flightportion = $trip->location_to_flightportion;
                           $this->trip_location_from_latlng_flightportion = $trip->location_from_latlng_flightportion;
                           $this->trip_location_to_latlng_flightportion = $trip->location_to_latlng_flightportion;
                           $this->trip_location_from_drivingportion = $trip->location_from_drivingportion;
                           $this->trip_location_to_drivingportion = $trip->location_to_drivingportion;
                           $this->trip_location_from_latlng_drivingportion = $trip->location_from_latlng_drivingportion;
                           $this->trip_location_to_latlng_drivingportion = $trip->location_to_latlng_drivingportion;
                           $this->trip_location_from_trainportion = $trip->location_from_trainportion;
                           $this->trip_location_to_trainportion = $trip->location_to_trainportion;
                           $this->trip_location_from_latlng_trainportion = $trip->location_from_latlng_trainportion;
                           $this->trip_location_to_latlng_trainportion = $trip->location_to_latlng_trainportion;
                           
                           $this->trip_option_weather = $trip->option_weather;     
                           $this->trip_option_hotels = $trip->option_hotels;     
                           $this->trip_option_police = $trip->option_police;     
                           $this->trip_option_hospitals = $trip->option_hospitals;     
                           $this->trip_option_gas = $trip->option_gas;     
                           $this->trip_option_subway = $trip->option_subway;     
                           $this->trip_option_embassis = $trip->option_embassis;
                           $this->trip_option_taxi = $trip->option_taxi;
                           $this->trip_option_airfields = $trip->option_airfields;
                           $this->trip_option_directions = $trip->option_directions;
                           $this->trip_option_busmap = $trip->option_busmap;
                           $this->trip_option_parking = $trip->option_parking;
                           $this->trip_option_circle = $trip->option_circle;
                           $this->trip_list_embassis = $trip->embassis_list;
                           $this->trip_employee = $trip->id_employee;
                       }
                     }
                  else
                     $this->error = 'error_access';   
                }
             else
               $this->error = 'error_mising';                                                                        
           } 
           
       function put_data($transport,$from,$to,$from_latlng,$to_latlng,$filter,$embasylist = '',$location_triptype,$location_datel,$location_dater,$location_from_flightportion,$location_to_flightportion,$location_from_latlng_flightportion,$location_to_latlng_flightportion,$location_from_drivingportion,$location_to_drivingportion,$location_from_latlng_drivingportion,$location_to_latlng_drivingportion,$location_from_trainportion,$location_to_trainportion,$location_from_latlng_trainportion,$location_to_latlng_trainportion) 
          { global $dbh,$userdata; 
            $stmt = $tmp = $query = $queryfilter = $queryfiltervalue = '';
            $elist = implode(',',$embasylist);
            
            if (!empty($from) && !empty($to))
               {/* if (!empty($embasylist))
                    $query = "INSERT INTO ".$this->table." (id_user, transport, location_from, location_to, location_from_latlng, location_to_latlng, option_weather, option_hotels, option_police, option_hospitals, option_gas, option_subway, option_embassis, embassis_list) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                 else   */
                    /*$query = "INSERT INTO `".$this->table."` (`id_user`, `title`, `transport`, `location_triptype`, `location_datel`, `location_dater`, `location_from`, `location_to`, `location_from_latlng`, `location_to_latlng`, `location_from_flightportion`, `location_to_flightportion`, `location_from_latlng_flightportion`, `location_to_latlng_flightportion`, `location_from_drivingportion`, `location_to_drivingportion`, `location_from_latlng_drivingportion`, `location_to_latlng_drivingportion`, `location_from_trainportion`, `location_to_trainportion`, `location_from_latlng_trainportion`, `location_to_latlng_trainportion`, `option_weather`, `option_hotels`, `option_police`, `option_hospitals`, `option_gas`, `option_subway`, `option_embassis`, `option_circle`, `option_taxi`, `option_airfields`, `option_busmap`, `option_parking`, `option_directions`, `embassis_list`, `id_employee`, `pdf_generated`) VALUES ('".$userdata['id']."', NULL, '".$transport."', '".$location_triptype."', '".$location_datel."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, '0');";
                    $stmt = $dbh->prepare($query);*/
                    
                    $query = "INSERT INTO ".$this->table." (id_user, transport, location_triptype, location_datel, location_dater, location_from, location_to, location_from_latlng, location_to_latlng, location_from_flightportion, location_to_flightportion, location_from_latlng_flightportion, location_to_latlng_flightportion, location_from_drivingportion, location_to_drivingportion, location_from_latlng_drivingportion, location_to_latlng_drivingportion, location_from_trainportion, location_to_trainportion, location_from_latlng_trainportion, location_to_latlng_trainportion, option_weather, option_hotels, option_police, option_hospitals, option_gas, option_subway, option_embassis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                 $stmt->bindValue(2, $transport, PDO::PARAM_STR);
                 $stmt->bindValue(3, $location_triptype, PDO::PARAM_STR);
                 $stmt->bindValue(4, ($location_datel) ? $location_datel:'0000-00-00', PDO::PARAM_STR);
                 $stmt->bindValue(5, ($location_dater) ? $location_dater:'0000-00-00', PDO::PARAM_STR);
                 $stmt->bindValue(6, $from, PDO::PARAM_STR);
                 $stmt->bindValue(7, $to, PDO::PARAM_STR);
                 $stmt->bindValue(8, $from_latlng, PDO::PARAM_INT);
                 $stmt->bindValue(9, $to_latlng, PDO::PARAM_INT);
                 $stmt->bindValue(10, $location_from_flightportion, PDO::PARAM_STR);
                 $stmt->bindValue(11, $location_to_flightportion, PDO::PARAM_STR);
                 $stmt->bindValue(12, $location_from_latlng_flightportion, PDO::PARAM_STR);
                 $stmt->bindValue(13, $location_to_latlng_flightportion, PDO::PARAM_STR);
                 $stmt->bindValue(14, $location_from_drivingportion, PDO::PARAM_STR);
                 $stmt->bindValue(15, $location_to_drivingportion, PDO::PARAM_STR);
                 $stmt->bindValue(16, $location_from_latlng_drivingportion, PDO::PARAM_STR);
                 $stmt->bindValue(17, $location_to_latlng_drivingportion, PDO::PARAM_STR);
                 $stmt->bindValue(18, $location_from_trainportion, PDO::PARAM_STR);
                 $stmt->bindValue(19, $location_to_trainportion, PDO::PARAM_STR);
                 $stmt->bindValue(20, $location_from_latlng_trainportion, PDO::PARAM_STR);
                 $stmt->bindValue(21, $location_to_latlng_trainportion, PDO::PARAM_STR);
                 $stmt->bindValue(22, (in_array('weather',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(23, (in_array('hotels',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(24, (in_array('police',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(25, (in_array('hospitals',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(26, (in_array('gas',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(27, (in_array('subway',$filter) ? 1 : 0), PDO::PARAM_INT);
                 $stmt->bindValue(28, (in_array('embassis',$filter) ? 1 : 0), PDO::PARAM_INT);
                 
                 //$stmt->bindValue(14, (in_array('taxi',$filter) ? 1 : 0), PDO::PARAM_INT);
                 //$stmt->bindValue(15, (in_array('airfield',$filter) ? 1 : 0), PDO::PARAM_INT);
                 //$stmt->bindValue(16, (in_array('busmap',$filter) ? 1 : 0), PDO::PARAM_INT);
                 /*if (!empty($embasylist))
                    $stmt->bindValue(14, $elist, PDO::PARAM_STR);*/
                 $tmp = $stmt->execute();
                 if (!$tmp)
                     $this->error = 'error_fail';  
               }
            else
               $this->error = 'error_mising'; 
          }
          
       function edit_data_filter($id,$filter,$embasylist = '') 
          { global $dbh;
            $elist = implode(',',$embasylist);
            
            if (!empty($embasylist))
               $query = "UPDATE ".$this->table." SET option_weather = ?, option_hotels = ?, option_police = ?, option_hospitals = ?, option_gas = ?, option_subway = ?, option_embassis = ?, option_taxi = ?, option_airfields = ?, option_busmap = ?, option_parking = ?, option_directions = ?, option_circle = ?, embassis_list = ? WHERE id_trip = ?";
            else   
               $query = "UPDATE ".$this->table." SET option_weather = ?, option_hotels = ?, option_police = ?, option_hospitals = ?, option_gas = ?, option_subway = ?,  option_embassis = ?, option_taxi = ?, option_airfields = ?, option_busmap = ?, option_parking = ?, option_directions = ?, option_circle = ? WHERE id_trip = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, (in_array('weather',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(2, (in_array('hotels',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(3, (in_array('police',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(4, (in_array('hospitals',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(5, (in_array('gas',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(6, (in_array('subway',$filter) ? 1 : 0), PDO::PARAM_INT);
            $stmt->bindValue(7, (in_array('embassis',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(8, (in_array('taxi',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(9, (in_array('airports',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(10, (in_array('busmap',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(11, (in_array('parking',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(12, (in_array('directions',$filter) ? 1 : 0), PDO::PARAM_INT);            
            $stmt->bindValue(13, $filter[20], PDO::PARAM_INT);            
            if (!empty($embasylist))
               { $stmt->bindValue(14, $elist, PDO::PARAM_STR);
                 $stmt->bindValue(15, $id, PDO::PARAM_INT);
               }
            else
               $stmt->bindValue(14, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
          }
          
       function edit_data_employee($id,$id_employee) 
          { global $dbh;
          
            $query = "UPDATE ".$this->table." SET id_employee = ? WHERE id_trip = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $id_employee, PDO::PARAM_INT);
            $stmt->bindValue(2, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
          }
          
       function edit_data_name($id,$name) 
          { global $dbh;
          
            $query = "UPDATE ".$this->table." SET title = ? WHERE id_trip = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $name, PDO::PARAM_STR);
            $stmt->bindValue(2, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
          }
          
       /*function check_pay_plan()
          { global $dbh,$userdata;
            $stmt = $tmp = '';
            
            $stmt = $dbh->prepare("SELECT * FROM payments WHERE id_user=?");                  
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            
            if (!$tmp)
                $this->error = 'error_fail';
            elseif ($stmt->rowCount()>0)
               { /*$trips = $stmt->fetchAll(PDO::FETCH_OBJ);
                 foreach ($trips as $trip){
                     $this->trip_id = $trip->id_trip;     
                     $this->user_id = $trip->id_user;     
                     $this->trip_title = $trip->title;     
                     $this->trip_transport = $trip->transport;     
                     $this->trip_location_from = $trip->location_from;     
                     $this->trip_location_to = $trip->location_to;     
                     $this->trip_location_from_latlng = $trip->location_from_latlng;     
                     $this->trip_location_to_latlng = $trip->location_to_latlng;
                     $this->trip_option_weather = $trip->option_weather;     
                     $this->trip_option_hotels = $trip->option_hotels;     
                     $this->trip_option_police = $trip->option_police;     
                     $this->trip_option_hospitals = $trip->option_hospitals;     
                     $this->trip_option_gas = $trip->option_gas;     
                     $this->trip_option_subway = $trip->option_subway;     
                     $this->trip_option_embassis = $trip->option_embassis;
                     $this->trip_employee = $trip->id_employee;
                 }*
               }
            else
               $this->error = 'error_access';                  
          } */
          
       function getStaticGmapURLForDirection($origin, $destination, $key, $transport, $size = "640x300") {
           $markers = array();
           $waypoints_labels = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
           $waypoints_label_iter = 0;
           
           $mode = 'driving';

           $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter++] . '|' . $origin);
           $markers[] = "markers=color:red" . urlencode("|") . "label:" . urlencode($waypoints_labels[$waypoints_label_iter] . '|' . $destination);

           if ($transport=='plane')
              { $markers = implode($markers, '&');
                return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=geodesic:true|weight:3|color:0xF1A033|$origin|$destination&$markers&scale=4&key=".$key;
              } 
           else
              {
               if ($transport=='train') $mode = 'TRAIN';
               if ($transport=='vehicle') $mode = 'DRIVING';
               
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".urlencode($origin)."&destination=".urlencode($destination)."&mode=$mode&transit_mode=$mode&key=".$key;
                $result = file_get_contents($url);
                $googleDirection = json_decode($result, true);

                $polyline = urlencode($googleDirection['routes'][0]['overview_polyline']['points']);
                $markers = implode($markers, '&');
                
                return "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=weight:3|color:0xF1A033|enc:$polyline&$markers&mode=$mode&transit_mode=$mode&scale=2&key=".$key;
              }
       }
       
       function getDistance($addressFrom, $addressTo, $unit, $key){
           //Change address format
           $formattedAddrFrom = urlencode($addressFrom);//str_replace(' ','+',$addressFrom);
           $formattedAddrTo = urlencode($addressTo);//str_replace(' ','+',$addressTo);
           
           //Send request and receive json data
           $geocodeFrom = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$key);
           $outputFrom = json_decode($geocodeFrom);
           $geocodeTo = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$key);
           $outputTo = json_decode($geocodeTo);
           
           //Get latitude and longitude from geo data
           $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
           $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
           $latitudeTo = $outputTo->results[0]->geometry->location->lat;
           $longitudeTo = $outputTo->results[0]->geometry->location->lng;
           
           //Calculate distance from latitude and longitude
           $theta = $longitudeFrom - $longitudeTo;
           $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
           $dist = acos($dist);
           $dist = rad2deg($dist);
           $miles = $dist * 60 * 1.1515;
           $unit = strtoupper($unit);
           if ($unit == "K") {
               return ($miles * 1.609344); //.' km';
           } else if ($unit == "N") {
               return ($miles * 0.8684); //.' nm';
           } else {
               return $miles; //.' mi';
           }
       }
       
       function getMapFilters($place,$filter,$key) {
            global $userdata;
            $factor =1000;
            $scale = 'K';
            $radius=10000;
            if($userdata['scale']=='imperial'){
            $factor = 1609;
            $radius=16090;
            $scale = 'M';
            }
            
           if(!empty($this->trip_option_circle)){
                    $circle_data = explode('::',$this->trip_option_circle);
                    $lat_to = $circle_data[0];
                    $lng_to = $circle_data[1];
                    $radius = $circle_data[2];
                    $radius = round($radius*$factor);
                    $showclear =1;    
                }
 
          $mode = '';
          if ($this->trip_transport == 'vehicle') $mode = 'driving'; 
          if ($this->trip_transport == 'train') $mode = 'driving'; //'transit'; 
          $tmp = '';
          $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".urlencode($place)."&radius=".$radius."&type=$filter&key=".$key;          
          $result = file_get_contents($url);
          $googleDirection = json_decode($result, true);
  
            
          
          if ($googleDirection['status']=='OK')
             { for ($i=0;$i<20;$i++)
                  { //if ($googleDirection['results'][$i]['name'])
                       /*$tmp .= '<table width="60%" border="0">
                                  <tr>
                                    <td rowspan="2" width="20%"></td>
                                    <td width="10%" rowspan="2"><img src="'.SITE.'images/map-icons/'.$filter.'.png" /></td>
                                    <td><b>Name</b>: '.$googleDirection['results'][$i]['name'].'</td>
                                  </tr>
                                  <tr>
                                    <td><b>Address</b>: '.$googleDirection['results'][$i]['vicinity'].'</td>
                                  </tr>
                                  <tr>
                                     <td>&nbsp;</td>
                                     <td></td>
                                     <td></td>
                                  </tr>
                                </table>';*/                        
                        if ($mode)
                           { $apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=".$userdata['scale']."&mode=".$mode."&origins=".urlencode($this->trip_location_to_drivingportion)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                             $api = file_get_contents($apiurl);
                             $data = json_decode($api,true);
                             //$distance = ((int)$data['rows'][0]['elements'][0]['distance']['value'] / 1000); // Km
                             $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                             //$distance = 'xxx';
                           }
                        else   
                           { //$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".urlencode($this->trip_location_from)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                             $distance = $this->getDistance($this->trip_location_to_drivingportion,$googleDirection['results'][$i]['vicinity'],$scale,$key);
                             
                           }
                      if ($googleDirection['results'][$i]['name'])  
                        $tmp .= '<table width="90%" border="0" align="center" bgcolor="#EFF7FC" style="margin-bottom:10px">
                                  <tr><td colspan="4" height="15px"></td></tr>
                                  <tr>
                                    <td width="16%"></td>
                                    <td width="10%" align="center" valign="middle"><img src="'.SITE.'images/map-icons/'.$filter.'.png" /></td>
                                    <td width="58%" style="color:#555555; font-family: OpenSansRegular; font-size:16px">Name: '.$googleDirection['results'][$i]['name'].' Address: '.$googleDirection['results'][$i]['vicinity'].'<br />
                                                                          Distance from destination point: '.round((float)$distance,2).' '.$scale.' </td>
                                    <td width="16%">location_to: '.$this->trip_location_to_drivingportion.'</td>    
                                  </tr>
                                  <tr><td colspan="4" height="15px"></td></tr>                                                                   
                                </table>';
                  }
             }

          return $tmp;
       }
       
       function getMapEmbassis($place,$key,$list = '') {
         global $userdata;
            $factor =1000;
            $scale = 'K';
            $radius=10000;
            if($userdata['scale']=='imperial'){
            $factor = 1609;
            $radius=16090;
            $scale = 'M';
            }
            
          $tmp = '';
          $mode = '';
          if ($this->trip_transport == 'vehicle') $mode = 'driving'; 
          if ($this->trip_transport == 'train') $mode = 'driving'; //'transit';
          $elist = explode(',',$list);
          $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".urlencode($place)."&radius=".$radius."&type=embassy&key=".$key;          
          $result = file_get_contents($url);
          $googleDirection = json_decode($result, true);
          
          if ($googleDirection['status']=='OK')
             { for ($i=0;$i<20;$i++)
                  { if ($googleDirection['results'][$i]['name'] && in_array($googleDirection['results'][$i]['place_id'],$elist))
                       { if ($mode)
                            { $apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=".$userdata['scale']."&mode=".$mode."&origins=".urlencode($this->trip_location_from)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                              $api = file_get_contents($apiurl);
                              $data = json_decode($api,true);
                              //$distance = ((int)$data['rows'][0]['elements'][0]['distance']['value'] / 1000);  // Km
                              $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                            }
                         else   
                            { //$apiurl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".urlencode($this->trip_location_from)."&destinations=".urlencode($googleDirection['results'][$i]['vicinity'])."&key=".$key;
                              $distance = $this->getDistance($this->trip_location_to,$googleDirection['results'][$i]['vicinity'],$scale,$key);
                            }                           
                         $tmp .= '<table width="100%" border="0">
                                   <tr>
                                     <td colspan="4" align="center">
                                     <p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>'.$googleDirection['results'][$i]['name'].'</b></p>
                                     <img src="https://maps.googleapis.com/maps/api/staticmap?center='.urlencode($googleDirection['results'][$i]['vicinity']).'&maptype=hybrid&zoom=17&style=feature:all|element:labels.text|visibility:off&markers=color:0xff0000%7C'.urlencode($googleDirection['results'][$i]['vicinity']).'&size=640x250&scale=2&key='.$key.'" width="100%" />
                                     </td>
                                   </tr>
                                   <tr>
                                      <td colspan="4">&nbsp;</td>                                     
                                   </tr> 
                                   <tr>
                                     <td rowspan="3" width="10%"></td>
                                     <td width="10%" rowspan="3"><img src="'.SITE.'images/map-icons/embassy.png" /></td>
                                     <td width="40%"><b>Name</b>: '.$googleDirection['results'][$i]['name'].'</td>
                                     <td><!--<b>Phone</b>: '.$googleDirection['results'][$i]['formatted_phone_number'].'--></td>
                                   </tr>
                                   <tr>
                                     <td><b>Address</b>: '.$googleDirection['results'][$i]['vicinity'].'</td>
                                     <td><!--<b>Website</b>: '.$googleDirection['results'][$i]['website'].'--></td>
                                   </tr>
                                   <tr>
                                     <td><b>Distance from destination point: </b>: '.round((float)$distance,2).' '.$scale.'</td>
                                     <td></td>
                                   </tr>                                                                    
                                   <tr>
                                      <td colspan="4">&nbsp;</td>                                     
                                   </tr>
                                 </table>';
                       }
                  }
             }

          return $tmp;
       }
       
     /*  function getWeatherFilters($place,$pdf=0) {
          $tmp = '';
          global $userdata;
          $url = "http://api.openweathermap.org/data/2.5/forecast?q=".urlencode($place)."&cluster=yes&units=".$userdata['scale']."&format=json&APPID=d4182585780d3774b783c699d6dab316";          
          $result = file_get_contents($url);
          $googleDirection = json_decode($result, true);
         if(!$pdf){ 
          if ($googleDirection['cod'])
             { $tmp = '<table width="100%"><tr><td>';
               for ($i=0;$i<5;$i++)
                   { if ($i==0)
                        { $tmp .= '<span class="dest1">' .$place. '</span><br />
                                   <span class="date1">' . date('l - d F', $googleDirection['list'][0]['dt']) .'</span><br />
                                   <div class="date2"><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][0]['weather'][0]['icon']  . '.png" alt="">'.'<big>' . $googleDirection['list'][0]['main']['temp'] . '&#8457;</big></div>'; 
                        }
                     else
                        { $tmp .= '<div class="date3"><b>'.date('D', $googleDirection['list'][$i]['dt']).'</b><br /><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][$i]['weather'][0]['icon'].'.png" alt="">'.'<b style="color:#F08A0D">'.round($googleDirection['list'][$i]['main']['temp_min']).'&#8457;'.'</b><br />'.round($googleDirection['list'][$i]['main']['temp_max']).'&#8457;</div>';        
                        }
                   }
                $tmp .= '</td></tr></table>';   
             }
         }else{
          //-----------------------PDF version----------------------------
            if ($googleDirection['cod'])
             { $tmp = '<table width="100%"><tr><td><table width="100%" style="text-align:center">';
               for ($i=0;$i<5;$i++)
                   { if ($i==0)
                        { $tmp .= '<tr><td colspan="4"><span style="font-size:25px; color:#F08A0D">' .$place. '</span><br />' . date('l - d F', $googleDirection['list'][0]['dt']) .'</td></tr>
                                   <tr><td colspan="4"><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][0]['weather'][0]['icon']  . '.png" width="60px" style="margin-top:10px; margin-bottom:-20px;" alt="">'.'<big style="font-size:25px;">' . $googleDirection['list'][0]['main']['temp'] . '&#8457;</big></td></tr><tr><td colspan="4"><br /></td></tr><tr>'; 
                        }
                     else
                        { $tmp .= '<td><b>'.date('D', $googleDirection['list'][$i]['dt']).'</b><br /><img src="http://openweathermap.org/img/w/' . $googleDirection['list'][$i]['weather'][0]['icon'].'.png" alt="">'.'<br /><b style="color:#F08A0D">'.round($googleDirection['list'][$i]['main']['temp_min']).'&#8457;'.'</b> - '.round($googleDirection['list'][$i]['main']['temp_max']).'&#8457;</td>';        
                        }
                   }
                $tmp .= '</tr></table></td></tr></table>';   
             }
             
         }
          return $tmp;
       } */
      function getWeatherFilters($place,$pdf=0) {
          $tmp = '';
          global $userdata;
          $xml=simplexml_load_file("http://api.openweathermap.org/data/2.5/forecast?q=".$place."&units=".$userdata['scale']."&mode=xml&APPID=d4182585780d3774b783c699d6dab316") or die("Error: Cannot create object xml");
 $weather_data='';
                         if($xml){ 
                             $weather_data.= '<table width="250" border="1">';
                             $location = $xml->location->name.', '.$xml->location->country;     
                             $sunrise = explode('T',$xml->sun['rise']);  
                             $sunrise = explode(':',$sunrise[1]);
                             $sunrise = $sunrise[0].':'.$sunrise[1]; 
                             $sunset = explode('T',$xml->sun['set']); 
                             $sunset = explode(':',$sunset[1]);
                             $sunset = $sunset[0].':'.$sunset[1]; 
                             
                             $i=$j=0;
                             foreach($xml->forecast->time as $value){
                               $i++;
                               $j++;
                               if($units=='imperial'){
                                   $temp_unit = 'F';
                                   $speed_unit = 'mph';
                               }else{
                                   $temp_unit = 'C';  
                                   $speed_unit = 'Km/h';
                               }
                                    $date_from = str_replace('T',' ',$value['from']);
                                    $from     = date('D',strtotime($date_from));
                                    $wind     = $value->windDirection['code'].' '.$value->windSpeed['mps'].' '.$speed_unit;
                                    $Humidity     = $value->humidity['value'].' '.$value->humidity['unit'];
                                      
                                    $date_to = str_replace('T',' ',$value['to']);
                                    $to = date('d F Y h:i a',strtotime($date_to));
                                    
                               
                                   if($j==1){
                                       $clouds   = $value->clouds['value'];  
                                       $temp_cur     = $value->temperature['value'].' &#176;'.$temp_unit;
                                       $from_header  = date('l, d.m.Y',strtotime($date_from));
                                       switch($clouds){
                                           case 'overcast clouds': $imgname='overcast_clouds.png';
                                                  break;
                                           case 'broken clouds': $imgname='few_clouds.png';
                                                  break;
                                           case 'scattered clouds': $imgname='scattered_clouds.png';
                                                  break;
                                           case 'few clouds': $imgname='few_clouds.png';
                                                  break;
                                           case 'clear sky': $imgname='clear_sky.png';
                                                  break;
                                           default : $imgname='clear_sky.png';      
                                                  break;
                                           
                                       }
                                       $weather_data.= '
                                            <tr>
                                              <td colspan="5">'.$location.'<br />'.$from_header.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><img src="'.SITE.'images/weather-icons/'.$imgname.'" />'.$temp_cur.'<br />
                                                    Current: '.$clouds.'<br />
                                                    Wind: '.$wind.'<br />
                                                    Humidity: '.$Humidity.'<br />
                                                    Sunrise: '.$sunrise.' Sunset: '.$sunset.'
                                                </td>
                                            </tr>

                                       <tr>
                                        <td >
                                           <table border="1">
                                                <tr>
                                                ';
                                                   }  
                                                   if($i==3){
                                                       $t_max = floatval($value->temperature['max']);
                                                       $temp_max = number_format($t_max,0).' &#176;'.$temp_unit;
                                                   }  
                                                   if($i==8){  
                                                        $clouds   = $value->clouds['value']; 
                                                        switch($clouds){
                                                               case 'overcast clouds': $imgname='temp_sun2.png';
                                                                      break;
                                                               case 'broken clouds': $imgname='temp_sun.png';
                                                                      break;
                                                               case 'scattered clouds': $imgname='temp_rain2.png';
                                                                      break;
                                                               case 'few clouds': $imgname='temp_rain.png';
                                                                      break;
                                                               case 'clear sky': $imgname='clear_sky.png';
                                                                      break;
                                                               default : $imgname='clear_sky.png';      
                                                                      break;
                                                               
                                                           }
                                       
                                                       $t_min = floatval($value->temperature['min']);
                                                       $temp_min  = number_format($t_min,0).' &#176;'.$temp_unit;
                                                       $weather_data.= '<td>
                                                                 <img width="34" height="34" src="'.SITE.'images/weather-icons/'.$imgname.'" /><br />
                                                                 '.$from.'<br />
                                                                 '.$temp_max.' <br />
                                                                 '.$temp_min.'<br />
                                                       </td>';
                                                       $i=0;
                                                   }
                                                      
                             }
                             $weather_data.= '             </tr>
                                            </table>
                                        </td>
                                      </tr>
                                      </table>';
                                      return $weather_data;
                         }
          
       } 
       
       function edit_data_pdf($id) 
          { global $dbh;
          
            $query = "UPDATE ".$this->table." SET pdf_generated = ? WHERE id_trip = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, 1, PDO::PARAM_STR);
            $stmt->bindValue(2, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
          }  
                 
     }
 
?>