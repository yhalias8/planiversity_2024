<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['list']) {

    $list = filter_var($_POST["list"]);

    $stmt = $dbh->prepare("SELECT CONCAT(f_name,' ',l_name) as full_name FROM employees WHERE id_employee IN ($list)");    
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    echo json_encode($timelines);
}
