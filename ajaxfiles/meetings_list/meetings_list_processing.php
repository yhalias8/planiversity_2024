<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}


// DB table to use
$table = 'events';

$uid = $userdata['uid'];

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'event_title', 'dt' => "0"),	
	array(
		'db' => 'event_date_from',
		'dt' => "1",
		'formatter' => function ($d, $row) {
        $date_from = $d . " " . $row['event_time_from'];
        $date_from = date('Y-m-d H:i:s A', strtotime($date_from));
		return $date_from;
		}
	),
	array(
		'db' => 'event_date_to',
		'dt' => "2",
		'formatter' => function ($d, $row) {
        $date_to = $d . " " . $row['event_time_to'];
        $date_to = date('Y-m-d H:i:s A', strtotime($date_to));
		return $date_to;
		}
	),		
	array('db' => 'event_location',     'dt' => "3"),
	array('db' => 'customer_name',     'dt' => "4"),			
	array('db' => 'event_overview',     'dt' => "5"),
	array('db' => 'special_instructions',     'dt' => "6"),
	array(
		'db' => 'event_date_from',
		'dt' => "7",
		'formatter' => function ($d, $row) {        
        $date_from = date('Y-m-d', strtotime($d));
		return $date_from;
		}
	),
	array(
		'db' => 'event_date_to',
		'dt' => "8",
		'formatter' => function ($d, $row) {        
        $date_from = date('Y-m-d', strtotime($d));
		return $date_from;
		}
	),		
	array('db' => 'event_time_from', 'dt' => "9"),
	array('db' => 'event_time_to', 'dt' => "10"),	
	array('db' => 'event_invitee',     'dt' => 11),
	array('db' => 'id',     'dt' => "12")
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

$extraWhere = "user_id=$uid AND event_type='meeting'";   


echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,null,$extraWhere)
);
