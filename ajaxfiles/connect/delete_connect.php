<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

    $error = '';
    $another = '';

    $smt = $dbh->prepare('SELECT b.id_trip,a.people_id,a.connect_id FROM connect_details as a,connect_master as b WHERE a.connect_id=b.id AND a.id=?');
    $smt->bindParam(1, $id, PDO::PARAM_INT);
    $smt->execute();
    $nrow = $smt->fetch(PDO::FETCH_OBJ);

    $tripId = $nrow->id_trip;

    $query = "UPDATE migration_master SET status=? WHERE trip_id=? AND people_id=?";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->bindValue(1, 'declined', PDO::PARAM_STR);
    $stmtnew->bindValue(2, $nrow->id_trip, PDO::PARAM_INT);
    $stmtnew->bindValue(3, $nrow->people_id, PDO::PARAM_INT);
    $save = $stmtnew->execute();

        if (!$save) {
            $another = 'error_fail';
        }

        if ($another) {

            http_response_code(422);
            $response = array(
                'status' => 422,
                'message' => "Connect Deleted Failed",
            );
        } else {
            
                    

        // Delete from "details" table based on ID
        $detailsQuery = "DELETE FROM connect_details WHERE id = ?";
        $detailsStmt = $dbh->prepare($detailsQuery);
        $detailsStmt->bindValue(1, $id, PDO::PARAM_INT);
        $check = $detailsStmt->execute();

        // Delete from "master" table based on ID

        if ($check) {
            $master_check = $dbh->prepare("SELECT id FROM connect_details WHERE connect_id = ?");
            $master_check->bindValue(1, $nrow->connect_id, PDO::PARAM_INT);
            $master_tmp = $master_check->execute();

            if ($master_check->rowCount() == 0) {

                $masterQuery = "DELETE FROM connect_master WHERE id = ?";
                $masterStmt = $dbh->prepare($masterQuery);
                $masterStmt->bindValue(1, $nrow->connect_id, PDO::PARAM_INT);
                $masterStmt->execute();

            }
        }

            ActivityLogger::log($tripId, ActivityLogger::USER_DELETED);
            $response = array(
                "status" => 200,
                "message" => "Successfully Connect Deleted",
            );

            http_response_code(200);
        }
    

    echo json_encode($response);
}
