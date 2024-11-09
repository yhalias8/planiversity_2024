<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */

include_once("config.ini.php");
include_once ("class/class.UpdateStatus.php");
$updateStatus = new UpdateStatus();

if (!$auth->isLogged()) {
    header("Location:" . SITE . "login");
    die();
}

if (!$updateStatus->hasAccessToTrip($_POST['trip_id'], $userdata['id'])) {
    header("Location:" . SITE . "login");
    die();
}

$result = $updateStatus->store(
    $_POST['trip_id'],
    $userdata['id'],
    $_POST['update_status_text'],
    $_POST['for'],
    $_POST['people'] ?? []
);

echo json_encode(["result" => $result]);