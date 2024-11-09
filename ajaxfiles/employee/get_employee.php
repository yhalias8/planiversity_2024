<?php

include '../../config.ini.php';

if ($userdata['id']){

$id=$userdata['id'];

$stmt = $dbh->prepare("SELECT id_employee as emp_id,CONCAT(f_name,' ',l_name) as full_name,email FROM employees WHERE id_user=?");
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$tmp = $stmt->execute();
$aux = '';
$emp=[];
if ($tmp && $stmt->rowCount() > 0) {
$emp = $stmt->fetchAll(PDO::FETCH_OBJ);
}   
echo json_encode($emp);
}