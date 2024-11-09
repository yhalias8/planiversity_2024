<?php
  
  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }
  
  $error = '';
  include("../class/class.Timeline.php");
  $timeline = new Timeline();
  if (isset($_POST['id']) && !empty($_POST['id']))     // get element
     { $timeline->get_data($_POST['id']);
       if ($timeline->error) 
           $error = 'A system error has been encountered. Please try again.';
       else
         { $res['name'] = $timeline->timeline_title;
           $res['fulldate'] = $timeline->timeline_date;
           $res['date'] = date('Y-m-d',strtotime($timeline->timeline_date));
           $res['time'] = date('H:i:s',strtotime($timeline->timeline_date));
         }
       $res['error'] = $error;
       echo json_encode($res);
     }  
  else                                                // edit schedule
     { $_tmp = '';
       if ($_POST['name'] && $_POST['tdate'] && $_POST['idt']) 
          {  $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
             $timeline->edit_data($_POST['idt'],$name,$_POST['tdate']);
             if ($timeline->error)
                $error = 'A system error has been encountered. Please try again.';                     
             else
                { $_tmp = '<p>'.date('M d,Y h:i a',strtotime($_POST['tdate'])).' --- '.$name.
                           '</p><a onclick="del_element('.$_POST['idt'].')"><img src="'.SITE.'images/delete.png" title="Delete" /></a>
                           <a onclick="del_element('.$_POST['idt'].')"><img src="'.SITE.'images/icon_edit2.png" title="Edit" /></a>';
                           $_tmp = '<div class = "note-result-wrap" id="timeline_'.$dbh->lastInsertId().'">
                                        <p>'.date('M d, h:i',strtotime($_POST['tdate'])).'&nbsp;&nbsp;&nbsp;<span style="color:#78859A;">'.$name.'</span>
                                          <a href = "#" onclick="del_element('.$_POST['idt'].')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                             <i class = "fa fa-times-circle edit-icon"  style="color:#058BEF;"></i>
                                          </a>
                                          <a href = "#" onclick="toggle_edit_form(\''.$_POST['idt'].'\');" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                 <i class = "fa fa-pencil (alias) edit-icon" style="color:#058BEF;"></i>
                                          </a>
                                        </p> 
                                        <div id="'.$_POST['idt'].'" style="display:none;">
                                            <div class = "row">
                                               <div class="col-md-10">
                                                  <input name="nm_'.$_POST['idt'].'" id="name_'.$_POST['idt'].'" type="text" class="edit-form-control input-lg" placeholder="'.date('M d, Y h:i a',strtotime($_POST['tdate'])).' --- '.$name.'" required="">
                                               </div>
                                               <div class="col-md-2">
                                                  <button onClick="timeline_edit('.$_POST['idt'].',\'name_'.$_POST['idt'].'\',\''.$_POST['tdate'].'\')"  class="save-edit-btn">Save</button>
                                               </div>
                                             </div>
                                        </div>
                                    </div>';
                  $error = '';
                }
            $res['txt'] = $_tmp;
            $res['error'] = $error;
            echo json_encode($res);   
          }
       else
          { if (!$_POST['name']) $error = 'Please write an event title';
            if (!$_POST['date']) $error = 'Please select a date';
            $res['txt'] = '';
            $res['error'] = $error;
            echo json_encode($res);
          }
     }     
?>
