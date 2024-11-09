<?php
include '../../config.ini.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trip = $_POST['id_trip'];

    ActivityLogger::log($id_trip, ActivityLogger::PLAN_DELETED);

    $query = "DELETE FROM `trips` WHERE `trips`.`id_trip` in (" . $id_trip . ")";
    $stmt = $dbh->prepare($query);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
