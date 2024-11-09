<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
//include("../../class/class.Timeline.php");



if ($_GET['id_trip']){

$id_trip=$_GET['id_trip'];

$stmt = $dbh->prepare("SELECT * FROM timeline LEFT JOIN tripit_plans ON timeline.id_timeline = tripit_plans.schedule_id  WHERE id_trip=?");
$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
$tmp = $stmt->execute();
$aux = '';
$timelines=[];
if ($tmp && $stmt->rowCount() > 0) {
	$timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
	foreach ($timelines as $key => $timeline) {
		if (!$timelines[$key]->document) continue;
		$timelines[$key]->document = SITE . 'ajaxfiles/uploads/' . $timelines[$key]->document;
	}
}

echo json_encode($timelines);
}