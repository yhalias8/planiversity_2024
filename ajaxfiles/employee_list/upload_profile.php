<?php

//upload.php
include('../../config.ini.php');
if (isset($_POST['image'])) {

	$output_dir = "ajaxfiles/people/";
	$return_dir = "../people/";

	$data = $_POST['image'];

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);
	$file_name = uniqid() . '-' . time() . '.png';

	$return_url = SITE . $output_dir . $file_name;

	$image_name = $return_dir . $file_name;
	file_put_contents($image_name, $data);

	$query = "UPDATE employees SET photo = ?,photo_connect= ? WHERE id_employee = ?";
	$stmtnew = $dbh->prepare($query);
	$stmtnew->bindValue(1, $file_name, PDO::PARAM_STR);
	$stmtnew->bindValue(2, 0, PDO::PARAM_INT);
	$stmtnew->bindValue(3, $_POST["useId"], PDO::PARAM_INT);
	$stmtnew->execute();

	echo $return_url;
}
