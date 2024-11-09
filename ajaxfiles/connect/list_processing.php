<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}

if ($_GET['id_trip']) {

	$id_trip = $_GET['id_trip'];

	$stmt = $dbh->prepare("SELECT a.id, c.role, a.people_id, c.f_name AS first_name, c.l_name AS last_name, c.photo, c.photo_connect, c.email,b.is_group,d.group_name
	FROM connect_details AS a
	INNER JOIN connect_master AS b ON a.connect_id = b.id
	INNER JOIN employees AS c ON a.people_id = c.id_employee
	LEFT JOIN travel_groups as d ON b.group_id = d.id
	WHERE b.id_trip = ?");
	$stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
	$tmp = $stmt->execute();
	$aux = '';
	$peoples = [];
	if ($tmp && $stmt->rowCount() > 0) {
		$peoples = $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	echo json_encode($peoples);
}
