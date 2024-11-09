<?php
include_once("../config.ini.php");
ini_set('display_errors', TRUE);
include("../class/class.TripPlan.php");

$trip = new TripPlan();

if (isset($_POST['MODE']) && $_POST['MODE'] === 'progress' && isset($_POST['id'])) {
    $id = $_POST['id'];
    // echo $id;
    $progressing = $trip->getProgressing($id);
    echo json_encode(['progressing' => $progressing]);
    exit();
}
