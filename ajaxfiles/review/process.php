<?php
include '../../config.ini.php';
include '../../config.ini.curl.php';
include("layout/list.php");


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['process_no'] && $_POST['type']) {

    $process_no = filter_var($_POST["process_no"], FILTER_SANITIZE_STRING);

    $html = '';
    $html .= documents($dbh, $userdata['id'], $process_no);
    $html .= timeline($dbh, $userdata['id'], $process_no);
    $html .= notes($dbh, $userdata['id'], $process_no);

    $output = [
        'review_list' => $html,
    ];

    $output = array("data" => $output);

    echo json_encode($output);
}
