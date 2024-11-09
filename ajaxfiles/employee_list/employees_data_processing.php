<?php

include '../../config.ini.php';
include_once '../../class/class.TripPlan.php';

if (isset($_POST['employee_fname']) && isset($_POST['employee_lname']) && isset($_POST['employee_email']) && isset($_POST['employee_id'])) {


    $URL = ADMIN_URL;
    $PATH = "message/group-add-message";

    $employee_docname = 0;
    $photo_connect = 0;
    $image_name = "";

    $user_id = $userdata['uid'];

    if (isset($_POST['upload_item'])) {
        $employee_docname = $_POST['upload_item'];
    }

    if (isset($_POST['photo_connect'])) {
        $photo_connect = $_POST['photo_connect'];
    }

    if ((isset($_POST['base64_image'])) && ($photo_connect == 0)) {
        $image_name = image_upload($_POST['base64_image']);
    }

    if ((isset($_POST['photo'])) && ($photo_connect == 1)) {
        $image_name = $_POST['photo'];
    }

    $st = $dbh->prepare("SELECT id FROM users as a WHERE customer_number=?");
    $st->bindValue(1, $_POST['employee_id'], PDO::PARAM_INT);
    $tp = $st->execute();

    $data = [
        $user_id,
        $_POST['employee_fname'],
        $_POST['employee_lname'],
        $_POST['employee_email'],
        $_POST['employee_id'],
        $_POST['employee_address'],
        $image_name,
        $_POST['employee_city'],
        $_POST['employee_state'],
        $_POST['employee_zcode'],
        $_POST['employee_phone'],
        $_POST['employee_ssn'],
        $_POST['employee_dlnumber'],
        $_POST['employee_dlstate'],
        $_POST['employee_dldate'] ? date('Y-m-d', strtotime($_POST['employee_dldate'])) : null,
        $_POST['employee_b'] ? date('Y-m-d', strtotime($_POST['employee_b'])) : null,
        $_POST['employee_gender'],
        $_POST['employee_race'],
        $_POST['employee_veteran'],
        $_POST['travel_group'] ? $_POST['travel_group'] : -1,
        $photo_connect,
        ($_POST['role'] ? $_POST['role'] : TripPlan::ROLE_VIEW_ONLY)
    ];


    $sql = "INSERT INTO employees (id_user,f_name, l_name, email, 
employee_id, address,photo, city, state, zip_code, phone, social_number, driver_number, driver_state, driver_expiration, birthdate,gender,race,veteran,travel_group,photo_connect,role) VALUES (?,?,?,?
,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $dbh->prepare($sql);

    if (!$stmt->execute($data)) {

        $msg = $stmt->errorInfo()[2];
        echo $msg;
        die();
        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered",
        );

        http_response_code(422);
    } else {

        $lastID = $dbh->lastInsertId();


        if ($tp && $st->rowCount() > 0) {

            $params = [
                'travel_group' => $_POST['travel_group'],
                'employee_id' => $_POST['employee_id'],
                'user_id' => $user_id
            ];


            $fields = json_encode($params);


            $API_POST_PATH = $URL . $PATH;
            $mData = curlRequestPost($API_POST_PATH, $fields);
            $rData = json_decode($mData);
        }

        $response = array(
            "status" => 200,
            "message" => "Successfully People Added",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}


function image_upload($image)
{

    $output_dir = "profile/";
    $return_dir = "../people/";

    $data = $image;

    $image_array_1 = explode(";", $data);

    $image_array_2 = explode(",", $image_array_1[1]);

    $data = base64_decode($image_array_2[1]);
    $file_name = uniqid() . '-' . time() . '.png';
    //$image_name = $output_dir . $file_name;
    $image_name = $return_dir . $file_name;
    file_put_contents($image_name, $data);


    return $file_name;
}

function curlRequestPost($url, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);


    $headers = array(
        "Content-type: application/json",
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
