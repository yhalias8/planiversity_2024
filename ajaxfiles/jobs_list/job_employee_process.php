<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['ID']) {

    $id = filter_var($_POST["ID"]);

    $stmt = $dbh->prepare("SELECT CONCAT(a.f_name,' ',a.l_name) as full_name FROM employees as a,`employee-job` as b  WHERE b.id_job=$id and a.id_employee=b.id_employee");    
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    echo json_encode($timelines);
}
