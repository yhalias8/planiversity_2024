<?php

function registrationEmailSend($auth, $to_address, $fname)
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
    $mail->Password = "Oq7dQVMIda9-";
    $mail->setFrom($auth->config->site_email, $auth->config->site_name);
    $mail->addAddress($to_address);
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - Registration Information' ;
    $mail->Body = 'Hello ' . $fname . ',<br/><br/> Thank you for registering with Planiversity. <br/><br/>';
    $mail->send();
}