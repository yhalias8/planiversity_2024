<?php
include '../../config.ini.php';

if (isset($_POST['title']) && isset($_POST['event_time_from']) && isset($_POST['event_time_to'])) {
   

    $invitee_list = null;
    $notification_agree = 0;
    
    $employee_docname = 0;

    if (isset($_POST['invitee_id'])) {
        $invitee_list = implode(",", $_POST['invitee_id']);
    }

    if (isset($_POST['notification'])) {
        $notification_agree = $_POST['notification'];
    }

    if (isset($_POST['upload_item'])) {
        $employee_docname = $_POST['upload_item'];
    }

    $user_id= $userdata['uid'];

    $event_date_row = $_POST['event_date'] ? date('Y-m-d', strtotime($_POST['event_date'])) : null;

    $data = [
        $user_id,
        $_POST['customer_name'],
        $_POST['location'],
        $_POST['title'],
        $_POST['event_time_from'],
        $_POST['event_time_to'],
        $_POST['overview'],
        $_POST['instructions'],
        $event_date_row,        
        $event_date_row,
        $event_date_row,
        'meeting',
        $invitee_list
    ];

    $sql = "INSERT INTO events (user_id,customer_name, event_location,event_title, event_time_from, event_time_to, event_overview, special_instructions, event_date, event_date_from , event_date_to , event_type,event_invitee)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $dbh->prepare($sql);
    if (!$stmt->execute($data)) {
        $msg = $stmt->errorInfo()[2];

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered",
        );

        http_response_code(422);
    } else {
        $lastID = $dbh->lastInsertId();
        // $sql = "UPDATE events SET doc='" . $_POST['job_docname1'] . "' WHERE id='" . $lastID . "'";

        // $stmt = $dbh->query($sql);

        if($employee_docname){

           $employee_docname = explode(",", $employee_docname);
           
           foreach($employee_docname as $row) {
            $mData[]= 
            array(
                "user_id"=> $user_id,
                "event_id"=> $lastID,
                "doc_name"=> $row,
                "type" => 'meeting'
            );
           }                     
           
           $sql = 'INSERT INTO event_doc(user_id, event_id, doc_name, type) VALUES(:user_id, :event_id, :doc_name, :type )';
           
           $statement = $dbh->prepare($sql);
           
           foreach($mData as $row) {
               $statement->execute($row); 
           }

        }
        
        $response = array(
            "status" => 200,
            "message" => "Successfully Meeting Added",
        );

        http_response_code(200);
    }


    echo json_encode($response);  

    if ($_POST['event_date'] && $_POST['event_time_from'] && $_POST['event_time_to']) {

        $stmh = $dbh->prepare("SELECT sync_googlecalendar FROM users WHERE id=?");
        $stmh->bindValue(1, $userdata['id'], PDO::PARAM_INT);
        $tmp = $stmh->execute();
        $google_object = [];
        $google_object = $stmh->fetch(PDO::FETCH_OBJ);


        $event_date_from = $_POST['event_date'];
        $event_date_to = $_POST['event_date'];

        $event_time_from = $_POST['event_time_from'];
        $event_time_to = $_POST['event_time_to'];

        $date_from = $event_date_from . " " . $event_time_from;
        $date_from = date('Y/m/d H:i:s', strtotime($date_from));

        $startdate = explode(' ', $date_from);

        $date_calculation = $event_date_to ? $event_date_to : $event_date_from;

        $date_to = $date_calculation . " " . $event_time_to;
        $date_to = date('Y/m/d H:i:s', strtotime($date_to));

        $title = $_POST['title'];
        $address = $_POST['location'];
        $description = 'Meeting (from: ' . $date_from . ' - to: ' . $date_to . ') at ' . $address;

        $enddate = explode(' ', $date_to);

        $start_time = str_replace('/', '', $startdate[0]) . 'T' . str_replace(':', '', $startdate[1]);
        $end_time = str_replace('/', '', $enddate[0]) . 'T' . str_replace(':', '', $enddate[1]);


        $event_text = '<html>		
        <body>
            <p>
                Meeting Update
            </p>
                <p>                					
                Meeting Title: ' . $title . '<br/>
                Star Date: ' . $date_from . '<br/>
                End Date: ' . $date_to . '<br/>
                <br/>
                <a style="padding: 15px 38px;font-size: 17px;line-height:30px;border: 2px solid #f6a027;color: #fff;outline: none;background: #f6a027;cursor: pointer;text-indent:0;text-align:center;text-transform:uppercase;text-decoration: none;"
                 href="http://www.google.com/calendar/event?action=TEMPLATE&dates=' . $start_time . '%2F' . $end_time . '&ctz=' . urlencode($userdata['timezone']) . '&text=' . urlencode($title) . '&location=&details=">Add meeting to calendar</a>
                </p>
                ';

        $event_text .= '</body>
                </html>';

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';

        if ($google_object->sync_googlecalendar) {

            $mail->From = $auth->config->site_email;
            $mail->FromName = $auth->config->site_name;
            $mail->addAddress($userdata['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Planiversity.com - Meeting';
            $mail->Body = $event_text;
            $mail->send();
        }

        if ($notification_agree && !empty($invitee_list)) {

            foreach ($_POST['invitee_email'] as $item) {

                $mail->CharSet = 'UTF-8';
                $mail->From = $auth->config->site_email;
                $mail->FromName = $auth->config->site_name;
                $mail->addAddress($item);
                $mail->isHTML(true);
                $mail->Subject = 'Planiversity.com - Meeting';
                $mail->Body = $event_text;
                $mail->send();
            }
        }
    }
}
