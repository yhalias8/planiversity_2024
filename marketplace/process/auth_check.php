<?php
include_once("../../config.ini.php");

$flag = false;
if ($auth->isLogged()) {
    $flag = true;
}

$returnValue = $flag;

$output = [
    'responseList' => $returnValue,
];

$output = array("data" => $output);

echo json_encode($output);
