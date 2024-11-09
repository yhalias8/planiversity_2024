<?php
include '../../config.ini.php';
include '../../config.ini.curl.php';
include("layout/list.php");



if ($_POST['type'] && $_POST['id']) {

    $name = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

    if ($name == 'trip') {
        echo trip($dbh, $userdata['id'], $id, $userdata['account_type']);
    } else if ($name == 'meeting') {
        echo meeting($dbh, $userdata['id'], $id, $userdata['account_type']);
    } else {
        echo event($dbh, $userdata['id'], $id, $userdata['account_type']);
    }
}

