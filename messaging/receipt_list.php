<?php
include_once('../config.ini.php');
include_once('list.php');

$rData = (object) [
    'data' =>
        (object) [
            'data' => []
        ],
];

$sql = <<<SQL
select u.id,u.name,u.customer_number,u.picture
from employees e
inner join users u on u.customer_number=e.employee_id
where e.id_user=:user_id
SQL;

if ($_GET['search']) {
    $sql .= ' and (u.name like :search_name or u.customer_number = :search_customer_number)';
}

global $dbh;

$stmt = $dbh->prepare($sql);

$stmt->bindParam(':user_id', $userdata['id'], PDO::PARAM_INT);
if ($_GET['search']) {
    $stmt->bindParam(':search_customer_number', $_GET['search']);
    $str = "%" . $_GET['search'] . "%";
    $stmt->bindParam(':search_name', $str);
}

$stmt->execute();

$rData->data->data = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!empty($rData->data->data)) {
    $returnValue = $rData->data->data;
    $after = $_GET['page'] + 1;
} else {
    $returnValue = [];
    $after = null;
}

$responseList = processReceiptData($returnValue, $FILE_PATH);


$output = [
    'responseList' => $responseList,
    'next_page' => $after,
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
