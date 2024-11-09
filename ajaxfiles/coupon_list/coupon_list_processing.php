<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}


// DB table to use
$table = 'coupon';


// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'title', 'dt' => 0),
	array('db' => 'coupon_code',  'dt' => 1),
	array('db' => 'percent',   'dt' => 2),
	array('db' => 'start_date',     'dt' => 3),
	array('db' => 'end_date',     'dt' => 4),
	array('db' => 'status',     'dt' => 5),
	array(
		'db' => 'lifetime',
		'dt' => 6,
		'formatter' => function ($d, $row) {
			$result = ($d == 1) ? 'Yes' : 'No';
			return $result;
		}
	),
	array(
		'db' => 'bulk_coupon',
		'dt' => 7,
		'formatter' => function ($d, $row) {
			$result = ($d == 1) ? 'Yes' : 'No';
			return $result;
		}
	),
	array('db' => 'target_auth_level',     'dt' => 8),
	array('db' => 'lifetime',     'dt' => 9),
	array('db' => 'bulk_coupon',     'dt' => 10),
	array('db' => 'coupon_prefix',     'dt' => 11),
	array('db' => 'coupon_postfix',     'dt' => 12),
	array('db' => 'id',     'dt' => 13),
	array('db' => 'target_plan_level',     'dt' => 14)
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

require('ssp.class.php');


echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
