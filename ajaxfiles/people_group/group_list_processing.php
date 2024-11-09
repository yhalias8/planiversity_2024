<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}

// if ($userdata['account_type'] != 'Admin') {
// 	header("Location:" . SITE . "welcome");
// }


// DB table to use
$table = 'travel_groups';

$uid = $userdata['id'];


// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'group_name', 'dt' => "group_name"),
	array('db' => 'description', 'dt' => "description"),
	array('db' => 'user_id',     'dt' => "user_id"),
	array('db' => 'id',     'dt' => "id"),
	array(
		'db' => 'created_at',
		'dt' => 'created_at',
		'formatter' => function ($d, $row) {
			$created_at_date = new DateTime($d);
			$dateFormatted = $created_at_date->format('M j, Y h.iA');
			return $dateFormatted;
		}
	),
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

$extraWhere = "user_id=$uid";


echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere)
);
