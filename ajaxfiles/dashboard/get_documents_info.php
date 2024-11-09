<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['id_trip'])) {

    $id_trip = $_GET['id_trip'];

    $stmt = $dbh->prepare("SELECT dc.*, u.name as user_name
    FROM documents AS dc
    LEFT JOIN users AS u ON dc.id_user = u.id
    LEFT JOIN `trips-docs` AS td ON dc.id_document = td.id_document WHERE td.id_trip=? ORDER BY dc.id_document DESC");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    $data = array();

    foreach ($timelines as $timeline) {
        $created_at_date = new DateTime($timeline->created_at);
        $dateFormatted = $created_at_date->format('F j, Y h.iA');

        $data[] = array(
            "checked_in_date" => $dateFormatted,
            "document_name" => $timeline->name,
            "type" => $timeline->type,
            "user_name" => $timeline->user_name,
            "class" => getFileIconClass($timeline->name),
        );
    }


    $jsonObject = json_encode($data);

    echo ($jsonObject);
}


function getFileIconClass($filename)
{
    $fileInfo = pathinfo($filename);
    $fileExtension = strtolower($fileInfo['extension']);

    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'fa-file-image-o';
        case 'pdf':
            return 'fa-file-pdf-o';
        case 'ppt':
        case 'pptx':
            return 'fa-file-powerpoint-o';
        default:
            return 'fa-file-text-o';
    }
}
