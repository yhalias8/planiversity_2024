<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.Timeline.php");
include("../../class/class.PlanTrip.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])){
        
       
        $timeline = new Timeline();  
        
        $id = $_POST['id'];
        $plan_linked = $_POST['plan_linked'];        

        $timeline->del_data($id);
        
        if ($plan_linked == 1) {
            $plantrip = new PlanTrip();
            $plantrip->del_schedule_data($id);
        }        
        
        if ($timeline->error){

        $response = array (
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",            
        );
    
        http_response_code(422);            

        }else{

        $response = array (  
                  "status" => 200,
                  "message" => "Successfully Schedule Deleted",              
        );

        http_response_code(200);

        }

        echo json_encode($response);        

}


