<?php
  
  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }
  
  $error = '';
  include("../class/class.Job.php");
  $job = new Job();
  if (isset($_POST['id']) && !empty($_POST['id']))     // del element
     { $job->del_data($_POST['id']);
       if ($job->error) $error = 'A system error has been encountered. Please try again.';
       $res['txt'] = '';
       $res['error'] = $error;
       echo json_encode($res);
     }  
  else                                                // add job
     { $_tmp = '';
       if ($_POST['name']) 
          {  $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
             $category = filter_var($_POST["category"], FILTER_SANITIZE_STRING);
             $details = filter_var($_POST["details"], FILTER_SANITIZE_STRING);
             $cnumbers = filter_var($_POST["cnumbers"], FILTER_SANITIZE_STRING);
             $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
             $employees = $_POST["employees"]; //$employees = filter_var($_POST["employees"], FILTER_SANITIZE_STRING);
             $city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
             $state = filter_var($_POST["state"], FILTER_SANITIZE_STRING);
             $zcode = filter_var($_POST["zcode"], FILTER_SANITIZE_STRING);
             $doc1 = filter_var($_POST["doc1"], FILTER_SANITIZE_STRING); $doc1 = str_replace('[','',$doc1); $doc1 = str_replace(']','',$doc1); $doc1 = str_replace('&#34','',$doc1); $doc1 = str_replace(';','',$doc1);
             $doc2 = filter_var($_POST["doc2"], FILTER_SANITIZE_STRING); $doc2 = str_replace('[','',$doc2); $doc2 = str_replace(']','',$doc2); $doc2 = str_replace('&#34','',$doc2); $doc2 = str_replace(';','',$doc2);
             $doc3 = filter_var($_POST["doc3"], FILTER_SANITIZE_STRING); $doc3 = str_replace('[','',$doc3); $doc3 = str_replace(']','',$doc3); $doc3 = str_replace('&#34','',$doc3); $doc3 = str_replace(';','',$doc3);
             $doc4 = filter_var($_POST["doc4"], FILTER_SANITIZE_STRING); $doc4 = str_replace('[','',$doc4); $doc4 = str_replace(']','',$doc4); $doc4 = str_replace('&#34','',$doc4); $doc4 = str_replace(';','',$doc4);
             $doc5 = filter_var($_POST["doc5"], FILTER_SANITIZE_STRING); $doc5 = str_replace('[','',$doc5); $doc5 = str_replace(']','',$doc5); $doc5 = str_replace('&#34','',$doc5); $doc5 = str_replace(';','',$doc5);
             $doc6 = filter_var($_POST["doc6"], FILTER_SANITIZE_STRING); $doc6 = str_replace('[','',$doc6); $doc6 = str_replace(']','',$doc6); $doc6 = str_replace('&#34','',$doc6); $doc6 = str_replace(';','',$doc6);
             $doc7 = filter_var($_POST["doc7"], FILTER_SANITIZE_STRING); $doc7 = str_replace('[','',$doc7); $doc7 = str_replace(']','',$doc7); $doc7 = str_replace('&#34','',$doc7); $doc7 = str_replace(';','',$doc7);
             $doc8 = filter_var($_POST["doc8"], FILTER_SANITIZE_STRING); $doc8 = str_replace('[','',$doc8); $doc8 = str_replace(']','',$doc8); $doc8 = str_replace('&#34','',$doc8); $doc8 = str_replace(';','',$doc8);
             $job->put_data($userdata['id'],$name,$category,$details,$cnumbers,$address,$city,$state,$zcode,$doc1,$doc2,$doc3,$doc4,$doc5,$doc6,$doc7,$doc8);
             if ($job->error)
                $error = 'A system error has been encountered. Please try again.';                     
             else
                { $_lastid = $dbh->lastInsertId();
                  $job->put_je($_lastid,$employees);
                  $_tmp = '<div class="add_note" id="job_'.$_lastid.'" >
                              <p>'.$name.' --- '.$category.
                              '</p><a onclick="del_element('.$_lastid.')"><img src="'.SITE.'images/delete.png" title="Delete" /></a>
                              <a class="more_det" id="more'.$_lastid.'" onclick="see_detail('.$_lastid.')">more details</a>
                              <div class="det_emp" id="details'.$_lastid.'" style="display:none">
                                  <strong>Name:</strong> '.$name.'<br />
                                  <strong>Category:</strong> '.$category.'<br />
                                  <strong>Job Details:</strong> '.$details.'<br />
                                  <strong>Contact Numbers:</strong> '.$cnumbers.'<br />
                                  <strong>Address:</strong> '.$address.'<br />
                                  <strong>Employees:</strong> '.$job->get_employee_list($_lastid).'<br />
                                  <strong>City:</strong> '.$city.'<br />
                                  <strong>State:</strong> '.$state.'<br />
                                  <strong>Zip code:</strong> '.$zcode.'<br />';
                    if ($doc1) $_tmp .= '<strong>Document 1:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc1.'">'.$doc1.'</a><br />';                                  
                    if ($doc2) $_tmp .= '<strong>Document 2:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc2.'">'.$doc2.'</a><br />';                                  
                    if ($doc3) $_tmp .= '<strong>Document 3:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc3.'">'.$doc3.'</a><br />';                                  
                    if ($doc4) $_tmp .= '<strong>Document 4:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc4.'">'.$doc4.'</a><br />';
                    if ($doc5) $_tmp .= '<strong>Equipment 1:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc5.'">'.$doc5.'</a><br />';                                  
                    if ($doc6) $_tmp .= '<strong>Equipment 2:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc6.'">'.$doc6.'</a><br />';                                  
                    if ($doc7) $_tmp .= '<strong>Equipment 3:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc7.'">'.$doc7.'</a><br />';                                  
                    if ($doc8) $_tmp .= '<strong>Equipment 4:</strong> <a target="_blank" href="'.SITE.'ajaxfiles/equipment/'.$doc8.'">'.$doc8.'</a><br />';                                  
                    $_tmp .= '</div>
                            </div>';
                  $error = '';
                }
            $res['txt'] = $_tmp;
            $res['error'] = $error;
            echo json_encode($res);   
          }
       else
          { if (!$_POST['name']) $error = 'Please write the Job Name'; 
            $res['txt'] = '';
            $res['error'] = $error;
            echo json_encode($res);
          }
     }         
?>
