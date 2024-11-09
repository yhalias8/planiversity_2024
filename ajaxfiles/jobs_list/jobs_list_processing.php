<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}


// DB table to use
$table = 'jobs';

$uid = $userdata['uid'];

// Table's primary key
$primaryKey = 'id_job';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'name', 'dt' => "0"),		
	array('db' => 'category',     'dt' => "1"),
	array('db' => 'address',     'dt' => "2"),			
	array('db' => 'details',     'dt' => "3"),
	array('db' => 'contact_number',     'dt' => "4"),	
	array('db' => 'city', 'dt' => "5"),
	array('db' => 'state', 'dt' => "6"),	
	array('db' => 'zip_code',     'dt' => "7"),
	array('db' => 'id_job',     'dt' => "8")
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
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,null,$extraWhere)
);
