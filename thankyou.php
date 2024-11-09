<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("include_new_header.php");
?>
<style>
    a:active {
        color: #004eff63;
    }

    .background-e1f2ff {
        background-color: #e1f2ff;
    }

    .color-1f74b7 {
        color: #1f74b7;
    }

    .color-0886E3 {
        color: #0886E3 !important;
    }

    .background-fafafa {
        background-color: #fafafa;
    }

    .mb-90 {
        margin-bottom: 90px !important;
    }

    .mt--40 {
        margin-top: -40px !important;
    }

    .form-item-1 {
        border-radius: 5px;
        background-color: rgba(224, 231, 255, 0.2);
    }
</style>

<div class="account-main-wrapper spacer background-e1f2ff pb-0 mb-90">
    <div class="background-e1f2ff">
        <div class="container">
            <div class="col-md-12 section-header">
                <span style="color: #0886E3 !important; font-weight: 500">PLANIVERSITY</span><br />
                <h2 class="lhight color-0886E3 mb-90 mt-4">Experience travel the way a seasoned <br> traveler would.</h2>
            </div>
        </div>
    </div>
    <div class="background-fafafa">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
                    <div class="account-wrapper mt--40">
                        <span>PLANIVERSITY</span>
                        <h2 class="color-1f74b7" style="font-size:24px">Registration</h2>
                        <hr />
                        <div class="form-wrap pt-5">
                            <h2 style="color: #F39F32">Thanks for registering<br /> with Planiversity</h2><br />
                            <a class="btn free-trial-button" href="<?= SITE ?>login" id="show_loginform" style="font-weight: 600; width: 300px; border-radius: 5px!important; background: linear-gradient(180deg, #FACD61 0%, #F39F32 100%), #0D256E!important; color:#333!important;">Sign In Now</a>
                            <img src="<?= SITE ?>assets/images/thanks_bg.png" alt="" width="100%" srcset="">
                        </div>
                    </div>
                </div>
                <style>
                    .font-1 {
                        font-size: 1rem !important;
                    }

                    .color-2e3a59 {
                        color: #F39F32 !important;
                    }

                    .color-67758D {
                        color: #67758D !important;
                    }

                    .checkmark {
                        border: 2px solid #dddddd !important;
                    }
                </style>
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
<?php include_once("include_new_footer.php"); ?>