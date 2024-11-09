<?php
include '../../config.ini.php';

if (isset($_POST['employee_fname']) && isset($_POST['employee_lname']) && isset($_POST['employee_email']) && isset($_POST['employee_id'])) {


    $URL = ADMIN_URL;
    $PATH = "message/group-add-message";

    $user_id = $userdata['uid'];

    $employee_fname = filter_var($_POST["employee_fname"]);
    $employee_lname = filter_var($_POST["employee_lname"]);
    $employee_email = filter_var($_POST["employee_email"]);
    $employee_id = filter_var($_POST["employee_id"]);
    $employee_address = filter_var($_POST["employee_address"]);
    $employee_city = filter_var($_POST["employee_city"]);
    $employee_state = filter_var($_POST["employee_state"]);
    $employee_zcode = filter_var($_POST["employee_zcode"]);
    $employee_phone = filter_var($_POST["employee_phone"]);
    $employee_ssn = filter_var($_POST["employee_ssn"]);
    $employee_dlnumber = filter_var($_POST["employee_dlnumber"]);
    $employee_dlstate = filter_var($_POST["employee_dlstate"]);
    $employee_dldate = $_POST['employee_dldate'] ? date('Y-m-d', strtotime($_POST['employee_dldate'])) : null;
    $employee_b = $_POST['employee_b'] ? date('Y-m-d', strtotime($_POST['employee_b'])) : null;
    $employee_gender = filter_var($_POST["employee_gender"]);
    $employee_race = filter_var($_POST["employee_race"]);
    $employee_veteran = filter_var($_POST["employee_veteran"]);
    $travel_group = filter_var($_POST["travel_group"]);
    $role = filter_var($_POST['role']);

    $id = filter_var($_POST["eid"]);


    $st = $dbh->prepare("SELECT id_employee FROM employees as a WHERE id_employee=? and travel_group!= ?");
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->bindValue(2, $travel_group, PDO::PARAM_INT);
    $tp = $st->execute();


    if ($tp && $st->rowCount() > 0) {


        $params = [
            'travel_group' => $travel_group,
            'employee_id' => $employee_id,
            'user_id' => $user_id
        ];

        $fields = json_encode($params);


        $API_POST_PATH = $URL . $PATH;
        $mData = curlRequestPost($API_POST_PATH, $fields);
        $rData = json_decode($mData);

        //http_response_code($rData->status);
    }


    $error = '';

    $query = "UPDATE employees SET f_name = ?, l_name = ?,email = ?,employee_id = ?,address = ?,city = ?,state = ?,zip_code = ?,phone = ?,social_number = ?,driver_number = ?,driver_state = ?,driver_expiration = ?,birthdate = ?,gender = ?,race = ?,veteran = ?,travel_group = ?, role = ?  WHERE id_employee = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $employee_fname, PDO::PARAM_STR);
    $stmt->bindValue(2, $employee_lname, PDO::PARAM_STR);
    $stmt->bindValue(3, $employee_email, PDO::PARAM_STR);
    $stmt->bindValue(4, $employee_id, PDO::PARAM_STR);
    $stmt->bindValue(5, $employee_address, PDO::PARAM_STR);
    $stmt->bindValue(6, $employee_city, PDO::PARAM_STR);
    $stmt->bindValue(7, $employee_state, PDO::PARAM_STR);
    $stmt->bindValue(8, $employee_zcode, PDO::PARAM_STR);
    $stmt->bindValue(9, $employee_phone, PDO::PARAM_STR);
    $stmt->bindValue(10, $employee_ssn, PDO::PARAM_STR);
    $stmt->bindValue(11, $employee_dlnumber, PDO::PARAM_STR);
    $stmt->bindValue(12, $employee_dlstate, PDO::PARAM_STR);
    $stmt->bindValue(13, $employee_dldate, PDO::PARAM_STR);
    $stmt->bindValue(14, $employee_b, PDO::PARAM_STR);
    $stmt->bindValue(15, $employee_gender, PDO::PARAM_STR);
    $stmt->bindValue(16, $employee_race, PDO::PARAM_STR);
    $stmt->bindValue(17, $employee_veteran, PDO::PARAM_STR);
    $stmt->bindValue(18, $travel_group, PDO::PARAM_INT);
    $stmt->bindValue(19, $role);

    $stmt->bindValue(20, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();





    if (!$tmp) {
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
            "message" => "Successfully People Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
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
