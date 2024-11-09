<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.Note.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])){
        
       
        $note = new Note();  

        $note->del_data($_POST['id']);
        
        if ($note->error){

        $response = array (
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",            
        );
    
        http_response_code(422);            

        }else{

        $response = array (  
                  "status" => 200,
                  "message" => "Successfully Note Deleted",              
        );

        http_response_code(200);

        }

        echo json_encode($response);        

}


