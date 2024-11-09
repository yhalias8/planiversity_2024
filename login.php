<?php
include_once("config.ini.php");

if ($auth->isLogged()) {
    header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard");
}

$output = '';

if (isset($_POST['login_usernamep'])) {
    $username = $_POST["login_usernamep"];
    $password = $_POST["login_passwordp"];
    $remember = isset($_POST['login_rememberp']) ? $_POST['login_rememberp'] : 0;
    $result = $auth->login($username, $password, $remember,true);
    if ($result['error']) {
        $output = $result['message'];
        // put failed_attemps in DB
        $query = "UPDATE users SET failed_attemps = failed_attemps + 1 WHERE email = ? OR customer_number = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $username, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        //setcookie('PlaniversityAuthIDD', $result["hash"], $result["expire"], '/');
        setcookie($auth->config->cookie_name, $result["hash"], $result["expire"], '/');
        // put IP and sign_count in DB
        $uid = $auth->getSessionUID($auth->getSessionHash());
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
        if (isset($_SESSION['redirect']) && !empty($_SESSION['redirect'])) {
            $tmp = $_SESSION['redirect'];
            $_SESSION['redirect'] = '';
            header("Location:" . SITE . "welcome");
        } else {
            header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard"); 
        }
    }
}
include_once("include_new_header.php");
include('include_doctype.php');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#showhidepassword1').click(function() {
            var x = document.getElementById("login_passwordp");
            console.log(x.type);
            if (x.type === "password") {
                document.getElementById("login_passwordp").type = "text";
            } else {
                document.getElementById("login_passwordp").type = "password";
            }

        });

    });
</script>

<style>
    .form-control {
        border-radius: 5px;
        background-color: rgba(224, 231, 255, 0.2);
    }

    .get-started-btn,
    .get-started-btn:hover,
    .get-started-btn:focus {
        background-image: linear-gradient(180deg, #FACD61 0%, #F39F32 100%);
        border-radius: 5.4px;
        color: #333333;
        border: none;
        font-weight: bold;
        font-size: 14px !important;
    }

    .pointer {
        cursor: pointer;
    }
</style>
<div class="account-main-wrapper spacer">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                <div class="account-wrapper">
                    <h2 class="text-center" style="color:#1f74b7;font-size:24px">Sign In</h2>
                    <!--<div class="error_style"><?php echo $output; ?></div>-->
                    <div class="<?php echo (!empty($result['message'])) ? "alert alert-success" : "" ?> <?php echo (!empty($result['error'])) ? "alert alert-danger" : "" ?> error_style"><?php echo $output; ?></div>
                    <div class="form-wrap">
                        <form class="form-horizontal" id="login_formp" name="login_formp" method="POST">
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="login_usernamep" id="login_usernamep" maxlength="100" type="text" class="form-control input-lg" placeholder="Email Address or Customer ID" required="">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="login_passwordp" id="login_passwordp" maxlength="50" type="password" class="form-control input-lg inp1" placeholder="Password" required="">
                                            <div class="showhidepassword"><img title="toggle password visibility" id="showhidepassword1" src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png" alt="showorhidepassword1"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label>
                                            <input name="login_rememberp" id="login_rememberp" type="checkbox" value="1" checked="checked"><span class="rem-me">Remember Me</span>
                                        </label>
                                        <a href="<?php echo SITE; ?>password-restore" class="frg-password">Forgot Password</a>
                                    </div><br><br>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn-block get-started-btn">Sign In</button>
                                            <p style="margin:5px;text-align:center;">OR</p>
                                            <!--<div data-width="200" data-longtitle="true" id="my-signin2" class="g-signin2" data-onsuccess="onSignIn"></div>-->
                                            <div style="cursor:pointer;" onclick="login()"><button style="padding-top: 6px;padding-bottom: 43px;width:100%;background-image: linear-gradient(135deg, #0C81D7 0.69%, #0191FD 100%), linear-gradient(
180deg, #FACD61 0%, #F39F32 100%)!important;border-radius: 5.4px!important;color:white;" type="button" class="google-button pointer">
                                                    <span class="google-button__icon">
                                                        <svg viewBox="0 0 366 372" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335" />
                                                            <path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05" />
                                                            <path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4" />
                                                            <path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853" />
                                                        </svg>
                                                    </span>
                                                    <span class="google-button__text">Sign in with Google</span>
                                                </button></div>
                                        </div>
                                        <p class="no-account-yet">No account yet? <a href="<?php echo SITE; ?>registration">Sign Up</a></p>
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
<?php include_once("include_new_footer.php") ?>
