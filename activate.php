<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("include_new_header.php");

$output = '';
if (isset($_POST['activate_submit']))
   {  $key = filter_var($_POST["activate_key"], FILTER_SANITIZE_STRING);
      $result = $auth->activate($key);
      $output = $result['message'];
	  if ($output=='Account activated.')
	     { $output = '';
		   ?>
			<script type="text/javascript">
            $('#loginerror').html('Your account have been activated, you can now login.');
            setTimeout(function(){	
            $('.login_cont').show('fast');},1000);
            </script>
		   <?php
		 }
   }
?>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!--<script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>-->

    <div class="account-main-wrapper spacer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12"> 
                        <div class = "account-wrapper2">
                            <h2 class="text-center">Activate your account</h2>
                            <p>Enter your activation key below to activate your account.</p>
                            <div class="form-wrap">
                                <form method="post" class="form-horizontal" name="activate_form" id="activate_form">
                                <div class="error_style"> <?php echo $output; ?></div> 
                                    <fieldset>
                                        <div class = "row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" name="activate_key" id="activate_key" maxlength="20" class="form-control input-lg" placeholder="Activation Key" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <button type="submit" name="activate_submit" id="activate_submit" class="btn-block get-started-btn">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                        <div class = "">
                            <div class = "account-benefits-wrapper activate-msg">
                            <h3>Why Planiversity? Consider these points</h3>
                            <ul>
                                <li><i class="fa fa-circle bullet-icon"></i>We are the only service to consolidate so much of your travel information into one location.</li>

                                <li><i class="fa fa-circle bullet-icon"></i>Our focus is on two key elements of travel: organization and situational awareness.</li>

                                <li><i class="fa fa-circle bullet-icon"></i>Your get to compile itineraries, documents, maps, key destination locations, weather, notes, embassy info, and your schedule together.</li>

                                <li><i class="fa fa-circle bullet-icon"></i>Security while traveling abroad is important, and that is why we focus on emergency services locations and electronic copies of your documents; so that you can respond, and not have to worry about losing or having your passportconfiscated.</li>

                                <li><i class="fa fa-circle bullet-icon"></i>We are only beginning and have a ton of plans for expansion. Imagine how far our service will advance in the months and years to come.</li>
                            </ul>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<?php include_once("include_new_footer.php")?>        