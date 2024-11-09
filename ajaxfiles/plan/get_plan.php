<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
//include("../../class/class.Timeline.php");


if ($_GET['id_trip']){

$id_trip=$_GET['id_trip'];

$stmt = $dbh->prepare("SELECT * FROM tripit_plans WHERE trip_id=? and schedule_flag=?");
$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
$stmt->bindValue(2, 0, PDO::PARAM_INT);
$tmp = $stmt->execute();
$aux = '';
$timelines=[];
if ($tmp && $stmt->rowCount() > 0) {
$timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
}   
echo json_encode($timelines);

}