<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';



if ($_GET['id']){

$id=$_GET['id'];

$stmt = $dbh->prepare("SELECT * FROM tripit_plans WHERE id_plan=?");
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$tmp = $stmt->execute();
$aux = '';
$timelines=[];
if ($tmp && $stmt->rowCount() > 0) {
$timelines = $stmt->fetch(PDO::FETCH_OBJ);
}   
echo json_encode($timelines);

}