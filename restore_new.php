<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("include_new_header.php");
$output = '';
$mobile = "";
$uid = "";
$OTPoutput = "";
$is_email = 0;
?>
<style>
    a:active {
        color: #004eff63;
    }

    .pr-mobile_no::-webkit-input-placeholder {
        text-align: center;
    }

    .pr-mobile_no:-moz-placeholder {
        /* Firefox 18- */
        text-align: center;
    }

    .pr-mobile_no::-moz-placeholder {
        /* Firefox 19+ */
        text-align: center;
    }

    .pr-mobile_no:-ms-input-placeholder {
        text-align: center;
    }
</style>
<script>
    function resendOTP(uid, event) {
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo SITE; ?>/submit_otp.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                theResp = JSON.parse(this.responseText);

            }
        };
        xhr.send('resendOTP=resendOTP&uid=' + uid);
    }
</script>
<?php
if ($auth->isLogged()) {
    header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard");
}
include('include_doctype.php');

if (isset($_POST['restore_email'])) {
    $email = filter_var($_POST["restore_email"], FILTER_SANITIZE_STRING);
    $result = $auth->requestReset($email, true);
    $output = $result['message'];
}
if (isset($_POST['mobile_no'])) {
    $mobile_no = filter_var($_POST["mobile_no"], FILTER_SANITIZE_STRING);
    $otpResult = $auth->requestOTP($mobile_no);
    if ($otpResult['error']) {
        $output = $otpResult['message'];
    } else {
        $uid = $otpResult['uid'];
        $_SESSION['uid'] = $uid;
        $_SESSION['mobile'] = $otpResult['mobile'];
        $_SESSION['is_email'] = $otpResult['is_email'];
        $mobile = $_SESSION['mobile'];
         $resendResult = $auth->resendOTP($uid);
    $user = $auth->getUser($uid);
    $to = $user['email'];
    $subject = 'Planiversity - Here`s your one-time code';
    $message = $resendResult['message'];
    $headers = 'From: plans@planiversity.com'       . "\r\n" .
                 'Reply-To: plans@planiversity.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
    }
}
$disply = "";
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $mobile = $_SESSION['mobile'];
    $is_email = $_SESSION['is_email'];
    unset($_SESSION['uid']);
    unset($_SESSION['otp']);
    unset($_SESSION['is_email']);
    $disply = "display:block;";
}
if (isset($_SESSION['message'])) {
    $OTPoutput = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<div class="account-main-wrapper spacer">
    <div class="container">
        <div class="col-md-12 section-header">
            <h2 class="blue-color">Welcome to Planiversity</h2>
        </div>
        <?php
        //print_r($otpResult);
        ?>
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                <div class="account-wrapper">
                    <h2 class="text-center">Restore Password</h2>
                    <!--<p>Enter your email address and we’ll email you the password restore instructions.</p>-->
                    <p>Enter your mobile number OR email address and we’ll send you one time passcode on same.</p>
                    <div class="form-wrap">
                        <form name="restore_form" method="POST" class="form-horizontal" id="restore_form">
                            <div class="error_style"><?php echo $output; ?></div>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <!--<input name="restore_email"  id="restore_email" type="text" class="form-control input-lg" placeholder="Email Address" required="">-->
                                            <input name="mobile_no" id="mobile_no" type="text" class="form-control input-lg pr-mobile_no" placeholder="Enter Mobile Number OR Email Address" required="">

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn-block get-started-btn">Send OTP</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" style="background: #000000cf;<?php echo $disply; ?>">
    <div class="modal-dialog" role="document" style="max-width: 550px;">
        <div class="modal-content">
            <form method="POST" name="otp_form" action="submit_otp.php" class="form-horizontal" id="otp_form">
                <div class="modal-header">
                    <h5 class="modal-title">Validate OTP(One Time Passcode)</h5>
                    <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>-->
                </div>
                <div class="modal-body">
                    <?php if ($OTPoutput != NULL) { ?>
                        <div>
                            <div class="alert alert-danger error_style" style="margin-bottom: 15px;"><?php echo $OTPoutput; ?></div>
                        </div>
                    <?php } ?>
                    <p style="border: none;font-size: 15px;">A OTP (One Time Passcode) has been sent to <?php echo $mobile ?> Please enter the OTP in the field below to veryfy your <?php echo $is_email ? 'email' : 'phone'; ?></p>
                    <input name="otp" maxlength="4" minlength="4" class="form-control input-lg" placeholder="Enter OTP" required="">
                    <input name="uid" id="restore_password" type="hidden" class="form-control input-lg" value="<?php echo $uid ?>">
                </div>
                <div class="modal-footer" style="justify-content: space-between;">
                    <button type="submit" style="margin-left: 20px;" class="get-started-btn">Validate OTP</button>
                    <a href="#" style="text-decoration: none;" onClick="resendOTP(<?php echo $uid ?>,event)">Resend OTP</a>
                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Resend OTP</button>-->
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once("include_new_footer.php") ?>