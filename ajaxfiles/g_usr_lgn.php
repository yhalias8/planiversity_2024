<?php

  include '../config.ini.php';

function generate_customerid(){
    for($i = 0; $i < 10; $i++){
        $decstr = substr(md5(uniqid(rand(), true)), 10, 10);
        return $decstr;
    }
}

  $error = '';
  if (!empty($_POST['idtoken']) && !empty($_POST['email'])) // add in trips-docs table
    {
        $username = $_POST["email"];
        
        $result = $auth->glogin($username);
        print_r($result);
        exit;
        if ($result['error']) {
            $output = $result['message'];
            // put failed_attemps in DB
            $query = "UPDATE users SET failed_attemps = failed_attemps + 1 WHERE email = ? OR customer_number = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $username, PDO::PARAM_STR);
            $stmt->bindValue(2, $username, PDO::PARAM_STR);        
            $stmt->execute();
            
             $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
             $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
             $password = filter_var('test544&^^&_-.76767TT65h', FILTER_SANITIZE_STRING);
             $passwordconform = filter_var('test544&^^&_-.76767TT65h', FILTER_SANITIZE_STRING);
             $clientID = generate_customerid();
            
            $params = array("name" => $name, "account_type" => 'Individual', "customer_number" => $clientID);
            //$result = $auth->register($email, $password, $passwordconform, $params, null, $sendmail = false);
            $result = $auth->register($email, $password, $passwordconform, $params, '', $sendmail = true, 'external');
            if ($result['error']) {
                echo json_encode(array('false',$result['message']));
            } else {
                // send clientID number by email
               /*     $mail = new PHPMailer;
                    $mail->CharSet = 'UTF-8';
                    $mail->From = $auth->config->site_email;
                    $mail->FromName = $auth->config->site_name;
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Planiversity.com - Account created';
                    $mail->Body = 'Hello,<br/><br/> Your clientID is <b>'.$clientID.'</b>, you can use this number to login here <a href="'.SITE.'">Planiversity.com</a>';
                    $mail->send();*/
                // send clientID number by email
                //$output = $result['message'];
                //$output .= ' Please check your email to activate your account.';
                $name = '';
                $email = '';
                echo json_encode(array('true','User registered successfully! Please check your email to activate the account!'));
            }
           //}
            
           // die('cccc');
            //echo 'error';
            //echo 'false';
            
        } else {
           //  echo 'no error';
            //setcookie('PlaniversityAuthIDD', $result["hash"], $result["expire"], '/');
            setcookie($auth->config->cookie_name, $result["hash"], $result["expire"], '/');
            // put IP and sign_count in DB
            $uid = $auth->getSessionUID($auth->getSessionHash());
          //  echo $uid;
            $userdata = $auth->getUser($uid);
            $lastlogin = $userdata['date_current_login'];
            $iplastlogin = $userdata['ip_current_login'];
            $query = "UPDATE users SET sign_count = sign_count + 1, ip_current_login = ?, date_current_login = ?, date_last_login = ?, ip_last_login = ? WHERE id = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->bindValue(2, date('Y-m-d H:i:s'), PDO::PARAM_STR);        
            $stmt->bindValue(3, $lastlogin, PDO::PARAM_STR);
            $stmt->bindValue(4, $iplastlogin, PDO::PARAM_STR);
            $stmt->bindValue(5, $userdata['id'], PDO::PARAM_INT);
            $stmt->execute();
            //echo 'true';
            echo json_encode(array('true','Log In successfull!'));
        }
        
        
        
       /* $query = "INSERT INTO `trips-docs` (`id_trip`, `id_document`) VALUES (?, ?)";
        $stmt = $dbh->prepare($query);                  
        $stmt->bindValue(1, $_POST['tp'], PDO::PARAM_INT);
        $stmt->bindValue(2, $_POST['dt'], PDO::PARAM_INT);
        if (!$stmt->execute())
            $error = 'A system error has been encountered. Please try again.';
        }*/
  
  //$res['error'] = $error;
  //echo json_encode($res);
    }

?>