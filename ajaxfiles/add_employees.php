<?php
  
  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }
  
  $error = '';
  include("../class/class.Employee.php");
  $employee = new Employee();
  if (isset($_POST['id']) && !empty($_POST['id']))     // del element
     { $employee->del_data($_POST['id']);
       if ($employee->error) $error = 'A system error has been encountered. Please try again.';
       $res['txt'] = '';
       $res['error'] = $error;
       echo json_encode($res);
     }  
  else                                                // add note
     { $_tmp = '';
       if ($_POST['fname'] && $_POST['lname'] && $_POST['empid']) 
          {  $fname = filter_var($_POST["fname"], FILTER_SANITIZE_STRING);
             $lname = filter_var($_POST["lname"], FILTER_SANITIZE_STRING);
             $empid = filter_var($_POST["empid"], FILTER_SANITIZE_STRING);
             $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
             $city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
             $state = filter_var($_POST["state"], FILTER_SANITIZE_STRING);
             $zipcode = filter_var($_POST["zipcode"], FILTER_SANITIZE_STRING);
             $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
             $dlnum = filter_var($_POST["dlnum"], FILTER_SANITIZE_STRING);
             $dlstate = filter_var($_POST["dlstate"], FILTER_SANITIZE_STRING);
             $dldate = filter_var($_POST["dldate"], FILTER_SANITIZE_STRING);
             $ssn = filter_var($_POST["ssn"], FILTER_SANITIZE_STRING);
             $bdate = filter_var($_POST["bdate"], FILTER_SANITIZE_STRING);
             $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
             $gender = filter_var($_POST["gender"], FILTER_SANITIZE_STRING);
             $race = filter_var($_POST["race"], FILTER_SANITIZE_STRING);
             $veteran = filter_var($_POST["veteran"], FILTER_SANITIZE_STRING);
             $doc1 = filter_var($_POST["doc1"], FILTER_SANITIZE_STRING); $doc1 = str_replace('[','',$doc1); $doc1 = str_replace(']','',$doc1); $doc1 = str_replace('&#34','',$doc1); $doc1 = str_replace(';','',$doc1);
             $doc2 = filter_var($_POST["doc2"], FILTER_SANITIZE_STRING); $doc2 = str_replace('[','',$doc2); $doc2 = str_replace(']','',$doc2); $doc2 = str_replace('&#34','',$doc2); $doc2 = str_replace(';','',$doc2);
             $doc3 = filter_var($_POST["doc3"], FILTER_SANITIZE_STRING); $doc3 = str_replace('[','',$doc3); $doc3 = str_replace(']','',$doc3); $doc3 = str_replace('&#34','',$doc3); $doc3 = str_replace(';','',$doc3);
             $doc4 = filter_var($_POST["doc4"], FILTER_SANITIZE_STRING); $doc4 = str_replace('[','',$doc4); $doc4 = str_replace(']','',$doc4); $doc4 = str_replace('&#34','',$doc4); $doc4 = str_replace(';','',$doc4);
             $employee->put_data($userdata['id'],$fname,$lname,$empid,$address,$city,$state,$zipcode,$phone,$dlnum,$dlstate,$dldate,$ssn,$bdate,$email,$gender,$race,$veteran,$doc1,$doc2,$doc3,$doc4);
             if ($employee->error)
                $error = 'A system error has been encountered. Please try again.';                     
             else
                { $_tmp = '<div class="add_note" id="employee_'.$dbh->lastInsertId().'" >
                              <p>'.$empid.' --- '.
                              $fname.' '.$lname.' --- '.
                              '</p><a onclick="del_element('.$dbh->lastInsertId().')"><img src="'.SITE.'images/delete.png" title="Delete" /></a>
                              <a class="more_det" id="more'.$dbh->lastInsertId().'" onclick="see_detail('.$dbh->lastInsertId().')">more details</a>
                              <div class="det_emp" id="details'.$dbh->lastInsertId().'" style="display:none">
                                  <strong>First Name:</strong> '.$fname.'<br />
                                  <strong>Last Name:</strong> '.$lname.'<br />
                                  <strong>Employee ID</strong>: '.$empid.'<br />
                                  <strong>Address:</strong> '.$address.'<br />
                                  <strong>City:</strong> '.$city.'<br />
                                  <strong>State:</strong> '.$state.'<br />
                                  <strong>Zip Code:</strong> '.$zipcode.'<br />
                                  <strong>Phone Number:</strong> '.$phone.'<br />
                                  <strong>Driver\'s License Number:</strong> '.$dlnum.'<br />
                                  <strong>Driver\'s License State:</strong> '.$dlstate.'<br />
                                  <strong>Driver\'s License Expiration:</strong> '.$dldate.'<br />
                                  <strong>Social Security Number:</strong> '.$ssn.'<br />
                                  <strong>Birthdate</strong>: '.$bdate.'<br />
                                  <strong>Email:</strong> '.$email.'<br />
                                  <strong>Gender:</strong> '.($gender=='f' ? 'Female' : 'Male').'<br />
                                  <strong>Race:</strong> '.$race.'<br />
                                  <strong>Veteran:</strong> '.($veteran ? 'yes' : 'no').'<br />';
                    if ($doc1) $_tmp .= '<strong>Document 1:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/documents/'.$doc1.'">'.$doc1.'</a><br />';                                  
                    if ($doc2) $_tmp .= '<strong>Document 2:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/documents/'.$doc2.'">'.$doc2.'</a><br />';                                  
                    if ($doc3) $_tmp .= '<strong>Document 3:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/documents/'.$doc3.'">'.$doc3.'</a><br />';                                  
                    if ($doc4) $_tmp .= '<strong>Document 4:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/documents/'.$doc4.'">'.$doc4.'</a><br />';                                  
                    $_tmp .= '</div>
                            </div>';
                  $error = '';
                } 
            $res['txt'] = $_tmp;
            $res['error'] = $error;
            echo json_encode($res);   
          }
       else
          { if (!$_POST['empid']) $error = 'Please write the Employee ID';
            if (!$_POST['lname']) $error = 'Please write the Last Name';
            if (!$_POST['fname']) $error = 'Please write the First Name'; 
            $res['txt'] = '';
            $res['error'] = $error;
            echo json_encode($res);
          }
     }         
?>
