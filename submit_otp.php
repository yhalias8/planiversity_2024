<?php

include_once("config.ini.php");
//require __DIR__ . '/vendor/autoload.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_POST['otp'])) {
    $otp = filter_var($_POST["otp"], FILTER_SANITIZE_STRING);
    if (isset($_POST['uid'])) {
        $otpResult = $auth->validateRestorePassOTP($otp, $_POST['uid']);
        $_SESSION['uid'] = $_POST['uid'];
        $_SESSION['otp'] = $otp;
        if (!$otpResult['error']) {
            unset($_SESSION["result"]);
            header("Location:" . SITE . 'reset_password.php');
        } else {
            $_SESSION['message'] = $otpResult['message'];
            header("Location:" . SITE . "password-restore");
        }
    } else {
        $otpResult = $auth->validateOTP($otp, $_SESSION["result"]["uid"], true);
        if ($otpResult['error']) {
            $_SESSION['message'] = $otpResult['message'];
            $otpOutput = $otpResult['message'];
            header("Location:" . SITE . "registration");
        } else {
            unset($_SESSION["result"]);
            header("Location:" . SITE . 'thankyou');
        }
    }
}
if (isset($_POST['reset_password_submit'])) {
    $password = $_POST['reset_password'];
    $confirm_password = $_POST['reset_password2'];
    $result = $auth->resetPass1($_SESSION['uid'], $_SESSION['otp'], $password, $confirm_password);
    //    $output = $result['message'];
    if (!$result['error']) {
        unset($_SESSION['resetPasswordResult']);
        unset($_SESSION['uid']);
        unset($_SESSION['otp']);
        header("Location:" . SITE . 'login');
    } else {
        $_SESSION['resetPasswordResult'] = $result;
        header("Location:" . SITE . 'reset_password.php');
    }
}
if (isset($_POST['resendOTP'])) {
    $uid = "";
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];
    } else if (isset($_SESSION["result"])) {
        $uid = $_SESSION["result"]["uid"];
    }
    $resendResult = $auth->resendOTP($uid);
    $resultEncoded = json_encode($resendResult);
    $user = $auth->getUser($uid);
    $to = $user['email'];
    $subject = 'Planiversity - Here`s your one-time code';
    $message = $resendResult['message'];
    $headers = 'From: plans@planiversity.com'       . "\r\n" .
                 'Reply-To: plans@planiversity.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
    echo $resultEncoded;
}
