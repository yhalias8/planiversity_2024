<?php
include '../config.ini.php'; 
if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}
  
$query = "UPDATE users SET active = ?, email=? WHERE id = ?";
$stmtnew = $dbh->prepare($query);                  
$stmtnew->bindValue(1, 0, PDO::PARAM_INT);
$stmtnew->bindValue(2, $userdata['email']."--", PDO::PARAM_STR);
$stmtnew->bindValue(3, $userdata['id'], PDO::PARAM_INT);
if($stmtnew->execute()){
    
    $to = $userdata['email'];
$subject = "Planiversity.com - Account deleted";

$message = "
<html>
<head>
<title>Planiversity.com - Account deleted</title>
</head>
<body>
<p>Hello,<br>
Your account with Planiversity has been cancelled, thank you for giving us a try. If you change your mind, please visit us on our website to re-register. 
<br>Happy Travels!</p>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: Planiversity<info@planiversity.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

mail($to,$subject,$message,$headers);
    
    
    echo 'User deleted successfully!';
}else{
    echo "An error was encountered ";
}       

?>