<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['ref_type']) && !empty($_POST['ref_type'])) {


    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
    $ref_type = filter_var($_POST["ref_type"], FILTER_SANITIZE_STRING);


    if ($_POST["ref_type"] == 'notes') {
        $query = "DELETE FROM notes WHERE id_note = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $response = array(
            "status" => 200,
            "message" => "Successfully Deleted",
        );
        http_response_code(200);
    }


    if ($_POST["ref_type"] == 'timelines') {

        $query = "DELETE FROM timeline WHERE id_timeline = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $response = array(
            "status" => 200,
            "message" => "Successfully Deleted",
        );
        http_response_code(200);
    }

    if ($_REQUEST["table"] == 'documents') {

        $query = "DELETE FROM documents WHERE id_document = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $response = array(
            "status" => 200,
            "message" => "Successfully Deleted",
        );
        http_response_code(200);
    }

    echo json_encode($response);
}
