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
if (isset($_POST['id']) && !empty($_POST['id']))     // del element
{
    $timeline->del_data($_POST['id']);
    if ($timeline->error) $error = 'A system error has been encountered. Please try again.';
    $res['txt'] = '';
    $res['error'] = $error;
    echo json_encode($res);
} else                                                // add schedule
{
    $_tmp = '';
    if ($_POST['name'] && $_POST['date'] && $_POST['time'] && $_POST['trip']) {
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $date_time = $_POST['date'] . " " . $_POST['time'];
        $date_time = date('Y-m-d H:i:s', strtotime($date_time));
        $timeline->put_data($_POST['trip'], $name, $date_time);

        if ($timeline->error){
            $error = 'A system error has been encountered. Please try again.';
        }
        else {
            $_tmp = '<div class="add_note" id="timeline_' . $dbh->lastInsertId() . '" >
                             <p>' . date('M d, Y h:i a', strtotime($_POST['date'])) . ' --- ' . $name .
                '</p><a onclick="del_element(' . $dbh->lastInsertId() . ')"><img src="' . SITE . 'images/delete.png" title="Delete" /></a>
                             <a onclick="edit_element(' . $dbh->lastInsertId() . ')"><img src="' . SITE . 'images/icon_edit2.png" title="Edit" /></a>
                           </div>';
            $_tmp = '<div class = "note-result-wrap" id="timeline_' . $dbh->lastInsertId() . '">
                                        <p>' . date('M d, h:i', strtotime($date_time)) . '&nbsp;&nbsp;&nbsp;<span style="color:#78859A;">'.$name.'</span>
                                          <a href = "#" onclick="del_element(' . $dbh->lastInsertId() . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                            <i class = "fa fa-times-circle edit-icon"  style="color:#058BEF;"></i>
                                          </a>
                                          <a href = "#" onclick="toggle_edit_form(\'' . $dbh->lastInsertId() . '\');" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                <i class = "fa fa-pencil (alias) edit-icon" style="color:#058BEF;"></i>
                                          </a>
                                        </p>
                                        <div id="' . $dbh->lastInsertId() . '" style="display:none;">
                                            <div class = "row">
                                               <div class="col-md-10">
                                                  <input name="nm_' . $dbh->lastInsertId() . '" name="name_' . $dbh->lastInsertId() . '" type="text" class="edit-form-control input-lg" placeholder="' . date('M d, Y h:i a', strtotime($_POST['date'])) . ' --- ' . $name . '" required="">
                                               </div>
                                               <div class="col-md-2">
                                                  <button onClick="timeline_edit(' . $dbh->lastInsertId() . ',\'name_' . $dbh->lastInsertId() . '\',\'' . $_POST['date'] . '\')"  class="save-edit-btn">Save</button>
                                               </div>
                                             </div>
                                        </div>
                                    </div>';
            //$error = 'The data has been added successfully.';
            $error = '';
        }
        $res['txt'] = $_tmp;
        $res['error'] = $error;
        echo json_encode($res);
    } else {
        if (!$_POST['name']) $error = 'Please write an event title';
        if (!$_POST['date']) $error .= "<br/> &nbsp&nbsp Please select a date";
        if (!$_POST['time']) $error .= "<br/> &nbsp&nbsp Please select a time";
        $res['txt'] = '';
        $res['error'] = $error;
        echo json_encode($res);
    }
}
?>
