<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_FILES["upload_preview_image"]) && isset($_POST['id'])) {

    $output_dir = "../cover_image/";
    $return_dir = "ajaxfiles/cover_image/";
    $flag = 1;
    $id = $_POST['id'];

    $ret = array();

    $error = $_FILES["upload_preview_image"]["error"];

    $dots = explode(".", $_FILES["upload_preview_image"]["name"]);
    $file_name = time();
    $extention = $dots[(count($dots) - 1)];

    $fileName = $id . '_' . $userdata['id'] . '_' . $file_name . '.' . $extention;

    $return_url = SITE . $return_dir . $fileName;

    if (move_uploaded_file($_FILES["upload_preview_image"]["tmp_name"], $output_dir . $fileName)) {

        $error_ = '';

        $query = "UPDATE trips SET cover_image = ?, cover_image_url = ?,cover_image_type = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $flag, PDO::PARAM_INT);
        $stmt->bindValue(2, $return_url, PDO::PARAM_STR);
        $stmt->bindValue(3, 1, PDO::PARAM_INT);
        $stmt->bindValue(4, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $error_ = 'error_fail';
        }

        if ($error_) {
            $response = array(
                'status' => 422,
                'message' => "A system error has been encountered. Please try again",
            );

            http_response_code(422);
        } else {

            $response = array(
                "status" => 200,
                "return_url" => $return_url,
                "message" => "Successfully Cover Updated",
            );

            http_response_code(200);
        }

        echo json_encode($response);
    }
}
