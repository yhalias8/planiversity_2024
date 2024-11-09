<?php
include("./list_process.php");

$output_dir = "uploads/";
if (isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name'])) {

    $trip_generated = $_POST["trip_generated"];
    $trip_u_id = $_POST["trip_u_id"];
    $trip_title = $_POST["trip_title"];
    $type = $_POST["type"];

    $fileName = $_POST['name'];
    $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files
    $filePath = $output_dir . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);

        // del document in DB
        include('../config.ini.php');

        global $dbh;
        $query = <<<SQL
        select td.id_trip 
        from documents d 
            inner join `trips-docs` td on td.id_document=d.id_document 
        where d.`name` = :name;
SQL;

        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":name", str_replace("..", ".", $fileName));
        $stmt->execute();
        $tripId = $stmt->fetchColumn();

        $query = "DELETE FROM documents WHERE name = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, str_replace("..", ".", $fileName), PDO::PARAM_STR);
        $stmt->execute();
    }

    if ($trip_generated == 1) {
        ActivityLogger::log($tripId, ActivityLogger::DOCUMENT_DELETED);

        $data = [
            "UserId" => $trip_u_id,
            "NotificationTitle" => "Document Deleted",
            "NotificationBody" => notificationBodyProcess("delete", "$type document", $trip_title)
        ];
        $fields = json_encode($data);

        $mData = curlRequestPost($API_URL, $TOKEN, $fields);

    }

    echo "Deleted File " . $fileName . "<br>";
}

?>