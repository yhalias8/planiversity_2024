<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}


// DB table to use
$table = 'employees';

$uid = $userdata['uid'];


// Table's primary key
$primaryKey = 'id_employee';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
	array('db' => 'photo', 'dt' => "photo"),
	array('db' => 'photo_connect', 'dt' => "photo_connect"),
	array('db' => 'f_name', 'dt' => "f_name"),
	array('db' => 'l_name',     'dt' => "l_name"),
	array('db' => 'email',     'dt' => "email"),
	array('db' => 'employee_id',     'dt' => "employee_id"),
	array('db' => 'phone',     'dt' => "phone"),
	array('db' => 'id_employee',     'dt' => "id_employee")
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

$extraWhere = "id_user=$uid";


echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere)
);
