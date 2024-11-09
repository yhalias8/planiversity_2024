<?php
if (isset($_GET['acc']) && $_GET['acc'] == 'logout') {
   $auth->logout($auth->getSessionHash());
   if (!$result['error'])
      setcookie($auth->config->cookie_name, null, time() - 3600, "/");
}
$welcomelink = '';
if ($auth->isLogged()) {
   $welcomelink = 'welcome';
}

$output = '';

// if (isset($_POST['login_submit'])) {
//   if (isset($_POST['X-xhgshdg-jhknh'])) {
//       $username = $_POST["login_username"];
//       $remember = isset($_POST['login_remember']) ? $_POST['login_remember'] : 0;

//       $result = $auth->glogin($username);
//   } else {
//       $username = $_POST["login_username"];
//       $password = $_POST["login_password"];
//       $remember = isset($_POST['login_remember']) ? $_POST['login_remember'] : 0;
//       $result = $auth->login($username, $password, $remember);
//   }


//   if ($result['error']) {
//       $output = $result['message'];
//       // put failed_attemps in DB
//       $query = "UPDATE users SET failed_attemps = failed_attemps + 1 WHERE email = ? OR customer_number = ?";
//       $stmt = $dbh->prepare($query);
//       $stmt->bindValue(1, $username, PDO::PARAM_STR);
//       $stmt->bindValue(2, $username, PDO::PARAM_STR);
//       $stmt->execute();
//       // die('cccc');
//       //echo 'error';
//   } else {
//       //  echo 'no error';
//       //setcookie('PlaniversityAuthIDD', $result["hash"], $result["expire"], '/');
//       setcookie($auth->config->cookie_name, $result["hash"], $result["expire"], '/');
//       // put IP and sign_count in DB
//       $uid = $auth->getSessionUID($auth->getSessionHash());
//       //  echo $uid;
//       $userdata = $auth->getUser($uid);
//       $lastlogin = $userdata['date_current_login'];
//       $iplastlogin = $userdata['ip_current_login'];
//       $query = "UPDATE users SET sign_count = sign_count + 1, ip_current_login = ?, date_current_login = ?, date_last_login = ?, ip_last_login = ? WHERE id = ?";
//       $stmt = $dbh->prepare($query);
//       $stmt->bindValue(1, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
//       $stmt->bindValue(2, date('Y-m-d H:i:s'), PDO::PARAM_STR);
//       $stmt->bindValue(3, $lastlogin, PDO::PARAM_STR);
//       $stmt->bindValue(4, $iplastlogin, PDO::PARAM_STR);
//       $stmt->bindValue(5, $userdata['id'], PDO::PARAM_INT);
//       $stmt->execute();
//       if (isset($_SESSION['redirect']) && !empty($_SESSION['redirect'])) {
//          $tmp = $_SESSION['redirect'];
//          $_SESSION['redirect'] = '';
//          header("Location:" . SITE . $tmp);
//       } else {
//          // die('aaaa');
//          header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard"); 
//       }
//   }
// }
