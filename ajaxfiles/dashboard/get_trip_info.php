<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['id_trip'])) {

    $id_trip = $_GET['id_trip'];

    $stmt = $dbh->prepare("SELECT id_trip,packet_number, title,itinerary_type,transport FROM trips WHERE id_trip=?");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetch(PDO::FETCH_OBJ);
    }

    $jsonObject = json_encode($timelines);

    echo ($jsonObject);
}
