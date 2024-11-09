<?php
  
  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }
  
  $error = '';
  include("../class/class.Note.php");
  $note = new Note();
  if (isset($_POST['id']) && !empty($_POST['id']))     // del element
     { $note->del_data($_POST['id']);
       if ($note->error) $error = 'A system error has been encountered. Please try again.';
       $res['txt'] = '';
       $res['error'] = $error;
       echo json_encode($res);
     }  
  else                                                // add note
     { $_tmp = '';
       if ($_POST['name'] && $_POST['trip']) 
          {  $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
             $note->put_data($_POST['trip'],$name);
             if ($note->error)
                $error = 'A system error has been encountered. Please try again.';                     
             else
                { $_tmp = '<div class="add_note" id="note_'.$dbh->lastInsertId().'" >
                             <p>'.date('M d, Y').' --- '.$name.
                             '</p><a onclick="del_element('.$dbh->lastInsertId().')"><img src="'.SITE.'images/delete.png" title="Delete" /></a>
                           </div>';
                           $_tmp = '<div class = "note-result-wrap" id="note_'.$dbh->lastInsertId().'">
                                        <p>'.$name.
                                            '<a onclick="del_element(\''.$dbh->lastInsertId().'\')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class = "fa fa-times-circle edit-icon"  style="color:#058BEF;"></i></a>
                                        </p>
                                    </div>';
                  //$error = 'The data has been added successfully.';
                  $error = '';
                }
            $res['txt'] = $_tmp;
            $res['error'] = $error;
            echo json_encode($res);   
          }
       else
          { if (!$_POST['name']) $error = 'Please write a note';
            $res['txt'] = '';
            $res['error'] = $error;
            echo json_encode($res);
          }
     }         
?>
