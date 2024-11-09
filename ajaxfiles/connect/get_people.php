<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}

if ($_GET['user_id']) {
	$userId = $_GET['user_id'];

	if ($_GET['group_id']) {
		$groupId = $_GET['group_id'];
		$emp = $dbh->prepare("SELECT a.id_employee as id,
		CONCAT(a.f_name,' ',a.l_name) as name,
		a.photo,
		a.photo_connect,
		a.email
		FROM employees as a, users as b
		WHERE a.employee_id = b.customer_number
		AND a.id_user = ?
		AND a.travel_group = ?
		ORDER BY a.f_name"
		);
		$emp->bindValue(1, $userId, PDO::PARAM_INT);
		$emp->bindValue(2, $groupId, PDO::PARAM_INT);
	} else if ($_GET['people_id']) {
		$peopleId = $_GET['people_id'];

		$emp = $dbh->prepare("SELECT a.id_employee as id,
                               CONCAT(a.f_name,' ',a.l_name) as name,
                               a.photo,
                               a.photo_connect,
                               b.email
                      FROM employees as a, users as b
                      WHERE a.id_employee = ?
                      AND a.employee_id = b.customer_number
                      AND a.id_user = ?
                      ORDER BY a.f_name"
		);
		$emp->bindValue(1, $peopleId, PDO::PARAM_INT);
		$emp->bindValue(2, $userId, PDO::PARAM_INT);
	} else {
		http_response_code(404);
		echo json_encode(['message' => 'Not found.']);
		exit();
	}

	$emp_tmp = $emp->execute();
	$employees = array();

	if ($emp_tmp && $emp->rowCount() > 0) {
		$employees = $emp->fetchAll(PDO::FETCH_OBJ);
		echo json_encode($employees);
	}
}
