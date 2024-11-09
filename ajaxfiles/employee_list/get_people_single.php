<?php

include '../../config.ini.php';


if ($_GET['people_uid']) {

    $id = $_GET['people_uid'];

    $stmt = $dbh->prepare("SELECT * FROM employees WHERE id_employee=?");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $people = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $people = $stmt->fetch(PDO::FETCH_OBJ);
    }
    echo json_encode($people);
}
