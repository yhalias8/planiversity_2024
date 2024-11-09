<?php
include '../../config.ini.php';
include_once '../../includes/helpers.php';
include_once '../../class/class.TripPlan.php';

if (isset($_POST['idtrip']) && isset($_POST['location_from']) && isset($_POST['profile_employee'])) {
    $URL = ADMIN_URL;
    $PATH = "migration/connect";

    $user_id = $userdata['uid'];

    $people_id = $_POST['profile_employee'];
    $trip_id = $_POST['idtrip'];


    if (isset($_POST['switch'])) {

        $smt = $dbh->prepare("SELECT id FROM migration_master WHERE trip_id=? AND people_id=? AND status NOT IN('pending','declined')");
        $smt->bindParam(1, $trip_id, PDO::PARAM_INT);
        $smt->bindParam(2, $people_id, PDO::PARAM_INT);
        $smt->execute();
        $nrow = $smt->fetch(PDO::FETCH_OBJ);

        if ($nrow) {
            $response = array(
                'status' => 422,
                'message' => "People migration record exists",
            );
            http_response_code(422);
        } else {

            $stmt = $dbh->prepare('SELECT b.id FROM connect_details as a,connect_master as b WHERE a.connect_id=b.id AND b.id_trip=? AND a.people_id=?');
            $stmt->bindParam(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $people_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $response = array(
                    'status' => 422,
                    'message' => "People is already connected",
                );

                http_response_code(422);
            } else {


                $last_inserted_id = connectMasterProcess($dbh, $_POST['idtrip'], 0);

                $data = [
                    $last_inserted_id,
                    $people_id,
                    $user_id
                ];

                $sql = "INSERT INTO connect_details (connect_id,people_id,user_id) VALUES (?,?,?)";
                $stmt = $dbh->prepare($sql);

                if (!$stmt->execute($data)) {
                    $msg = $stmt->errorInfo()[2];

                    $response = array(
                        'status' => 422,
                        'message' => "A system error has been encountered",
                    );

                    http_response_code(422);
                } else {
                    $params = [
                        'trip_id' => $trip_id,
                        'people_id' => $people_id,
                        'user_id' => $user_id
                    ];
                    ActivityLogger::log($trip_id, ActivityLogger::USER_ADDED);
                    $fields = json_encode($params);

                    $API_POST_PATH = $URL . $PATH;
                    $mData = curlRequestPost($API_POST_PATH, $fields);
                    $rData = json_decode($mData);

                    $response = $rData;

                    http_response_code($response->status);
                }
            }
        }

        echo json_encode($response);
    }
    else {


        $smt_group = $dbh->prepare("SELECT id_employee as people_id FROM employees WHERE travel_group=?");
        $smt_group->bindParam(1, $people_id, PDO::PARAM_INT);
        $smt_group->execute();
        //$groups = $smt->fetchAll(PDO::FETCH_OBJ);

        $groups = [];
        while ($row = $smt_group->fetch(PDO::FETCH_OBJ)) {
            $groups[] = $row->people_id;
        }

        if (!empty($groups)) {
            $inClause = implode(',', $groups);

            $smt = $dbh->prepare("SELECT id FROM migration_master WHERE trip_id=? AND people_id IN ($inClause) AND status NOT IN('pending','declined')");
            $smt->bindParam(1, $trip_id, PDO::PARAM_INT);
            $smt->execute();
            $nrow = $smt->fetch(PDO::FETCH_OBJ);


            if ($nrow) {

                $response = array(
                    'status' => 422,
                    'message' => "People migration record exists",
                );
                http_response_code(422);
            } else {
                $stmt = $dbh->prepare("SELECT b.id FROM connect_details as a,connect_master as b WHERE a.connect_id=b.id AND b.id_trip=? AND people_id IN ($inClause)");

                $stmt->bindParam(1, $trip_id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $response = array(
                        'status' => 422,
                        'message' => "People is already connected",
                    );

                    http_response_code(422);
                } else {

                    $last_inserted_id = connectMasterProcess($dbh, $trip_id, 1, $people_id);

                    foreach ($groups as $key => $value) {
                        connectDetailsProcess($dbh, $last_inserted_id, $value, $user_id);
                    }

                    foreach ($groups as $key => $value) {
                        $params = [
                            'trip_id' => $trip_id,
                            'people_id' => $value,
                            'user_id' => $user_id
                        ];

                        $fields = json_encode($params);
                        ActivityLogger::log($trip_id, ActivityLogger::USER_ADDED);
                        $API_POST_PATH = $URL . $PATH;
                        $mData = curlRequestPost($API_POST_PATH, $fields);
                        $rData = json_decode($mData);

                        $response = $rData;
                    }

                    $response = array(
                        "status" => 200,
                        "message" => "Successfully Connected",
                    );

                    http_response_code(200);

                    echo json_encode($response);
                }
            }
        }
    }

    $sql = 'select * from employees where id_employee=:id_employee';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id_employee', $_POST['profile_employee'], PDO::PARAM_INT);
    $stmt->execute();
    $employee = $stmt->fetchObject();

    $sql = 'select * from trips where id_trip=:id_trip';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id_trip', $_POST['idtrip'], PDO::PARAM_INT);
    $stmt->execute();
    $trip = $stmt->fetchObject();

    sendEmployeeEmail(
        $employee->email,
        $employee->role,
        $employee->f_name,
        $trip->title,
        SITE . '/trip/connect/' . $trip->id_trip
    );

}


function connectMasterProcess($dbh, $trip_id, $is_group, $group_id = NULL)
{

    $query = "INSERT INTO connect_master (id_trip, is_group, group_id) VALUES (?, ?, ?)";
    $stmt = $dbh->prepare($query);

    $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $is_group, PDO::PARAM_INT);
    $stmt->bindValue(3, $group_id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $lastInsertedId = $dbh->lastInsertId();

    return $lastInsertedId;
}


function connectDetailsProcess($dbh, $connect_id, $people_id, $user_id)
{

    $query = "INSERT INTO connect_details (connect_id, people_id, user_id) VALUES (?, ?, ?)";
    $stmt = $dbh->prepare($query);

    $stmt->bindValue(1, $connect_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $people_id, PDO::PARAM_INT);
    $stmt->bindValue(3, $user_id, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if ($tmp) {
        return true;
    } else {
        return false;
    }
}

function sendEmployeeEmail($email, $role, $userName, $projectName, $link)
{
    global $auth;

    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->From = $auth->config->site_email;
    $mail->FromName = $auth->config->site_name;
    $mail->addAddress($email);
    $mail->isHTML(true);
    if ($role == TripPlan::ROLE_COLLABORATOR) {
        $mail->Subject = 'Welcome to Plaiversity! You\'ve Been Added as a Collaborator';
        $tpl = <<<BODY
Hi %%%USER_NAME%%%,<br>
We’re excited to let you know that you’ve been added as a collaborator on the %%%PROJECT_NAME%%% plan at <b>Plaiversity</b>!<br>
As a collaborator, you’ll have access to all the resources and tools necessary to contribute and help drive the success of this initiative. Below are a few details to get you started:<br>
- <b>Project Name:</b> %%%PROJECT_NAME%%%<br>
- <b>Your Role:</b> Collaborator<br>
- <b>Access Details:</b> %%%LINK%%%<br>
Feel free to explore the platform and familiarize yourself with the ongoing work. If you have any questions or need assistance, don’t hesitate to reach out to other admin members on the plan.<br>
Time to get planning!<br>
Best regards,<br>
Team Plaiversity<br>
www.Planiversity.com
BODY;
    } else {
        $tpl = <<<BODY
Hi %%%USER_NAME%%%,<br>
We’re happy to inform you that you’ve been added as a view-only participant on the **[Project Name]** plan at **Plaiversity**!<br>
As a view-only member, you will have access to all the content and updates of the project without the ability to make changes. This allows you to stay informed and monitor progress as needed. Below are your access details:<br>
- <b>Project Name:</b> %%%PROJECT_NAME%%%<br>
- <b>Your Role:</b> View-Only Participant<br>
- <b>Access Details:</b> %%%LINK%%%<br>
If you have any questions or would like further clarification about your role or access, feel free to reach out to the admin members on the plan.<br>
Best regards,<br>
Team Plaiversity<br>
www.Planiversity.com<br>
BODY;
    }

    $mail->Body = str_replace([
        '%%%USER_NAME%%%',
        '%%%PROJECT_NAME%%%',
        '%%%LINK%%%'
    ], [
        $userName,
        $projectName,
        $link
    ], $tpl);

    $mail->send();

}


function curlRequestPost($url, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);


    $headers = array(
        "Content-type: application/json",
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
