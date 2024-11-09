<?php
//include '../../config.ini.php';
//include '../../config.ini.curl.php';


function mailsendUser($to_address, $fname, $status, $price)
{
    // send notification payment email
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->From = $auth->config->site_email;
    $mail->FromName = $auth->config->site_name;
    $mail->addAddress($to_address);
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - Card Payment';
    $mail->Body = 'Hello ' . $fname . ',<br/><br/> Thank you for your payment. <br /><br /> Payment Status: ' . $status . '<br />Payment Amount: $' . $price;
    $mail->send();
}


function mailSend($fname, $lname, $status, $price)
{

    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->From = $auth->config->site_email;
    $mail->FromName = $auth->config->site_name;
    //$mail->addAddress($auth->config->site_email);
    $mail->addAddress('its.kraftbj@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - Stripe Payment';
    $mail->Body = 'Hello,<br/><br/> You received a payment from Planiversity.com. <br /><br /> Payment From: ' . $fname . ' ' . $lname . '<br />Payment Status: ' . $status . '<br />Payment Amount: $' . $price;
    $mail->send();
}
