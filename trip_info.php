<?php
include_once("config.ini.php");
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
            getComments($dbh, $_GET['trip_id']);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['commentfield']) && $data['commentfield'] != '') {
            addComment($dbh, $data);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

function getTripInfo($dbh, $trip_id)
{
    if (empty($trip_id)) {
        http_response_code(400);
        echo json_encode(['message' => 'trip_id is required']);
        return;
    }

    $stmt = $dbh->prepare("SELECT id_trip, packet_number, id_user, title, itinerary_type, transport FROM TripMaster WHERE id_trip = ?");
    $stmt->execute([$trip_id]);
    $tripInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tripInfo) {
        http_response_code(404);
        echo json_encode(['message' => 'Trip not found']);
        return;
    }

    // Fetch user data
    $userId = $tripInfo['id_user'];
    $userStmt = $dbh->prepare("SELECT id, name, email, COALESCE(picture, null) as photo FROM UserList WHERE id = ?");
    $userStmt->execute([$userId]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user list
    $userListStmt = $dbh->prepare("SELECT users.id, users.name, users.email, users.picture FROM ConnectDetails 
                                    JOIN connect_master ON connect_details.connect_id = connect_master.id 
                                    JOIN employees ON connect_details.people_id = employees.id_employee 
                                    JOIN users ON users.customer_number = employees.employee_id 
                                    WHERE connect_master.id_trip = ?");
    $userListStmt->execute([$trip_id]);
    $userList = $userListStmt->fetchAll(PDO::FETCH_ASSOC);

    $output = [
        "trip_info" => $tripInfo,
        "user_list" => array_merge([$userData], $userList),
        "user_count" => count($userList) + 1 // Including the main user
    ];

    echo json_encode(['data' => $output, 'message' => 'Succeed', 'status' => 200]);
}

function getComments($dbh, $trip_id)
{
    if (empty($trip_id)) {
        http_response_code(400);
        echo json_encode(['message' => 'trip_id is required']);
        return;
    }

    $stmt = $dbh->prepare("SELECT id, comment, user_id, created_at FROM trip_comments WHERE id_trip = ? ORDER BY id ASC");
    $stmt->execute([$trip_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['data' => $comments, 'message' => 'Succeed', 'status' => 200]);
}

function addComment($dbh, $data)
{
    // print_r($data); die;
    if (empty($data['id_trip']) || empty($data['commentfield']) || empty($data['user_id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required']);
        return;
    }

    $stmt = $dbh->prepare("INSERT INTO trip_comments (id_trip, user_id, comment) VALUES (?, ?, ?)");
    $saved = $stmt->execute([$data['id_trip'], $data['user_id'], $data['commentfield']]);

    if ($saved) {
        echo json_encode(['message' => 'Comment added successfully', 'status' => 201]);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to add comment']);
    }
}
