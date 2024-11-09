<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_POST['id_trip'])) {

    $id_trip = $_POST['id_trip'];
    $type = "additional";
    $output_dir = "../uploads/";

    $user_id = null;
    if ($auth->isLogged()) {
        $user_id = $userdata['id'];
    }

    // var_dump($_FILES);

    // die();


    if (isset($_FILES["files"]) && !empty($_FILES['files'])) {

        $fileCount = count($_FILES["files"]["name"]);
        for ($i = 0; $i < $fileCount; $i++) {

            $original_name = $_FILES["files"]["name"][$i];
            $file_name = time();
            $fileInfo = pathinfo($original_name);
            $fileExtension = strtolower($fileInfo['extension']);
            $fileName = $id_trip . '_' . $type . '_' . $i . '_' . $file_name . '.' . $fileExtension;

            if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $output_dir . $fileName)) {
                if (!empty($user_id)) // add in documents table
                {
                    $id_doc = $query = '';
                    $query = "INSERT INTO documents (id_user, name, type) VALUES (?, ?, ?)";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
                    $stmt->bindValue(2, $fileName, PDO::PARAM_STR);
                    $stmt->bindValue(3, $type, PDO::PARAM_STR);
                    $stmt->execute();
                    $id_doc = $dbh->lastInsertId();
                    if (!empty($id_trip) && !strstr($id_trip, 'u')) // add in trips-docs table
                    {
                        $query = "INSERT INTO `trips-docs` (`id_trip`, `id_document`) VALUES (?, ?)";
                        $stmt = $dbh->prepare($query);
                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                        $stmt->bindValue(2, $id_doc, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
                $ret[] = $fileName;
            }
        }
    }
}
