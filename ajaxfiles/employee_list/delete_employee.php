<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])) {

    $tmp = '';
    $error = '';
    $query = "DELETE FROM employees WHERE id_employee = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $_POST['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
    
    if (!$tmp){
        $error = 'error_fail';
    }        

    if ($error) {

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",
        );

        http_response_code(422);
    } else {

        $response = array(
            "status" => 200,
            "message" => "Successfully Employee Deleted",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
