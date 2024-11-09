<?php
include '../../config.ini.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scale = $_POST['scale'];

    $query = "UPDATE `users` SET `scale` = :scale WHERE `id` = :id";
    $stmtnew = $dbh->prepare($query);

    $stmtnew->bindParam(':scale', $scale);
    $stmtnew->bindParam(':id', $userdata['id']);

    if ($stmtnew->execute()) {
        echo json_encode(["status" => "success", "message" => "Updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
