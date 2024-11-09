<?php
//include '../../config.ini.php';
//include '../../config.ini.curl.php';


function mailsendUser($auth, $to_address, $fname, $status, $price, $type)
{
    // send notification payment email
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "planiversity.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "plans@planiversity.com";
    $mail->Password = "xhj4@ma2sHwG";
    $mail->setFrom($auth->config->site_email, $auth->config->site_name);
    $mail->addAddress($to_address);
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - ' . $type;
    $mail->Body = 'Hello ' . $fname . ',<br/><br/> Thank you for your payment. <br /><br /> Payment Status: ' . $status . '<br />Payment Amount: $' . $price;
    $mail->send();
}


function mailSend($auth, $fname, $lname, $status, $price, $type)
{

    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "planiversity.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "plans@planiversity.com";
    $mail->Password = "xhj4@ma2sHwG";
    $mail->setFrom($auth->config->site_email, $auth->config->site_name);
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - ' . $type;
    $mail->Body = 'Hello,<br/><br/> You received a payment from Planiversity.com. <br /><br /> Payment From: ' . $fname . ' ' . $lname . '<br />Payment Status: ' . $status . '<br />Payment Amount: $' . $price;
    $mail->send();
}
