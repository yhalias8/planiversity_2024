<?php
include_once("config.ini.php");
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
$stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event' AND (DATE(NOW()) <= DATE(event_date_from) OR DATE(NOW()) <= DATE(event_date_to))");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($timelines as $timeline) {
        $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from)) : "";
        $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to)) . "T23:59:00" : "";
        
        $date_from = $timeline->event_date_from . " " . $timeline->event_time_from;
        $date_from = date('M d H:i A', strtotime($date_from));

        $date_to = $timeline->event_date_to . " " . $timeline->event_time_to;
        $date_to = date('M d H:i A', strtotime($date_to));

        if ($from && $to) {
            $event_list[] = [
                'id' => $timeline->id,
                'title' => $timeline->event_title,
                'start' => $from,
                'end' => $to,
                'url' => 'javascript:void(0)',
                'color' => '#f7b94d',
                'school' => 3,
                'packet_number' => null,
                'is_button' => false,
                'peoples' => null,
                'location_datel' => null,
                'location_datel_deptime' => null,
                'location_datel_arrtime' => null,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'extendedProps' => [
                    'school' => 3,
                    'tipe' => "Events"
                ],
                'cover_image' => null
            ];
        }
    }
}

// Fetch meetings
$stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting' AND (DATE(NOW()) <= DATE(event_date_from) OR DATE(NOW()) <= DATE(event_date_to))");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($timelines as $timeline) {
        $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from)) : "";
        $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to)) . "T23:59:00" : "";

        
        $date_from = $timeline->event_date_from . " " . $timeline->event_time_from;
        $date_from = date('M d H:i A', strtotime($date_from));

        $date_to = $timeline->event_date_to . " " . $timeline->event_time_to;
        $date_to = date('M d H:i A', strtotime($date_to));

        if ($from && $to) {
            $event_list[] = [
                'id' => $timeline->id,
                'title' => $timeline->event_title,
                'start' => $from,
                'end' => $to,
                'url' => 'javascript:void(0)',
                'color' => '#ed272f',
                'school' => 3,
                'packet_number' => null,
                'is_button' => false,
                'peoples' => null,
                'location_datel' => null,
                'location_datel_deptime' => null,
                'location_datel_arrtime' => null,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'extendedProps' => [
                    'school' => 3,
                    'tipe' => "Meeting"
                ],
                'cover_image' => null
            ];
        }
    }
}

function generateTripEvents($dbh, $userdata, $itineraryType, $color, $school)
{
    // $stmt = $dbh->prepare("
    //     (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)
    //     UNION ALL
    //     (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN (SELECT trip_id FROM migration_master WHERE modifier_user_id=? AND status NOT IN ('pending', 'declined')))
    //     ORDER BY location_datel ASC
    // ");

    $stmt = $dbh->prepare("(SELECT * FROM trips WHERE itinerary_type=? and (DATE(NOW()) <= DATE(location_datel)) AND id_user=?)
    UNION ALL
    (SELECT * FROM trips WHERE itinerary_type=? and (DATE(NOW()) <= DATE(location_datel)) AND id_trip IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))
    union
    (select t.* from trips t
    inner join connect_master cm on t.id_trip=cm.id_trip 
    inner join connect_details cd on cm.id=cd.connect_id
    inner join employees e on e.id_employee=cd.people_id 
    inner join users u on e.employee_id=u.customer_number
    where itinerary_type=? and u.id=? and (DATE(NOW()) <= DATE(location_datel)))
    ORDER BY `location_datel` ASC");


    $stmt->bindValue(1, $itineraryType, PDO::PARAM_STR);
    $stmt->bindValue(2, $userdata['id'], PDO::PARAM_STR);
    $stmt->bindValue(3, $itineraryType, PDO::PARAM_STR);
    $stmt->bindValue(4, $userdata['id'], PDO::PARAM_STR);
    $stmt->bindValue(5, $itineraryType, PDO::PARAM_STR);
    $stmt->bindValue(6, $userdata['id'], PDO::PARAM_STR);

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

            $stmt = $dbh->prepare("SELECT a.id, c.role, a.people_id, c.f_name AS first_name, c.l_name AS last_name, c.photo, c.photo_connect, c.email,b.is_group,d.group_name
            FROM connect_details AS a
            INNER JOIN connect_master AS b ON a.connect_id = b.id
            INNER JOIN employees AS c ON a.people_id = c.id_employee
            LEFT JOIN travel_groups as d ON b.group_id = d.id
            WHERE b.id_trip = ?");
            $stmt->bindValue(1, $result['id_trip'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            $peoples = [];
            if ($tmp && $stmt->rowCount() > 0) {
                $peoples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $full_path = null;

            if ($result['cover_image']) {
                $url = $result['cover_image_url'];
                $parmas = $result['cover_image_type'] ? null : "&q=80&w=400";
                $full_path = $url . $parmas;
            }

            $timelines[] = array(
                'id' => $result['id_trip'],
                'title' => $result['title'],
                'start' => $date_start->format('Y-m-d'),
                'end' => $date_end->format('Y-m-d\TH:i:s'),
                // 'url' => !empty($pdfname) ? SITE . 'pdf/' . $pdfname . '.pdf' : '',
                'url' => 'javascript:void(0)',
                'school' => !empty($school) ? $school : '',
                'color' => !empty($color) ? $color : '',
                'packet_number' => $result['packet_number'],
                'is_button' => true,
                'peoples' => $peoples,
                'location_datel' => $result['location_datel'],
                'location_datel_deptime' => $result['location_datel_deptime'],
                'location_datel_arrtime' => $result['location_datel_arrtime'],
                'date_from' => null,
                'date_to' => null,
                'extendedProps' => [
                    'school' => !empty($school) ? $school : '',
                    'tipe' => ucfirst($itineraryType)
                ],
                'cover_image' => $full_path
            );
        }
    }

    return $timelines;
}

// Return the JSON response
return $event_list;
