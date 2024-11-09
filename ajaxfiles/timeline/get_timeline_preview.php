<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if ($_GET['id_trip']) {

    $id_trip = $_GET['id_trip'];

    $stmt = $dbh->prepare("SELECT timeline.date, timeline.title,tripit_plans.plan_address
    FROM timeline
    LEFT JOIN tripit_plans ON timeline.id_timeline = tripit_plans.schedule_id WHERE id_trip=? ORDER BY date
    ");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    $data = array();

    foreach ($timelines as $timeline) {
        $date_top = $timeline->date;
        $title = $timeline->title;
        $address = $timeline->plan_address;

        $datetime = new DateTime($timeline->date);

        // Format date in "NOVEMBER 27, 2023" format
        $dateFormatted = $datetime->format('F j, Y');

        // Format time in "03.20pm" format
        $timeFormatted = $datetime->format('h.iA');

        // Check if the date is already in the $data array
        $index = array_search($dateFormatted, array_column($data, 'date_top'));
        if ($index !== false) {
            // Add the event to the existing date in the $data array
            $data[$index]['list'][] = array(
                "date" => $dateFormatted,
                "time" => $timeFormatted,
                "title" => $title,
                "address" => $address,
            );
        } else {
            // Create a new date entry in the $data array
            $data[] = array(
                "date_top" => $dateFormatted,
                "list" => array(
                    array(
                        "date" => $dateFormatted,
                        "time" => $timeFormatted,
                        "title" => $title,
                        "address" => $address,
                    )
                )
            );
        }
    }

    // Convert the $data array to a JSON string
    $jsonObject = json_encode($data, JSON_PRETTY_PRINT);

    echo ($jsonObject);
}
