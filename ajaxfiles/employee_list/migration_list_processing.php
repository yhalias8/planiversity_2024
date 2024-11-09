<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}


if (isset($_POST['uid'])) {

	// DB table to use
	$table = 'migration_master';

	$uid = $_POST['uid'];


	// Table's primary key
	$primaryKey = 'id';

	// Array of database columns which should be read and sent back to DataTables.
	// The `db` parameter represents the column name in the database, while the `dt`
	// parameter represents the DataTables column identifier. In this case simple
	// indexes
	$columns = array(
		array('db' => 'id', 'dt' => "id"),
		array('db' => 'trip_id', 'dt' => "trip_id"),
		array('db' => 'status', 'dt' => "status"),
		//array('db' => 'created_at',     'dt' => "created_at"),
		array(
			'db' => 'created_at',
			'dt' => "created_at",
			'formatter' => function ($d, $row) {
				$created_at_date = new DateTime($d);
				$dateFormatted = $created_at_date->format('M j, Y h.iA');
				return $dateFormatted;
			}
		),
		array(
			'db' => 'updated_at',
			'dt' => "updated_at",
			'formatter' => function ($d, $row) {
				$created_at_date = new DateTime($d);
				$dateFormatted = $created_at_date->format('M j, Y h.iA');
				return $dateFormatted;
			}
		),
		//array('db' => 'updated_at',     'dt' => "updated_at"),
	);

	// SQL server connection information
	$sql_details = array(
		'user' => DATABASE_USER,
		'pass' => DATABASE_PASS,
		'db'   => DATABASE_NAME,
		'host' => DATABASE_HOST
	);


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

	require('../ssp.extended.class.php');

	$extraWhere = "people_id=$uid";


	echo json_encode(
		SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, null, $extraWhere)
	);
}
