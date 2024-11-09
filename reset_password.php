<?php
include_once("config.ini.php");
//include('include_header.php');

if (!isset($_SESSION["uid"]) && !isset($_SESSION["otp"])) {
    header("Location:" . SITE);
}

if (!$auth->checkResetPasswordAuth($_SESSION["uid"], $_SESSION["otp"])) {
    header("Location:" . SITE);
}

//$uid = $_GET["id"];
//$otp = $_GET["otp"];
if ($auth->isLogged()) {
    header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard");
}
include_once("include_login_php.php");
include_once("include_new_header.php");
include('include_doctype.php');
$output = '';

if (isset($_SESSION['resetPasswordResult'])) {
    $output = $_SESSION['resetPasswordResult']['message'];
    unset($_SESSION['resetPasswordResult']);
}
?>

<div class="account-main-wrapper spacer">
    <div class="container">
        <div class="col-md-12 section-header">
            <h2 class="blue-color">Welcome to Planiversity</h2>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12"> 
                <div class = "account-wrapper">
                    <h2 class="text-center">Reset Password</h2>
                    <!--<article class="cont_opc login_cont">-->
                    <form name="reset_form" method="POST" action="submit_otp.php" class="form-horizontal">
                        <!--<h1>Reset Password</h1>-->
                        <div class="error_style"><?php echo $output; ?></div>
                        <fieldset>
                            <div class = "row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input name="reset_password" id="reset_password" maxlength="50" type="password" value="" class="form-control input-lg" placeholder="Password"><br />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input name="reset_password2" id="reset_password2" maxlength="50" type="password" value="" class="form-control input-lg" placeholder="Password Confirmation"><br />                    
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button name="reset_password_submit" id="reset_password_submit" type="submit" class="btn-block get-started-btn" >Reset</button>
                                        <!--<input name="reset_password_submit" id="reset_password_submit" type="submit" class="btn-block get-started-btn" value="RESET">-->
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <!--</article>-->

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("include_new_footer.php") ?>