<?php
include_once("../../config.ini.php");



if (isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST["email"];
    $password = $_POST["password"];
    $remember = 1;
    $result = $auth->login($username, $password, $remember, true);
    if ($result['error']) {
        $output = "Error Occured";
        $status = false;
        $error = $result['message'];
        // put failed_attemps in DB
        $query = "UPDATE users SET failed_attemps = failed_attemps + 1 WHERE email = ? OR customer_number = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $username, PDO::PARAM_STR);
        $stmt->execute();

        http_response_code(422);
    } else {
        setcookie($auth->config->cookie_name, $result["hash"], $result["expire"], '/');
        // put IP and sign_count in DB
        $uid = $auth->getSessionUID($auth->getSessionHash());
        $userdata = $auth->getUser($uid);
        $lastlogin = $userdata['date_current_login'];
        $iplastlogin = $userdata['ip_current_login'];
        $query = "UPDATE users SET sign_count = sign_count + 1, ip_current_login = ?, date_current_login = ?, date_last_login = ?, ip_last_login = ? WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindValue(2, date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(3, $lastlogin, PDO::PARAM_STR);
        $stmt->bindValue(4, $iplastlogin, PDO::PARAM_STR);
        $stmt->bindValue(5, $userdata['id'], PDO::PARAM_INT);
        $stmt->execute();
        $output = "Login successfully";
        $status = true;
        $error = null;
        http_response_code(200);
    }

    $output = [
        'message' => $output,
        'success' => $status,
        'error' => $error,
    ];

    $output = array("data" => $output);

    echo json_encode($output);
}
