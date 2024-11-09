<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['eid'])) {

    $eid = $_GET['eid'];

    $stmt = $dbh->prepare("
    (SELECT * FROM trips WHERE pdf_generated=1 and (DATE(NOW()) >= DATE(location_datel) OR DATE(NOW()) >= DATE(location_datel_arr)) AND id_user=?)
    UNION ALL
    (SELECT * FROM trips WHERE pdf_generated=1 and (DATE(NOW()) >= DATE(location_datel) OR DATE(NOW()) >= DATE(location_datel_arr)) AND id_trip 
    IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))
    ORDER BY `location_datel` ASC
    ");

    $stmt->bindValue(1, $eid, PDO::PARAM_INT);
    $stmt->bindValue(2, $eid, PDO::PARAM_INT);

   
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    $data = array();

    foreach ($timelines as $timeline) {
        $dateFormatted = "No Date Available";
        if ($timeline->location_datel) {
            $trip_date = new DateTime($timeline->location_datel);
            $dateFormatted = $trip_date->format('M j, Y h.iA');
        }

        $data[] = array(
            "trip_date" => $dateFormatted,
            "trip_name" => $timeline->title,
        );
    }

    $jsonObject = json_encode($data);

    echo ($jsonObject);
}
