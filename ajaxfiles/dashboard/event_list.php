<?php
// Enable error reporting
error_reporting(E_ALL); // Report all types of errors
ini_set('display_errors', 1); // Display errors inline


include_once("../../config.ini.php");
// header('Content-Type: application/json');

$event_list = [];

// Define the badges
$badges = [
    ['itineraryType' => 'trip', 'color' => '#046fcf', 'school' => 1],
    ['itineraryType' => 'event', 'color' => '#f7b94d', 'school' => 2],
    ['itineraryType' => 'job', 'color' => '#34c759', 'school' => 4],
    ['itineraryType' => 'appt', 'color' => '#a259ff', 'school' => 5],
];

// Fetch trip events
foreach ($badges as $b) {
    $trip_events = generateTripEvents($dbh, $userdata, $b['itineraryType'], $b['color'], $b['school']);
    $event_list = array_merge($event_list, $trip_events); // Gabungkan ke dalam satu array
}

// Fetch events
$stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event'");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($timelines as $timeline) {
        $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from)) : "";
        $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to)) . "T23:59:00" : "";

        if ($from && $to) {
            $event_list[] = [
                'title' => $timeline->event_title,
                'start' => $from,
                'end' => $to,
                'color' => '#f7b94d',
                'school' => 3,
            ];
        }
    }
}

// Fetch meetings
$stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting'");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($timelines as $timeline) {
        $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from)) : "";
        $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to)) . "T23:59:00" : "";

        if ($from && $to) {
            $event_list[] = [
                'title' => $timeline->event_title,
                'start' => $from,
                'end' => $to,
                'color' => '#ed272f',
                'school' => 3,
            ];
        }
    }
}

function generateTripEvents($dbh, $userdata, $itineraryType, $color, $school)
{
    $stmt = $dbh->prepare("
        (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)
        UNION ALL
        (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN (SELECT trip_id FROM migration_master WHERE modifier_user_id=? AND status NOT IN ('pending', 'declined')))
        ORDER BY location_datel ASC
    ");
    $stmt->bindValue(1, $itineraryType, PDO::PARAM_STR);
    $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
    $stmt->bindValue(3, $itineraryType, PDO::PARAM_STR);
    $stmt->bindValue(4, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();

    $timelines = array();
    if ($tmp && $stmt->rowCount() > 0) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $result) {
            $employee = '';
            if (!empty($result['id_employee'])) {
                $employee = get_employee($result['id_employee']);
            }

            $triptitle = trim($result['title']);
            $triptitle = preg_replace('/[^a-zA-Z0-9\s]/', '_', $triptitle);
            $triptitle = str_replace(' ', '_', $triptitle);
            $pdfname = $triptitle . '-' . $result['id_trip'];

            $date_start = new DateTime($result['location_datel'] ? $result['location_datel'] : $result['date_created']);
            $date_end = new DateTime($result['location_dater'] ? $result['location_dater'] : $result['date_created']);
            $date_end->modify('+1 day');

            $timelines[] = array(
                'title' => $triptitle,
                'start' => $date_start->format('Y-m-d'),
                'end' => $date_end->format('Y-m-d\TH:i:s'),
                'url' => !empty($pdfname) ? SITE . 'pdf/' . $pdfname . '.pdf' : '',
                'school' => !empty($school) ? $school : '',
                'color' => !empty($color) ? $color : ''
            );
        }
    }

    return $timelines;
}

// Return the JSON response
echo json_encode($event_list);
