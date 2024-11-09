<?php
include '../../config.ini.php';

if (isset($_POST['job_name']) && isset($_POST['job_category']) && isset($_POST['job_details'])) {
   

    $contact_number_list = null;
        
    $employee_docname = 0;
    $employee_id = 0;

    if (isset($_POST['contact_number'])) {
        $contact_number_list = implode(",", $_POST['contact_number']);
    }

    if (isset($_POST['upload_item'])) {
        $employee_docname = $_POST['upload_item'];
    }

    if (isset($_POST['employee_id'])) {
        $employee_id = $_POST['employee_id'];
    }


    $user_id= $userdata['uid'];    

    $data = [
        $user_id,
        $_POST['job_name'],
        $_POST['job_category'],
        $_POST['job_details'],
        $contact_number_list,
        $_POST['job_address'],
        $_POST['job_city'],
        $_POST['job_state'],
        $_POST['job_zcode'],                
    ];

    $sql = "INSERT INTO jobs (id_user,name, category,details, contact_number, address, city, state, zip_code)
    VALUES (?,?,?,?,?,?,?,?,?)";

    $stmt = $dbh->prepare($sql);
    if (!$stmt->execute($data)) {
        $msg = $stmt->errorInfo()[2];

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered",
        );

        http_response_code(422);
    } else {
        $lastID = $dbh->lastInsertId();

        if($employee_docname){

           $employee_docname = explode(",", $employee_docname);
           
           foreach($employee_docname as $row) {
            $mData[]= 
            array(
                "user_id"=> $user_id,
                "event_id"=> $lastID,
                "doc_name"=> $row,
                "type" => 'job'
            );
           }                     
           
           $sql = 'INSERT INTO event_doc(user_id, event_id, doc_name, type) VALUES(:user_id, :event_id, :doc_name, :type )';
           
           $statement = $dbh->prepare($sql);
           
           foreach($mData as $row) {
               $statement->execute($row); 
           }

        }


        if($employee_id){

           
            foreach($employee_id as $row) {
             $mData[]= 
             array(
                 "id_job"=> $lastID,
                 "id_employee"=> $row,
                 
             );
            }                     
            
            $sql = 'INSERT INTO `employee-job`(id_job, id_employee) VALUES(:id_job, :id_employee)';
            
            $state = $dbh->prepare($sql);
            
            foreach($mData as $row) {
                $state->execute($row); 
            }
 
         }        
        
        $response = array(
            "status" => 200,
            "message" => "Successfully Job Added",
        );

        http_response_code(200);
    }


    echo json_encode($response);  


}
