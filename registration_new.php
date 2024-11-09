<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("process/email_process.php");
include_once("include_new_header.php");
?>
<link href="<?php echo SITE; ?>assets/css/register.css?2022.2" rel="stylesheet">
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#showhidepassword1').click(function() {
            var x = document.getElementById("register_password");
            var xx = document.getElementById("register_password2");
            if (x.type === "password") {
                x.type = "text";
                xx.type = "text";
                document.getElementById("showhidepassword1").attr("src", "<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png");
            } else {
                x.type = "password";
                xx.type = "password";
                document.getElementById("showhidepassword1").attr("src", "<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png");
            }

        });


    });

    function resendOTP(event) {
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo SITE; ?>/submit_otp.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                theResp = JSON.parse(this.responseText);
                console.log(theResp)

            }
        };
        xhr.send('resendOTP=resendOTP');
    }
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
<script src="<?php echo SITE; ?>assets/js/passwordRequirements.js"></script>

<?php
$output = '';
$otpOutput = '';
$name = '';
$email = '';
$disply = "";
$uid = '';

function generate_customerid()
{
    for ($i = 0; $i < 10; $i++) {
        $decstr = substr(md5(uniqid(rand(), true)), 10, 10);
        return $decstr;
    }
}

if (isset($_POST['register_accounttype']) && isset($_POST['register_name']) && isset($_POST['register_email']) && isset($_POST['register_password']) && isset($_POST['register_password2']) && isset($_POST['grecaptcha'])) {
    
    $name = filter_var($_POST["register_name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["register_email"], FILTER_SANITIZE_STRING);
    $mobile_no = null;
    $country_code = null;
    $password = filter_var($_POST["register_password"], FILTER_SANITIZE_STRING);
    $passwordconform = filter_var($_POST["register_password2"], FILTER_SANITIZE_STRING);
    $clientID = generate_customerid();

    $params = array("name" => $name, "account_type" => $_POST["register_accounttype"], "customer_number" => $clientID);
    $result = $auth->register($email, $password, $mobile_no, $country_code, $passwordconform, $params, $_POST['g-recaptcha-response'], $sendmail = false);

    $_SESSION['result'] = $result;

    if ($result['error']) {
        $output = $result['message'];
    } else {
        
        registrationEmailSend($auth,$email,$name);
        
        ?>
        
        <script>
        
        gr("track", "conversion", { email: "<?= $email ?>" });
        
        </script>
        
        <?php
        
        $data = array(
            "site_key" => "18a58770-0d86-497e-a136-5b439bf4ed8a",
            "conversions" => array([
                "date" => date('Y-m-d H:i:s'),
                "email" => $email,
                "first_name" => $name,
                "last_name" => "",
                "ip" => "",
                "city" => "",
                "state" => "",
                "country" => "US"
            ])
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.nudgify.com/api/conversions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'accept:  application/json',
                'content-type:  application/json',
                'Authorization: Bearer z1tigCb8ssn8JKb3edOFPXE5ZS3snORvbAFX3Ahm7IcOr1fx0IAXLCMPfAhz'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $list_id = '02131023d0';
        $authToken = 'df0c2c1f49f4bc48c25f36d5ccdfb21e-us18';
        // The data to send to the API

        $postData = array(
            "email_address" => $email,
            "status" => "subscribed",
            'tags'  => array('Paid')
        );

        // Setup cURL
        $ch = curl_init('https://us18.api.mailchimp.com/3.0/lists/' . $list_id . '/members/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: apikey ' . $authToken,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);
        
        unset($_SESSION["result"]);
        
        
        echo "<script>window.location.href='thankyou';</script>";
        exit;

        // send clientID number by email
        // $mail = new PHPMailer;
        // $mail->CharSet = 'UTF-8';
        // $mail->From = $auth->config->site_email;
        // $mail->FromName = $auth->config->site_name;
        // $mail->addAddress($email);
        // $mail->isHTML(true);
        // $mail->Subject = 'Planiversity.com - Account created';
        // $mail->Body = 'Hello,<br/><br/> Your clientID is <b>' . $clientID . '</b>, you can use this number to login here <a href="' . SITE . '">Planiversity.com</a>';
        // $mail->send();
        // send clientID number by email
        // $output = $result['message'];
        // $output .= 'Your account has been created, the activation link has been sent to your email. Depending on your email settings it may go to your spam folder, rather than the inbox. We\'re happy to have you!';

        // $uid = $result['uid'];
        // $disply = "display: block;";
        // $output .= 'Your account has been created';
        // $name = '';
        // $email = '';
    }
}

if (isset($_SESSION['message'])) {
    $disply = "display: block;";
    $otpOutput = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<div class="account-main-wrapper spacer background-e1f2ff pb-0 mb-90">
    <div class="background-e1f2ff">
        <div class="container">
            <div class="col-md-12 section-header">
                <h2 class="lhight color-0886E3 mb-90 mt-4">Experience travel the way a seasoned <br> traveler would.</h2>
            </div>
        </div>
    </div>
    <div class="background-fafafa">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                    <div class="<?php echo (!empty($result['message'])) ? "alert alert-success" : "" ?> <?php echo (!empty($result['error'])) ? "alert alert-danger" : "" ?> error_style"><?php echo $output; ?></div>
                    <div class="account-wrapper mt--40">
                        <h2 class="color-1f74b7 mt-4" style="font-size:24px">Registration</h2>
                        <div class="form-wrap">
                            <form method="POST" class="form-horizontal" id="register_form">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="radio-container">Individual
                                                    <input type="radio" checked="checked" name="register_accounttype" value="Individual">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class="radio-container">Business
                                                    <input type="radio" checked="" name="register_accounttype" value="Business">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="register_name" id="register_name" class="form-control input-lg form-item-1" placeholder="Enter your Name" required="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="register_email" type="text" class="form-item-1 form-control input-lg" placeholder="Enter your Email Address" required="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control pr-password input-lg inp1 form-item-1" name="register_password" id="register_password" placeholder="Password" required="">
                                                <div class="showhidepassword"><img title="toggle password visibility" id="showhidepassword1" src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png" alt="showorhidepassword1"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="register_password2" id="register_password2" type="password" class="form-control input-lg form-item-1" placeholder="Password Confirm" required="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="6LcIzPIUAAAAANWbMmJsjYbO6aE1R-nGsXD79AbD" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired"></div>
                                                <input id="grecaptcha" name="grecaptcha" type="text" readonly style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                
                                                <button type="submit" class="btn-block get-started-btn get-started-buttn mb-4">Get Started</button>

                                                <!--<p style="margin:5px;text-align:center;">OR</p>-->
                                                <!--<div data-width="200" data-longtitle="true" id="my-signin2" class="g-signin2" data-onsuccess="onSignIn"></div>-->
                                                <div style="cursor:pointer;" onclick="login()">
                                                    <button style="padding-top: 10px;
                                                            padding-bottom: 46px;width:100%;background: #0f41a7;color:white;border-radius:50px;" type="button" class="google-button google-btn">
                                                        <span class="google-button__icon">
                                                            <svg viewBox="0 0 366 372" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335" />
                                                                <path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05" />
                                                                <path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4" />
                                                                <path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853" />
                                                            </svg>
                                                        </span>
                                                        <span class="google-button__text">Register with Google Account</span>
                                                    </button>
                                                </div>
                                                
                                                <button type="reset" style="background-image: unset !important; background-color: hsl(210deg 94% 40%); color: white !important;" class="btn-block get-started-btn get-started-buttn mb-4 mt-4">Reset Data</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <article class="criteria-wrapper">
                                <h4 class="color-67758D">Recommended Secure Password Criteria</h4>
                                <div class="row">
                                    <ul class="row">
                                        <li class="col-lg-5 col-md-12 color-67758D"><i class="fa fa-circle bullet-icon"></i>Eight letters minimum</li>
                                        <li class="col-lg-7 col-md-12 color-67758D"><i class="fa fa-circle bullet-icon"></i>At least two uppercase letters</li>
                                        <li class="col-lg-5 col-md-12 color-67758D"><i class="fa fa-circle bullet-icon"></i>At least two numbers</li>
                                        <li class="col-lg-7 col-md-12 color-67758D"><i class="fa fa-circle bullet-icon"></i>At least two special characters</li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                    <div class="account-benefits-wrapper">
                        <h3 class="color-0886E3">Why Consider Us</h3>
                        <ul>
                            <li class="color-67758D"><i class="fa font-1 color-2e3a59 fa-check-circle bullet-icon"></i>We are a business created from more than fifteen
                                years of military operational experience.
                            </li>

                            <li class="color-67758D"><i class="fa font-1 color-2e3a59 fa-check-circle bullet-icon"></i>No two packets are the same! Customize every
                                packet by selecting the information that is important to you, not the information deemed
                                important by a stranger.
                            </li>

                            <li class="color-67758D"><i class="fa font-1 color-2e3a59 fa-check-circle bullet-icon"></i>Your get to compile itineraries, documents,
                                maps, key destination locations, weather, notes, embassy info, and your schedule
                                together.
                            </li>

                            <li class="color-67758D"><i class="fa font-1 color-2e3a59 fa-check-circle bullet-icon"></i>Security while traveling abroad is important,
                                and that is why we focus on emergency services locations and electronic copies of your
                                documents; so that you can respond, and not have to worry about losing or having your
                                passportconfiscated.
                            </li>

                            <li class="color-67758D"><i class="fa font-1 color-2e3a59 fa-check-circle bullet-icon"></i>We are only beginning and have a ton of plans
                                for expansion. Imagine how far our service will advance in the months and years to come.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $(".pr-password").passwordRequirements({});
    });
    
    function recaptchaCallback() {
        var response = grecaptcha.getResponse();
        $("#grecaptcha").val(response);
    }

    function recaptchaExpired() {
        $("#grecaptcha").val("");
    }
    
    $.validator.addMethod("strong_password", function(value, element) {
        let password = value;
        if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
            return false;
        }
        return true;
    }, function(value, element) {
        let password = $(element).val();
        if (!(/^(.{8,20}$)/.test(password))) {
            return 'Password must be between 8 to 20 characters long.';
        } else if (!(/^(?=.*[A-Z])/.test(password))) {
            return 'Password must contain at least one uppercase.';
        } else if (!(/^(?=.*[a-z])/.test(password))) {
            return 'Password must contain at least one lowercase.';
        } else if (!(/^(?=.*[0-9])/.test(password))) {
            return 'Password must contain at least one digit.';
        } else if (!(/^(?=.*[@#$%&])/.test(password))) {
            return "Password must contain special characters from @#$%&.";
        }
        return false;
    });    


    $("#register_form").validate({
        rules: {
            register_name: {
                required: true,
            },
            register_email: {
                required: true,
                email: true,
            },
            register_password: {
                strong_password: true,
                minlength: 8,
                maxlength: 20
            },
            register_password2: {
                required: true,
                minlength: 8,
                maxlength: 20,
                equalTo: "#register_password"
            },
            grecaptcha: {
                required: true,
            }
        },
        messages: {

            register_name: {
                required: 'Please type your name'
            },
            register_email: {
                required: 'Please type your email address',
                email: 'Please type a valid email address',
            },
            register_password: {
                required: 'Please type your password'
            },
            register_password2: {
                required: 'Please type confirm password',
                equalTo: 'Confirm password mismatch'
            },
            grecaptcha: {
                required: 'Please check the recaptcha'
            }
        },


        submitHandler: function(form) {

            form.submit();

        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });
</script>

<?php include_once("include_new_footer_other.php"); ?>
<script>
    $(document).ready(function() {
        $('.close').click(function() {
            $('#otp-model').hide();
        })
    })
</script>