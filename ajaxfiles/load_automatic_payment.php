<?php

include '../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


//if ($_GET['request_id']) {

if (isset($_GET['request_id'])) {


    $uid = $userdata['id'];

    $stmt = $dbh->prepare("SELECT recurring_payment FROM users WHERE id = ? ");
    $stmt->bindValue(1, $uid, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetch(PDO::FETCH_OBJ);

        $response = array(
            "status" => 200,
            "data" => $timelines,
            "message" => "Successfully processed data",
        );

        http_response_code(200);
    } else {

        $response = array(
            'status' => 422,
            'message' => "No data found",
        );
        http_response_code(422);
    }
    echo json_encode($response);
}
