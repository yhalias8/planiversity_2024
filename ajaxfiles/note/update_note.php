<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.Note.php");
include("../list_process.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['notes_text'] && $_POST['note_id']) {
        
        $note = new Note();    

        $notes_text = filter_var($_POST["notes_text"], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST["note_id"], FILTER_SANITIZE_STRING);      
        
        $trip_generated = $_POST["trip_generated"];
        $trip_u_id = $_POST["trip_u_id"];
        $trip_title = $_POST["trip_title"];        
        
        $note->edit_data($id,$notes_text);
        
        if ($trip_generated == 1) {

        $data = [
            "UserId" => $trip_u_id,
            "NotificationTitle" => "Note Updated",
            "NotificationBody" => notificationBodyProcess("update", "note", $trip_title)
        ];

        $fields = json_encode($data);

        $mData = curlRequestPost($API_URL, $TOKEN, $fields);

        }        

        if ($note->error){

        $response = array (
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",            
        );
    
        http_response_code(422);            

        }else{

        $response = array (  
                  "status" => 200,
                  "message" => "Successfully Note Updated",              
        );

        http_response_code(200);

        }

        echo json_encode($response);        

}


