<?php
include '../../config.ini.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sync_outlookcalendar = $_POST['sync_outlookcalendar'];
    $timezone_offset_minutes = $_POST['timezone_offset_minutes'];
    $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);

    $query = "UPDATE `users` SET `sync_outlookcalendar` = :sync_outlookcalendar, `timezone` = :timezone_name WHERE `id` = :id";
    $stmtnew = $dbh->prepare($query);

    $stmtnew->bindParam(':sync_outlookcalendar', $sync_outlookcalendar);
    $stmtnew->bindParam(':timezone_name', $timezone_name);
    $stmtnew->bindParam(':id', $userdata['id']);

    if ($stmtnew->execute()) {
        echo json_encode(["status" => "success", "message" => "Updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
