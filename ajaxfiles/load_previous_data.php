<?php

include '../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['flag']) {

    $uid = $userdata['id'];

    $stmt = $dbh->prepare("SELECT fname,lname,country,address,city,state,zipcode FROM payments WHERE id_user = ? and save_info=? order by id_payment desc ");
    $stmt->bindValue(1, $uid, PDO::PARAM_INT);
    $stmt->bindValue(2, 1, PDO::PARAM_INT);
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
