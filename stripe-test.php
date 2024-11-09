<?php
include_once("config.ini.php");
require_once('config.php');
if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

include('include_doctype.php');
?>
<html>

<head>
    <!--<meta charset="utf-8">-->
    <meta charset="ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <script>
        var SITE = 'https://www.planiversity.com/dev';
    </script>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>images/favicon.png">

    <?php include('new_head_files.php'); ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-146873572-1');
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PBF3Z2D');
    </script>
    <!-- End Google Tag Manager -->
</head>
<?php
$img = 'images/my_profile_icon.png';
if ($userdata['picture']) $img = 'ajaxfiles/profile/' . $userdata['picture'];
function get_employee($id_employee)
{
    global $dbh;
    $stmt = $dbh->prepare("SELECT * FROM `employees` WHERE `id_employee` = ?");
    $stmt->bindValue(1, $id_employee, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $employee = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $employee[0]->f_name . ' ' . $employee[0]->l_name;
    //return 'test';

}


?>
<style>
    .stripe-button-el {
        width: 100% !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('input[type=radio][name=radioInline]').change(function() {
            if (this.value == 'monthly') {
                $('#stripe-c-b-c').hide();
                $('#stripe-monthly').show();

            } else {
                $('#stripe-c-b-c').show();
                $('#stripe-monthly').hide();
            }
        });
    });
</script>
<script src="https://js.stripe.com/v3/"></script>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <script src="https://www.paypal.com/sdk/js?client-id=AQZExJJ5yWof72DExjSQLXIGzZwP8WZ6DE1TXFHicEQohUmFYTKh9V6iYhayuznktg7a2WVa1U_qO6PZ&currency=USD&disable-funding=credit"></script>
    <?php include('new_backend_header.php'); ?>
    </header>
    <div class="billing-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="sorry-text">Sorry, One last thing...</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="billing-header-card-box">
                        <div class="page-title-box">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mgn-top">
                                        <div class="radio form-check-inline">
                                            <input type="radio" id="radio-case-by-case" value="casebycase" name="radioInline">
                                            <label for="radio-case-by-case">
                                                <p class="billing-option-txt">Case By Case</p><span class="billing-amount-txt">Only $1.29 per plan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mgn-top">
                                        <div class="radio form-check-inline mgn-top-20">
                                            <input type="radio" id="radio-monthly" value="monthly" name="radioInline" checked>
                                            <label for="radio-monthly">
                                                <p class="billing-option-txt">Monthly Plan</p><span class="billing-amount-txt">Unlimited uses per month for $9.99</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-lg-7">-->
                <!--    <div class="card-box padding-30">-->
                <!--        <div class="payment-type-header-wrapper">-->
                <!--            <div class = "row">-->
                <!--<div class="col-lg-7">-->
                <!--  <form id="stripe-c-b-c" style="display:none;" action="charge.php" method="post">
                            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $stripe['publishable_key']; ?>"
                            data-description="Case By Case"
                            data-amount="0129"
                            data-label = "Pay $1.29 through stripe"
                            data-locale="auto"></script>
                        </form>
                        <form id="stripe-monthly" action="charge.php" method="post">
                            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $stripe['publishable_key']; ?>"
                            data-description="Monthly"
                            data-amount="0999"
                            data-label = "Pay $9.99 through stripe"
                            data-locale="auto"></script>
                        </form>-->
                <div class="col-lg-7">
                    <div class="card-box padding-30">
                        <div class="payment-type-header-wrapper">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="pay-option-wrapper">
                                        <div class="radio form-check-inline mgn-top-20">
                                            <input type="radio" id="visa" value="visa" name="payment-option" checked>
                                            <label for="radio-visa"><img src="assets/images/payment1.png" alt="visa"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="pay-option-wrapper">
                                        <div class="radio form-check-inline mgn-top-20">
                                            <input type="radio" id="mastercard" value="mastercard" name="payment-option">
                                            <label for="radio-mastercard"><img src="assets/images/payment2.png" alt="visa"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="pay-option-wrapper">
                                        <div class="radio form-check-inline mgn-top-20">
                                            <input type="radio" id="amex" value="amex" name="payment-option">
                                            <label for="radio-amex"><img src="assets/images/payment3.png" alt="visa"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="pay-option-wrapper">
                                        <div class="radio form-check-inline mgn-top-20">
                                            <input type="radio" id="discover" value="discover" name="payment-option">
                                            <label for="radio-discover"><img src="assets/images/payment4.png" alt="visa"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-wrap">
                            <form action="charge.php" method="post" class="form-horizontal" id="payment-form">
                                <fieldset>
                                    <label class="billing-label">Full Name</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="account-form-control form-control input-lg inp1" placeholder="First Name" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="account-form-control form-control input-lg inp1" placeholder="Last Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label">Country</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="select-style">
                                                    <select class="input-lg inp1">
                                                        <optgroup id="country-optgroup-Africa" label="Africa">
                                                            <option value="DZ" label="Algeria">Algeria</option>
                                                            <option value="AO" label="Angola">Angola</option>
                                                            <option value="BJ" label="Benin">Benin</option>
                                                            <option value="BW" label="Botswana">Botswana</option>
                                                            <option value="BF" label="Burkina Faso">Burkina Faso</option>
                                                            <option value="BI" label="Burundi">Burundi</option>
                                                            <option value="CM" label="Cameroon">Cameroon</option>
                                                            <option value="CV" label="Cape Verde">Cape Verde</option>
                                                            <option value="CF" label="Central African Republic">Central African Republic</option>
                                                            <option value="TD" label="Chad">Chad</option>
                                                            <option value="KM" label="Comoros">Comoros</option>
                                                            <option value="CG" label="Congo - Brazzaville">Congo - Brazzaville</option>
                                                            <option value="CD" label="Congo - Kinshasa">Congo - Kinshasa</option>
                                                            <option value="CI" label="Côte d’Ivoire">Côte d’Ivoire</option>
                                                            <option value="DJ" label="Djibouti">Djibouti</option>
                                                            <option value="EG" label="Egypt">Egypt</option>
                                                            <option value="GQ" label="Equatorial Guinea">Equatorial Guinea</option>
                                                            <option value="ER" label="Eritrea">Eritrea</option>
                                                            <option value="ET" label="Ethiopia">Ethiopia</option>
                                                            <option value="GA" label="Gabon">Gabon</option>
                                                            <option value="GM" label="Gambia">Gambia</option>
                                                            <option value="GH" label="Ghana">Ghana</option>
                                                            <option value="GN" label="Guinea">Guinea</option>
                                                            <option value="GW" label="Guinea-Bissau">Guinea-Bissau</option>
                                                            <option value="KE" label="Kenya">Kenya</option>
                                                            <option value="LS" label="Lesotho">Lesotho</option>
                                                            <option value="LR" label="Liberia">Liberia</option>
                                                            <option value="LY" label="Libya">Libya</option>
                                                            <option value="MG" label="Madagascar">Madagascar</option>
                                                            <option value="MW" label="Malawi">Malawi</option>
                                                            <option value="ML" label="Mali">Mali</option>
                                                            <option value="MR" label="Mauritania">Mauritania</option>
                                                            <option value="MU" label="Mauritius">Mauritius</option>
                                                            <option value="YT" label="Mayotte">Mayotte</option>
                                                            <option value="MA" label="Morocco">Morocco</option>
                                                            <option value="MZ" label="Mozambique">Mozambique</option>
                                                            <option value="NA" label="Namibia">Namibia</option>
                                                            <option value="NE" label="Niger">Niger</option>
                                                            <option value="NG" label="Nigeria">Nigeria</option>
                                                            <option value="RW" label="Rwanda">Rwanda</option>
                                                            <option value="RE" label="Réunion">Réunion</option>
                                                            <option value="SH" label="Saint Helena">Saint Helena</option>
                                                            <option value="SN" label="Senegal">Senegal</option>
                                                            <option value="SC" label="Seychelles">Seychelles</option>
                                                            <option value="SL" label="Sierra Leone">Sierra Leone</option>
                                                            <option value="SO" label="Somalia">Somalia</option>
                                                            <option value="ZA" label="South Africa">South Africa</option>
                                                            <option value="SD" label="Sudan">Sudan</option>
                                                            <option value="SZ" label="Swaziland">Swaziland</option>
                                                            <option value="ST" label="São Tomé and Príncipe">São Tomé and Príncipe</option>
                                                            <option value="TZ" label="Tanzania">Tanzania</option>
                                                            <option value="TG" label="Togo">Togo</option>
                                                            <option value="TN" label="Tunisia">Tunisia</option>
                                                            <option value="UG" label="Uganda">Uganda</option>
                                                            <option value="EH" label="Western Sahara">Western Sahara</option>
                                                            <option value="ZM" label="Zambia">Zambia</option>
                                                            <option value="ZW" label="Zimbabwe">Zimbabwe</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Americas" label="Americas">
                                                            <option value="AI" label="Anguilla">Anguilla</option>
                                                            <option value="AG" label="Antigua and Barbuda">Antigua and Barbuda</option>
                                                            <option value="AR" label="Argentina">Argentina</option>
                                                            <option value="AW" label="Aruba">Aruba</option>
                                                            <option value="BS" label="Bahamas">Bahamas</option>
                                                            <option value="BB" label="Barbados">Barbados</option>
                                                            <option value="BZ" label="Belize">Belize</option>
                                                            <option value="BM" label="Bermuda">Bermuda</option>
                                                            <option value="BO" label="Bolivia">Bolivia</option>
                                                            <option value="BR" label="Brazil">Brazil</option>
                                                            <option value="VG" label="British Virgin Islands">British Virgin Islands</option>
                                                            <option value="CA" label="Canada">Canada</option>
                                                            <option value="KY" label="Cayman Islands">Cayman Islands</option>
                                                            <option value="CL" label="Chile">Chile</option>
                                                            <option value="CO" label="Colombia">Colombia</option>
                                                            <option value="CR" label="Costa Rica">Costa Rica</option>
                                                            <option value="CU" label="Cuba">Cuba</option>
                                                            <option value="DM" label="Dominica">Dominica</option>
                                                            <option value="DO" label="Dominican Republic">Dominican Republic</option>
                                                            <option value="EC" label="Ecuador">Ecuador</option>
                                                            <option value="SV" label="El Salvador">El Salvador</option>
                                                            <option value="FK" label="Falkland Islands">Falkland Islands</option>
                                                            <option value="GF" label="French Guiana">French Guiana</option>
                                                            <option value="GL" label="Greenland">Greenland</option>
                                                            <option value="GD" label="Grenada">Grenada</option>
                                                            <option value="GP" label="Guadeloupe">Guadeloupe</option>
                                                            <option value="GT" label="Guatemala">Guatemala</option>
                                                            <option value="GY" label="Guyana">Guyana</option>
                                                            <option value="HT" label="Haiti">Haiti</option>
                                                            <option value="HN" label="Honduras">Honduras</option>
                                                            <option value="JM" label="Jamaica">Jamaica</option>
                                                            <option value="MQ" label="Martinique">Martinique</option>
                                                            <option value="MX" label="Mexico">Mexico</option>
                                                            <option value="MS" label="Montserrat">Montserrat</option>
                                                            <option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>
                                                            <option value="NI" label="Nicaragua">Nicaragua</option>
                                                            <option value="PA" label="Panama">Panama</option>
                                                            <option value="PY" label="Paraguay">Paraguay</option>
                                                            <option value="PE" label="Peru">Peru</option>
                                                            <option value="PR" label="Puerto Rico">Puerto Rico</option>
                                                            <option value="BL" label="Saint Barthélemy">Saint Barthélemy</option>
                                                            <option value="KN" label="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                            <option value="LC" label="Saint Lucia">Saint Lucia</option>
                                                            <option value="MF" label="Saint Martin">Saint Martin</option>
                                                            <option value="PM" label="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                            <option value="VC" label="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                            <option value="SR" label="Suriname">Suriname</option>
                                                            <option value="TT" label="Trinidad and Tobago">Trinidad and Tobago</option>
                                                            <option value="TC" label="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                            <option value="VI" label="U.S. Virgin Islands">U.S. Virgin Islands</option>
                                                            <option value="US" label="United States">United States</option>
                                                            <option value="UY" label="Uruguay">Uruguay</option>
                                                            <option value="VE" label="Venezuela">Venezuela</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Asia" label="Asia">
                                                            <option value="AF" label="Afghanistan">Afghanistan</option>
                                                            <option value="AM" label="Armenia">Armenia</option>
                                                            <option value="AZ" label="Azerbaijan">Azerbaijan</option>
                                                            <option value="BH" label="Bahrain">Bahrain</option>
                                                            <option value="BD" label="Bangladesh">Bangladesh</option>
                                                            <option value="BT" label="Bhutan">Bhutan</option>
                                                            <option value="BN" label="Brunei">Brunei</option>
                                                            <option value="KH" label="Cambodia">Cambodia</option>
                                                            <option value="CN" label="China">China</option>
                                                            <option value="CY" label="Cyprus">Cyprus</option>
                                                            <option value="GE" label="Georgia">Georgia</option>
                                                            <option value="HK" label="Hong Kong SAR China">Hong Kong SAR China</option>
                                                            <option value="IN" label="India">India</option>
                                                            <option value="ID" label="Indonesia">Indonesia</option>
                                                            <option value="IR" label="Iran">Iran</option>
                                                            <option value="IQ" label="Iraq">Iraq</option>
                                                            <option value="IL" label="Israel">Israel</option>
                                                            <option value="JP" label="Japan">Japan</option>
                                                            <option value="JO" label="Jordan">Jordan</option>
                                                            <option value="KZ" label="Kazakhstan">Kazakhstan</option>
                                                            <option value="KW" label="Kuwait">Kuwait</option>
                                                            <option value="KG" label="Kyrgyzstan">Kyrgyzstan</option>
                                                            <option value="LA" label="Laos">Laos</option>
                                                            <option value="LB" label="Lebanon">Lebanon</option>
                                                            <option value="MO" label="Macau SAR China">Macau SAR China</option>
                                                            <option value="MY" label="Malaysia">Malaysia</option>
                                                            <option value="MV" label="Maldives">Maldives</option>
                                                            <option value="MN" label="Mongolia">Mongolia</option>
                                                            <option value="MM" label="Myanmar [Burma]">Myanmar [Burma]</option>
                                                            <option value="NP" label="Nepal">Nepal</option>
                                                            <option value="NT" label="Neutral Zone">Neutral Zone</option>
                                                            <option value="KP" label="North Korea">North Korea</option>
                                                            <option value="OM" label="Oman">Oman</option>
                                                            <option value="PK" label="Pakistan">Pakistan</option>
                                                            <option value="PS" label="Palestinian Territories">Palestinian Territories</option>
                                                            <option value="YD" label="People's Democratic Republic of Yemen">People's Democratic Republic of Yemen</option>
                                                            <option value="PH" label="Philippines">Philippines</option>
                                                            <option value="QA" label="Qatar">Qatar</option>
                                                            <option value="SA" label="Saudi Arabia">Saudi Arabia</option>
                                                            <option value="SG" label="Singapore">Singapore</option>
                                                            <option value="KR" label="South Korea">South Korea</option>
                                                            <option value="LK" label="Sri Lanka">Sri Lanka</option>
                                                            <option value="SY" label="Syria">Syria</option>
                                                            <option value="TW" label="Taiwan">Taiwan</option>
                                                            <option value="TJ" label="Tajikistan">Tajikistan</option>
                                                            <option value="TH" label="Thailand">Thailand</option>
                                                            <option value="TL" label="Timor-Leste">Timor-Leste</option>
                                                            <option value="TR" label="Turkey">Turkey</option>
                                                            <option value="™" label="Turkmenistan">Turkmenistan</option>
                                                            <option value="AE" label="United Arab Emirates">United Arab Emirates</option>
                                                            <option value="UZ" label="Uzbekistan">Uzbekistan</option>
                                                            <option value="VN" label="Vietnam">Vietnam</option>
                                                            <option value="YE" label="Yemen">Yemen</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Europe" label="Europe">
                                                            <option value="AL" label="Albania">Albania</option>
                                                            <option value="AD" label="Andorra">Andorra</option>
                                                            <option value="AT" label="Austria">Austria</option>
                                                            <option value="BY" label="Belarus">Belarus</option>
                                                            <option value="BE" label="Belgium">Belgium</option>
                                                            <option value="BA" label="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                            <option value="BG" label="Bulgaria">Bulgaria</option>
                                                            <option value="HR" label="Croatia">Croatia</option>
                                                            <option value="CY" label="Cyprus">Cyprus</option>
                                                            <option value="CZ" label="Czech Republic">Czech Republic</option>
                                                            <option value="DK" label="Denmark">Denmark</option>
                                                            <option value="DD" label="East Germany">East Germany</option>
                                                            <option value="EE" label="Estonia">Estonia</option>
                                                            <option value="FO" label="Faroe Islands">Faroe Islands</option>
                                                            <option value="FI" label="Finland">Finland</option>
                                                            <option value="FR" label="France">France</option>
                                                            <option value="DE" label="Germany">Germany</option>
                                                            <option value="GI" label="Gibraltar">Gibraltar</option>
                                                            <option value="GR" label="Greece">Greece</option>
                                                            <option value="GG" label="Guernsey">Guernsey</option>
                                                            <option value="HU" label="Hungary">Hungary</option>
                                                            <option value="IS" label="Iceland">Iceland</option>
                                                            <option value="IE" label="Ireland">Ireland</option>
                                                            <option value="IM" label="Isle of Man">Isle of Man</option>
                                                            <option value="IT" label="Italy">Italy</option>
                                                            <option value="JE" label="Jersey">Jersey</option>
                                                            <option value="LV" label="Latvia">Latvia</option>
                                                            <option value="LI" label="Liechtenstein">Liechtenstein</option>
                                                            <option value="LT" label="Lithuania">Lithuania</option>
                                                            <option value="LU" label="Luxembourg">Luxembourg</option>
                                                            <option value="MK" label="Macedonia">Macedonia</option>
                                                            <option value="MT" label="Malta">Malta</option>
                                                            <option value="FX" label="Metropolitan France">Metropolitan France</option>
                                                            <option value="MD" label="Moldova">Moldova</option>
                                                            <option value="MC" label="Monaco">Monaco</option>
                                                            <option value="ME" label="Montenegro">Montenegro</option>
                                                            <option value="NL" label="Netherlands">Netherlands</option>
                                                            <option value="NO" label="Norway">Norway</option>
                                                            <option value="PL" label="Poland">Poland</option>
                                                            <option value="PT" label="Portugal">Portugal</option>
                                                            <option value="RO" label="Romania">Romania</option>
                                                            <option value="RU" label="Russia">Russia</option>
                                                            <option value="SM" label="San Marino">San Marino</option>
                                                            <option value="RS" label="Serbia">Serbia</option>
                                                            <option value="CS" label="Serbia and Montenegro">Serbia and Montenegro</option>
                                                            <option value="SK" label="Slovakia">Slovakia</option>
                                                            <option value="SI" label="Slovenia">Slovenia</option>
                                                            <option value="ES" label="Spain">Spain</option>
                                                            <option value="SJ" label="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                            <option value="SE" label="Sweden">Sweden</option>
                                                            <option value="CH" label="Switzerland">Switzerland</option>
                                                            <option value="UA" label="Ukraine">Ukraine</option>
                                                            <option value="SU" label="Union of Soviet Socialist Republics">Union of Soviet Socialist Republics</option>
                                                            <option value="GB" label="United Kingdom">United Kingdom</option>
                                                            <option value="VA" label="Vatican City">Vatican City</option>
                                                            <option value="AX" label="Åland Islands">Åland Islands</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Oceania" label="Oceania">
                                                            <option value="AS" label="American Samoa">American Samoa</option>
                                                            <option value="AQ" label="Antarctica">Antarctica</option>
                                                            <option value="AU" label="Australia">Australia</option>
                                                            <option value="BV" label="Bouvet Island">Bouvet Island</option>
                                                            <option value="IO" label="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                            <option value="CX" label="Christmas Island">Christmas Island</option>
                                                            <option value="CC" label="Cocos [Keeling] Islands">Cocos [Keeling] Islands</option>
                                                            <option value="CK" label="Cook Islands">Cook Islands</option>
                                                            <option value="FJ" label="Fiji">Fiji</option>
                                                            <option value="PF" label="French Polynesia">French Polynesia</option>
                                                            <option value="TF" label="French Southern Territories">French Southern Territories</option>
                                                            <option value="GU" label="Guam">Guam</option>
                                                            <option value="HM" label="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                                            <option value="KI" label="Kiribati">Kiribati</option>
                                                            <option value="MH" label="Marshall Islands">Marshall Islands</option>
                                                            <option value="FM" label="Micronesia">Micronesia</option>
                                                            <option value="NR" label="Nauru">Nauru</option>
                                                            <option value="NC" label="New Caledonia">New Caledonia</option>
                                                            <option value="NZ" label="New Zealand">New Zealand</option>
                                                            <option value="NU" label="Niue">Niue</option>
                                                            <option value="NF" label="Norfolk Island">Norfolk Island</option>
                                                            <option value="MP" label="Northern Mariana Islands">Northern Mariana Islands</option>
                                                            <option value="PW" label="Palau">Palau</option>
                                                            <option value="PG" label="Papua New Guinea">Papua New Guinea</option>
                                                            <option value="PN" label="Pitcairn Islands">Pitcairn Islands</option>
                                                            <option value="WS" label="Samoa">Samoa</option>
                                                            <option value="SB" label="Solomon Islands">Solomon Islands</option>
                                                            <option value="GS" label="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                                            <option value="TK" label="Tokelau">Tokelau</option>
                                                            <option value="TO" label="Tonga">Tonga</option>
                                                            <option value="TV" label="Tuvalu">Tuvalu</option>
                                                            <option value="UM" label="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option>
                                                            <option value="VU" label="Vanuatu">Vanuatu</option>
                                                            <option value="WF" label="Wallis and Futuna">Wallis and Futuna</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label">Address</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label">City</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label">State</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="select-style">
                                                    <select class="input-lg inp1">
                                                        <option value="Alabama">Alabama</option>
                                                        <option value="Alaska">Alaska</option>
                                                        <option value="Arizona">Arizona</option>
                                                        <option value="Arkansas">Arkansas</option>
                                                        <option value="California">California</option>
                                                        <option value="Colorado">Colorado</option>
                                                        <option value="Connecticut">Connecticut</option>
                                                        <option value="Delaware">Delaware</option>
                                                        <option value="District of Columbia">District of Columbia</option>
                                                        <option value="Florida">Florida</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Guam">Guam</option>
                                                        <option value="Hawaii">Hawaii</option>
                                                        <option value="Idaho">Idaho</option>
                                                        <option value="Illinois">Illinois</option>
                                                        <option value="Indiana">Indiana</option>
                                                        <option value="Iowa">Iowa</option>
                                                        <option value="Kansas">Kansas</option>
                                                        <option value="Kentucky">Kentucky</option>
                                                        <option value="Louisiana">Louisiana</option>
                                                        <option value="Maine">Maine</option>
                                                        <option value="Maryland">Maryland</option>
                                                        <option value="Massachusetts">Massachusetts</option>
                                                        <option value="Michigan">Michigan</option>
                                                        <option value="Minnesota">Minnesota</option>
                                                        <option value="Mississippi">Mississippi</option>
                                                        <option value="Missouri">Missouri</option>
                                                        <option value="Montana">Montana</option>
                                                        <option value="Nebraska">Nebraska</option>
                                                        <option value="Nevada">Nevada</option>
                                                        <option value="New Hampshire">New Hampshire</option>
                                                        <option value="New Jersey">New Jersey</option>
                                                        <option value="New Mexico">New Mexico</option>
                                                        <option value="New York">New York</option>
                                                        <option value="North Carolina">North Carolina</option>
                                                        <option value="North Dakota">North Dakota</option>
                                                        <option value="Northern Marianas Islands">Northern Marianas Islands</option>
                                                        <option value="Ohio">Ohio</option>
                                                        <option value="Oklahoma">Oklahoma</option>
                                                        <option value="Oregon">Oregon</option>
                                                        <option value="Pennsylvania">Pennsylvania</option>
                                                        <option value="Puerto Rico">Puerto Rico</option>
                                                        <option value="Rhode Island">Rhode Island</option>
                                                        <option value="South Carolina">South Carolina</option>
                                                        <option value="South Dakota">South Dakota</option>
                                                        <option value="Tennessee">Tennessee</option>
                                                        <option value="Texas">Texas</option>
                                                        <option value="Utah">Utah</option>
                                                        <option value="Vermont">Vermont</option>
                                                        <option value="Virginia">Virginia</option>
                                                        <option value="Virgin Islands">Virgin Islands</option>
                                                        <option value="Washington">Washington</option>
                                                        <option value="West Virginia">West Virginia</option>
                                                        <option value="Wisconsin">Wisconsin</option>
                                                        <option value="Wyoming">Wyoming</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label">Zip Code</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="card-element">Card Number</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group account-form-control form-control input-lg inp1" id="card-element">
                                                <!--<input type="text" class="account-form-control form-control input-lg inp1" required="">-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div style=" color: #fa755a;padding-bottom:10px;" id="card-errors" role="alert"></div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="expiry-element">Expiration Date</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group account-form-control form-control input-lg inp1" id="expiry-element">
                                                <!--<div class="select-style">-->
                                                <!--  <select class = "input-lg inp1">-->
                                                <!--    <option value="January">January</option>-->
                                                <!--    <option value="Febuary">Febuary</option>-->
                                                <!--    <option value="March">March</option>-->
                                                <!--    <option value="April">April</option>-->
                                                <!--    <option value="May">May</option>-->
                                                <!--    <option value="June">June</option>-->
                                                <!--    <option value="July">July</option>-->
                                                <!--    <option value="August">August</option>-->
                                                <!--    <option value="September">September</option>-->
                                                <!--    <option value="October">October</option>-->
                                                <!--    <option value="November">November</option>-->
                                                <!--    <option value="December">December</option>-->
                                                <!--  </select>-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-6">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <div class="select-style">-->
                                        <!--          <select class = "input-lg inp1">-->
                                        <!--            <option value="2020">2020</option>-->
                                        <!--            <option value="2019">2019</option>-->
                                        <!--            <option value="2018">2018</option>-->
                                        <!--            <option value="2017">2017</option>-->
                                        <!--            <option value="2016">2016</option>-->
                                        <!--            <option value="2015">2015</option>-->
                                        <!--            <option value="2014">2014</option>-->
                                        <!--            <option value="2013">2013</option>-->
                                        <!--            <option value="2012">2012</option>-->
                                        <!--            <option value="2011">2011</option>-->
                                        <!--            <option value="2010">2010</option>-->
                                        <!--            <option value="2009">2009</option>-->
                                        <!--            <option value="2008">2008</option>-->
                                        <!--            <option value="2007">2007</option>-->
                                        <!--            <option value="2006">2006</option>-->
                                        <!--            <option value="2005">2005</option>-->
                                        <!--            <option value="2004">2004</option>-->
                                        <!--            <option value="2003">2003</option>-->
                                        <!--            <option value="2002">2002</option>-->
                                        <!--            <option value="2001">2001</option>-->
                                        <!--            <option value="2000">2000</option>-->
                                        <!--            <option value="1999">1999</option>-->
                                        <!--            <option value="1998">1998</option>-->
                                        <!--            <option value="1997">1997</option>-->
                                        <!--            <option value="1996">1996</option>-->
                                        <!--            <option value="1995">1995</option>-->
                                        <!--            <option value="1994">1994</option>-->
                                        <!--            <option value="1993">1993</option>-->
                                        <!--            <option value="1992">1992</option>-->
                                        <!--            <option value="1991">1991</option>-->
                                        <!--            <option value="1990">1990</option>-->
                                        <!--            <option value="1989">1989</option>-->
                                        <!--            <option value="1988">1988</option>-->
                                        <!--            <option value="1987">1987</option>-->
                                        <!--            <option value="1986">1986</option>-->
                                        <!--            <option value="1985">1985</option>-->
                                        <!--            <option value="1984">1984</option>-->
                                        <!--            <option value="1983">1983</option>-->
                                        <!--            <option value="1982">1982</option>-->
                                        <!--            <option value="1981">1981</option>-->
                                        <!--            <option value="1980">1980</option>-->
                                        <!--            <option value="1979">1979</option>-->
                                        <!--            <option value="1978">1978</option>-->
                                        <!--            <option value="1977">1977</option>-->
                                        <!--            <option value="1976">1976</option>-->
                                        <!--            <option value="1975">1975</option>-->
                                        <!--            <option value="1974">1974</option>-->
                                        <!--            <option value="1973">1973</option>-->
                                        <!--            <option value="1972">1972</option>-->
                                        <!--            <option value="1971">1971</option>-->
                                        <!--            <option value="1970">1970</option>-->
                                        <!--            <option value="1969">1969</option>-->
                                        <!--            <option value="1968">1968</option>-->
                                        <!--            <option value="1967">1967</option>-->
                                        <!--            <option value="1966">1966</option>-->
                                        <!--            <option value="1965">1965</option>-->
                                        <!--            <option value="1964">1964</option>-->
                                        <!--            <option value="1963">1963</option>-->
                                        <!--            <option value="1962">1962</option>-->
                                        <!--            <option value="1961">1961</option>-->
                                        <!--            <option value="1960">1960</option>-->
                                        <!--            <option value="1959">1959</option>-->
                                        <!--            <option value="1958">1958</option>-->
                                        <!--            <option value="1957">1957</option>-->
                                        <!--            <option value="1956">1956</option>-->
                                        <!--            <option value="1955">1955</option>-->
                                        <!--            <option value="1954">1954</option>-->
                                        <!--            <option value="1953">1953</option>-->
                                        <!--            <option value="1952">1952</option>-->
                                        <!--            <option value="1951">1951</option>-->
                                        <!--            <option value="1950">1950</option>-->
                                        <!--            <option value="1949">1949</option>-->
                                        <!--            <option value="1948">1948</option>-->
                                        <!--            <option value="1947">1947</option>-->
                                        <!--            <option value="1946">1946</option>-->
                                        <!--            <option value="1945">1945</option>-->
                                        <!--            <option value="1944">1944</option>-->
                                        <!--            <option value="1943">1943</option>-->
                                        <!--            <option value="1942">1942</option>-->
                                        <!--            <option value="1941">1941</option>-->
                                        <!--            <option value="1940">1940</option>-->
                                        <!--            <option value="1939">1939</option>-->
                                        <!--            <option value="1938">1938</option>-->
                                        <!--            <option value="1937">1937</option>-->
                                        <!--            <option value="1936">1936</option>-->
                                        <!--            <option value="1935">1935</option>-->
                                        <!--            <option value="1934">1934</option>-->
                                        <!--            <option value="1933">1933</option>-->
                                        <!--            <option value="1932">1932</option>-->
                                        <!--            <option value="1931">1931</option>-->
                                        <!--            <option value="1930">1930</option>-->
                                        <!--          </select>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div>
                                    <label class="billing-label" for="cvc-element">CVC</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div id="cvc-element" class="form-group account-form-control form-control input-lg inp1">
                                                <!--<input type="text" class="account-form-control form-control input-lg inp1" placeholder="CVC" required="">-->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <p class="whats-this-text"><a href="">What's This</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="process-payment-btn" data-toggle="modal" data-target="#myModal">Pay through Stripe</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card-box">
                        <div class="pay-with-paypal">
                            <img src="assets/images/pay-paypal.png" alt="pay with paypal">
                            <a href="" class="goto-paypal-btn">CLICK BELOW TO BE DIRECTED TO PAYPAL</a>
                            <!--<div id="paypal-button-container"></div>-->
                            <script>
                                paypal.Buttons({
                                    createOrder: function(data, actions) {
                                        return actions.order.create({
                                            purchase_units: [{
                                                amount: {
                                                    value: '9.99'
                                                }
                                            }]
                                        });
                                    },
                                    onApprove: function(data, actions) {
                                        return actions.order.capture().then(function(details) {
                                            alert('Transaction completed by ' + details.payer.name.given_name);
                                            // Call your server to save the transaction
                                            return fetch('/paypal-transaction-complete', {
                                                method: 'post',
                                                headers: {
                                                    'content-type': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    orderID: data.orderID
                                                })
                                            });
                                        });
                                    }
                                }).render('#paypal-button-container');
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!--<div class="row">-->
    <!--    <div class="col-lg-7">-->
    <!--        <div class="card-box padding-30">-->
    <!--            <div class="payment-type-header-wrapper">-->
    <!--                <div class = "row">-->
    <!--                    <div class="col-sm-2">-->
    <!--                        <div class="pay-option-wrapper">-->
    <!--                            <div class="radio form-check-inline mgn-top-20">-->
    <!--                                <input type="radio" id="visa" value="visa" name="payment-option" checked>-->
    <!--                                <label for="radio-visa"><img src = "assets/images/payment1.png" alt = "visa"></label>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-sm-2">-->
    <!--                        <div class="pay-option-wrapper">-->
    <!--                            <div class="radio form-check-inline mgn-top-20">-->
    <!--                                <input type="radio" id="mastercard" value="mastercard" name="payment-option">-->
    <!--                                <label for="radio-mastercard"><img src = "assets/images/payment2.png" alt = "visa"></label>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-sm-2">-->
    <!--                        <div class="pay-option-wrapper">-->
    <!--                            <div class="radio form-check-inline mgn-top-20">-->
    <!--                                <input type="radio" id="amex" value="amex" name="payment-option">-->
    <!--                                <label for="radio-amex"><img src = "assets/images/payment3.png" alt = "visa"></label>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-sm-2">-->
    <!--                        <div class="pay-option-wrapper">-->
    <!--                            <div class="radio form-check-inline mgn-top-20">-->
    <!--                                <input type="radio" id="discover" value="discover" name="payment-option">-->
    <!--                                <label for="radio-discover"><img src = "assets/images/payment4.png" alt = "visa"></label>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="form-wrap">-->
    <!--                <form class="form-horizontal" id="form-onboarding">-->
    <!--                    <fieldset>-->
    <!--                        <label class = "billing-label">Full Name</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" placeholder="First Name" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" placeholder="Last Name" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">Country</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <div class="form-group">-->
    <!--                                    <div class="select-style">-->
    <!--                                      <select class = "input-lg inp1">-->
    <!--                                        <optgroup id="country-optgroup-Africa" label="Africa">-->
    <!--                                        <option value="DZ" label="Algeria">Algeria</option>-->
    <!--                                        <option value="AO" label="Angola">Angola</option>-->
    <!--                                        <option value="BJ" label="Benin">Benin</option>-->
    <!--                                        <option value="BW" label="Botswana">Botswana</option>-->
    <!--                                        <option value="BF" label="Burkina Faso">Burkina Faso</option>-->
    <!--                                        <option value="BI" label="Burundi">Burundi</option>-->
    <!--                                        <option value="CM" label="Cameroon">Cameroon</option>-->
    <!--                                        <option value="CV" label="Cape Verde">Cape Verde</option>-->
    <!--                                        <option value="CF" label="Central African Republic">Central African Republic</option>-->
    <!--                                        <option value="TD" label="Chad">Chad</option>-->
    <!--                                        <option value="KM" label="Comoros">Comoros</option>-->
    <!--                                        <option value="CG" label="Congo - Brazzaville">Congo - Brazzaville</option>-->
    <!--                                        <option value="CD" label="Congo - Kinshasa">Congo - Kinshasa</option>-->
    <!--                                        <option value="CI" label="Côte d’Ivoire">Côte d’Ivoire</option>-->
    <!--                                        <option value="DJ" label="Djibouti">Djibouti</option>-->
    <!--                                        <option value="EG" label="Egypt">Egypt</option>-->
    <!--                                        <option value="GQ" label="Equatorial Guinea">Equatorial Guinea</option>-->
    <!--                                        <option value="ER" label="Eritrea">Eritrea</option>-->
    <!--                                        <option value="ET" label="Ethiopia">Ethiopia</option>-->
    <!--                                        <option value="GA" label="Gabon">Gabon</option>-->
    <!--                                        <option value="GM" label="Gambia">Gambia</option>-->
    <!--                                        <option value="GH" label="Ghana">Ghana</option>-->
    <!--                                        <option value="GN" label="Guinea">Guinea</option>-->
    <!--                                        <option value="GW" label="Guinea-Bissau">Guinea-Bissau</option>-->
    <!--                                        <option value="KE" label="Kenya">Kenya</option>-->
    <!--                                        <option value="LS" label="Lesotho">Lesotho</option>-->
    <!--                                        <option value="LR" label="Liberia">Liberia</option>-->
    <!--                                        <option value="LY" label="Libya">Libya</option>-->
    <!--                                        <option value="MG" label="Madagascar">Madagascar</option>-->
    <!--                                        <option value="MW" label="Malawi">Malawi</option>-->
    <!--                                        <option value="ML" label="Mali">Mali</option>-->
    <!--                                        <option value="MR" label="Mauritania">Mauritania</option>-->
    <!--                                        <option value="MU" label="Mauritius">Mauritius</option>-->
    <!--                                        <option value="YT" label="Mayotte">Mayotte</option>-->
    <!--                                        <option value="MA" label="Morocco">Morocco</option>-->
    <!--                                        <option value="MZ" label="Mozambique">Mozambique</option>-->
    <!--                                        <option value="NA" label="Namibia">Namibia</option>-->
    <!--                                        <option value="NE" label="Niger">Niger</option>-->
    <!--                                        <option value="NG" label="Nigeria">Nigeria</option>-->
    <!--                                        <option value="RW" label="Rwanda">Rwanda</option>-->
    <!--                                        <option value="RE" label="Réunion">Réunion</option>-->
    <!--                                        <option value="SH" label="Saint Helena">Saint Helena</option>-->
    <!--                                        <option value="SN" label="Senegal">Senegal</option>-->
    <!--                                        <option value="SC" label="Seychelles">Seychelles</option>-->
    <!--                                        <option value="SL" label="Sierra Leone">Sierra Leone</option>-->
    <!--                                        <option value="SO" label="Somalia">Somalia</option>-->
    <!--                                        <option value="ZA" label="South Africa">South Africa</option>-->
    <!--                                        <option value="SD" label="Sudan">Sudan</option>-->
    <!--                                        <option value="SZ" label="Swaziland">Swaziland</option>-->
    <!--                                        <option value="ST" label="São Tomé and Príncipe">São Tomé and Príncipe</option>-->
    <!--                                        <option value="TZ" label="Tanzania">Tanzania</option>-->
    <!--                                        <option value="TG" label="Togo">Togo</option>-->
    <!--                                        <option value="TN" label="Tunisia">Tunisia</option>-->
    <!--                                        <option value="UG" label="Uganda">Uganda</option>-->
    <!--                                        <option value="EH" label="Western Sahara">Western Sahara</option>-->
    <!--                                        <option value="ZM" label="Zambia">Zambia</option>-->
    <!--                                        <option value="ZW" label="Zimbabwe">Zimbabwe</option>-->
    <!--                                        </optgroup>-->
    <!--                                        <optgroup id="country-optgroup-Americas" label="Americas">-->
    <!--                                        <option value="AI" label="Anguilla">Anguilla</option>-->
    <!--                                        <option value="AG" label="Antigua and Barbuda">Antigua and Barbuda</option>-->
    <!--                                        <option value="AR" label="Argentina">Argentina</option>-->
    <!--                                        <option value="AW" label="Aruba">Aruba</option>-->
    <!--                                        <option value="BS" label="Bahamas">Bahamas</option>-->
    <!--                                        <option value="BB" label="Barbados">Barbados</option>-->
    <!--                                        <option value="BZ" label="Belize">Belize</option>-->
    <!--                                        <option value="BM" label="Bermuda">Bermuda</option>-->
    <!--                                        <option value="BO" label="Bolivia">Bolivia</option>-->
    <!--                                        <option value="BR" label="Brazil">Brazil</option>-->
    <!--                                        <option value="VG" label="British Virgin Islands">British Virgin Islands</option>-->
    <!--                                        <option value="CA" label="Canada">Canada</option>-->
    <!--                                        <option value="KY" label="Cayman Islands">Cayman Islands</option>-->
    <!--                                        <option value="CL" label="Chile">Chile</option>-->
    <!--                                        <option value="CO" label="Colombia">Colombia</option>-->
    <!--                                        <option value="CR" label="Costa Rica">Costa Rica</option>-->
    <!--                                        <option value="CU" label="Cuba">Cuba</option>-->
    <!--                                        <option value="DM" label="Dominica">Dominica</option>-->
    <!--                                        <option value="DO" label="Dominican Republic">Dominican Republic</option>-->
    <!--                                        <option value="EC" label="Ecuador">Ecuador</option>-->
    <!--                                        <option value="SV" label="El Salvador">El Salvador</option>-->
    <!--                                        <option value="FK" label="Falkland Islands">Falkland Islands</option>-->
    <!--                                        <option value="GF" label="French Guiana">French Guiana</option>-->
    <!--                                        <option value="GL" label="Greenland">Greenland</option>-->
    <!--                                        <option value="GD" label="Grenada">Grenada</option>-->
    <!--                                        <option value="GP" label="Guadeloupe">Guadeloupe</option>-->
    <!--                                        <option value="GT" label="Guatemala">Guatemala</option>-->
    <!--                                        <option value="GY" label="Guyana">Guyana</option>-->
    <!--                                        <option value="HT" label="Haiti">Haiti</option>-->
    <!--                                        <option value="HN" label="Honduras">Honduras</option>-->
    <!--                                        <option value="JM" label="Jamaica">Jamaica</option>-->
    <!--                                        <option value="MQ" label="Martinique">Martinique</option>-->
    <!--                                        <option value="MX" label="Mexico">Mexico</option>-->
    <!--                                        <option value="MS" label="Montserrat">Montserrat</option>-->
    <!--                                        <option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>-->
    <!--                                        <option value="NI" label="Nicaragua">Nicaragua</option>-->
    <!--                                        <option value="PA" label="Panama">Panama</option>-->
    <!--                                        <option value="PY" label="Paraguay">Paraguay</option>-->
    <!--                                        <option value="PE" label="Peru">Peru</option>-->
    <!--                                        <option value="PR" label="Puerto Rico">Puerto Rico</option>-->
    <!--                                        <option value="BL" label="Saint Barthélemy">Saint Barthélemy</option>-->
    <!--                                        <option value="KN" label="Saint Kitts and Nevis">Saint Kitts and Nevis</option>-->
    <!--                                        <option value="LC" label="Saint Lucia">Saint Lucia</option>-->
    <!--                                        <option value="MF" label="Saint Martin">Saint Martin</option>-->
    <!--                                        <option value="PM" label="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>-->
    <!--                                        <option value="VC" label="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>-->
    <!--                                        <option value="SR" label="Suriname">Suriname</option>-->
    <!--                                        <option value="TT" label="Trinidad and Tobago">Trinidad and Tobago</option>-->
    <!--                                        <option value="TC" label="Turks and Caicos Islands">Turks and Caicos Islands</option>-->
    <!--                                        <option value="VI" label="U.S. Virgin Islands">U.S. Virgin Islands</option>-->
    <!--                                        <option value="US" label="United States">United States</option>-->
    <!--                                        <option value="UY" label="Uruguay">Uruguay</option>-->
    <!--                                        <option value="VE" label="Venezuela">Venezuela</option>-->
    <!--                                        </optgroup>-->
    <!--                                        <optgroup id="country-optgroup-Asia" label="Asia">-->
    <!--                                        <option value="AF" label="Afghanistan">Afghanistan</option>-->
    <!--                                        <option value="AM" label="Armenia">Armenia</option>-->
    <!--                                        <option value="AZ" label="Azerbaijan">Azerbaijan</option>-->
    <!--                                        <option value="BH" label="Bahrain">Bahrain</option>-->
    <!--                                        <option value="BD" label="Bangladesh">Bangladesh</option>-->
    <!--                                        <option value="BT" label="Bhutan">Bhutan</option>-->
    <!--                                        <option value="BN" label="Brunei">Brunei</option>-->
    <!--                                        <option value="KH" label="Cambodia">Cambodia</option>-->
    <!--                                        <option value="CN" label="China">China</option>-->
    <!--                                        <option value="CY" label="Cyprus">Cyprus</option>-->
    <!--                                        <option value="GE" label="Georgia">Georgia</option>-->
    <!--                                        <option value="HK" label="Hong Kong SAR China">Hong Kong SAR China</option>-->
    <!--                                        <option value="IN" label="India">India</option>-->
    <!--                                        <option value="ID" label="Indonesia">Indonesia</option>-->
    <!--                                        <option value="IR" label="Iran">Iran</option>-->
    <!--                                        <option value="IQ" label="Iraq">Iraq</option>-->
    <!--                                        <option value="IL" label="Israel">Israel</option>-->
    <!--                                        <option value="JP" label="Japan">Japan</option>-->
    <!--                                        <option value="JO" label="Jordan">Jordan</option>-->
    <!--                                        <option value="KZ" label="Kazakhstan">Kazakhstan</option>-->
    <!--                                        <option value="KW" label="Kuwait">Kuwait</option>-->
    <!--                                        <option value="KG" label="Kyrgyzstan">Kyrgyzstan</option>-->
    <!--                                        <option value="LA" label="Laos">Laos</option>-->
    <!--                                        <option value="LB" label="Lebanon">Lebanon</option>-->
    <!--                                        <option value="MO" label="Macau SAR China">Macau SAR China</option>-->
    <!--                                        <option value="MY" label="Malaysia">Malaysia</option>-->
    <!--                                        <option value="MV" label="Maldives">Maldives</option>-->
    <!--                                        <option value="MN" label="Mongolia">Mongolia</option>-->
    <!--                                        <option value="MM" label="Myanmar [Burma]">Myanmar [Burma]</option>-->
    <!--                                        <option value="NP" label="Nepal">Nepal</option>-->
    <!--                                        <option value="NT" label="Neutral Zone">Neutral Zone</option>-->
    <!--                                        <option value="KP" label="North Korea">North Korea</option>-->
    <!--                                        <option value="OM" label="Oman">Oman</option>-->
    <!--                                        <option value="PK" label="Pakistan">Pakistan</option>-->
    <!--                                        <option value="PS" label="Palestinian Territories">Palestinian Territories</option>-->
    <!--                                        <option value="YD" label="People's Democratic Republic of Yemen">People's Democratic Republic of Yemen</option>-->
    <!--                                        <option value="PH" label="Philippines">Philippines</option>-->
    <!--                                        <option value="QA" label="Qatar">Qatar</option>-->
    <!--                                        <option value="SA" label="Saudi Arabia">Saudi Arabia</option>-->
    <!--                                        <option value="SG" label="Singapore">Singapore</option>-->
    <!--                                        <option value="KR" label="South Korea">South Korea</option>-->
    <!--                                        <option value="LK" label="Sri Lanka">Sri Lanka</option>-->
    <!--                                        <option value="SY" label="Syria">Syria</option>-->
    <!--                                        <option value="TW" label="Taiwan">Taiwan</option>-->
    <!--                                        <option value="TJ" label="Tajikistan">Tajikistan</option>-->
    <!--                                        <option value="TH" label="Thailand">Thailand</option>-->
    <!--                                        <option value="TL" label="Timor-Leste">Timor-Leste</option>-->
    <!--                                        <option value="TR" label="Turkey">Turkey</option>-->
    <!--                                        <option value="™" label="Turkmenistan">Turkmenistan</option>-->
    <!--                                        <option value="AE" label="United Arab Emirates">United Arab Emirates</option>-->
    <!--                                        <option value="UZ" label="Uzbekistan">Uzbekistan</option>-->
    <!--                                        <option value="VN" label="Vietnam">Vietnam</option>-->
    <!--                                        <option value="YE" label="Yemen">Yemen</option>-->
    <!--                                        </optgroup>-->
    <!--                                        <optgroup id="country-optgroup-Europe" label="Europe">-->
    <!--                                        <option value="AL" label="Albania">Albania</option>-->
    <!--                                        <option value="AD" label="Andorra">Andorra</option>-->
    <!--                                        <option value="AT" label="Austria">Austria</option>-->
    <!--                                        <option value="BY" label="Belarus">Belarus</option>-->
    <!--                                        <option value="BE" label="Belgium">Belgium</option>-->
    <!--                                        <option value="BA" label="Bosnia and Herzegovina">Bosnia and Herzegovina</option>-->
    <!--                                        <option value="BG" label="Bulgaria">Bulgaria</option>-->
    <!--                                        <option value="HR" label="Croatia">Croatia</option>-->
    <!--                                        <option value="CY" label="Cyprus">Cyprus</option>-->
    <!--                                        <option value="CZ" label="Czech Republic">Czech Republic</option>-->
    <!--                                        <option value="DK" label="Denmark">Denmark</option>-->
    <!--                                        <option value="DD" label="East Germany">East Germany</option>-->
    <!--                                        <option value="EE" label="Estonia">Estonia</option>-->
    <!--                                        <option value="FO" label="Faroe Islands">Faroe Islands</option>-->
    <!--                                        <option value="FI" label="Finland">Finland</option>-->
    <!--                                        <option value="FR" label="France">France</option>-->
    <!--                                        <option value="DE" label="Germany">Germany</option>-->
    <!--                                        <option value="GI" label="Gibraltar">Gibraltar</option>-->
    <!--                                        <option value="GR" label="Greece">Greece</option>-->
    <!--                                        <option value="GG" label="Guernsey">Guernsey</option>-->
    <!--                                        <option value="HU" label="Hungary">Hungary</option>-->
    <!--                                        <option value="IS" label="Iceland">Iceland</option>-->
    <!--                                        <option value="IE" label="Ireland">Ireland</option>-->
    <!--                                        <option value="IM" label="Isle of Man">Isle of Man</option>-->
    <!--                                        <option value="IT" label="Italy">Italy</option>-->
    <!--                                        <option value="JE" label="Jersey">Jersey</option>-->
    <!--                                        <option value="LV" label="Latvia">Latvia</option>-->
    <!--                                        <option value="LI" label="Liechtenstein">Liechtenstein</option>-->
    <!--                                        <option value="LT" label="Lithuania">Lithuania</option>-->
    <!--                                        <option value="LU" label="Luxembourg">Luxembourg</option>-->
    <!--                                        <option value="MK" label="Macedonia">Macedonia</option>-->
    <!--                                        <option value="MT" label="Malta">Malta</option>-->
    <!--                                        <option value="FX" label="Metropolitan France">Metropolitan France</option>-->
    <!--                                        <option value="MD" label="Moldova">Moldova</option>-->
    <!--                                        <option value="MC" label="Monaco">Monaco</option>-->
    <!--                                        <option value="ME" label="Montenegro">Montenegro</option>-->
    <!--                                        <option value="NL" label="Netherlands">Netherlands</option>-->
    <!--                                        <option value="NO" label="Norway">Norway</option>-->
    <!--                                        <option value="PL" label="Poland">Poland</option>-->
    <!--                                        <option value="PT" label="Portugal">Portugal</option>-->
    <!--                                        <option value="RO" label="Romania">Romania</option>-->
    <!--                                        <option value="RU" label="Russia">Russia</option>-->
    <!--                                        <option value="SM" label="San Marino">San Marino</option>-->
    <!--                                        <option value="RS" label="Serbia">Serbia</option>-->
    <!--                                        <option value="CS" label="Serbia and Montenegro">Serbia and Montenegro</option>-->
    <!--                                        <option value="SK" label="Slovakia">Slovakia</option>-->
    <!--                                        <option value="SI" label="Slovenia">Slovenia</option>-->
    <!--                                        <option value="ES" label="Spain">Spain</option>-->
    <!--                                        <option value="SJ" label="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>-->
    <!--                                        <option value="SE" label="Sweden">Sweden</option>-->
    <!--                                        <option value="CH" label="Switzerland">Switzerland</option>-->
    <!--                                        <option value="UA" label="Ukraine">Ukraine</option>-->
    <!--                                        <option value="SU" label="Union of Soviet Socialist Republics">Union of Soviet Socialist Republics</option>-->
    <!--                                        <option value="GB" label="United Kingdom">United Kingdom</option>-->
    <!--                                        <option value="VA" label="Vatican City">Vatican City</option>-->
    <!--                                        <option value="AX" label="Åland Islands">Åland Islands</option>-->
    <!--                                        </optgroup>-->
    <!--                                        <optgroup id="country-optgroup-Oceania" label="Oceania">-->
    <!--                                        <option value="AS" label="American Samoa">American Samoa</option>-->
    <!--                                        <option value="AQ" label="Antarctica">Antarctica</option>-->
    <!--                                        <option value="AU" label="Australia">Australia</option>-->
    <!--                                        <option value="BV" label="Bouvet Island">Bouvet Island</option>-->
    <!--                                        <option value="IO" label="British Indian Ocean Territory">British Indian Ocean Territory</option>-->
    <!--                                        <option value="CX" label="Christmas Island">Christmas Island</option>-->
    <!--                                        <option value="CC" label="Cocos [Keeling] Islands">Cocos [Keeling] Islands</option>-->
    <!--                                        <option value="CK" label="Cook Islands">Cook Islands</option>-->
    <!--                                        <option value="FJ" label="Fiji">Fiji</option>-->
    <!--                                        <option value="PF" label="French Polynesia">French Polynesia</option>-->
    <!--                                        <option value="TF" label="French Southern Territories">French Southern Territories</option>-->
    <!--                                        <option value="GU" label="Guam">Guam</option>-->
    <!--                                        <option value="HM" label="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>-->
    <!--                                        <option value="KI" label="Kiribati">Kiribati</option>-->
    <!--                                        <option value="MH" label="Marshall Islands">Marshall Islands</option>-->
    <!--                                        <option value="FM" label="Micronesia">Micronesia</option>-->
    <!--                                        <option value="NR" label="Nauru">Nauru</option>-->
    <!--                                        <option value="NC" label="New Caledonia">New Caledonia</option>-->
    <!--                                        <option value="NZ" label="New Zealand">New Zealand</option>-->
    <!--                                        <option value="NU" label="Niue">Niue</option>-->
    <!--                                        <option value="NF" label="Norfolk Island">Norfolk Island</option>-->
    <!--                                        <option value="MP" label="Northern Mariana Islands">Northern Mariana Islands</option>-->
    <!--                                        <option value="PW" label="Palau">Palau</option>-->
    <!--                                        <option value="PG" label="Papua New Guinea">Papua New Guinea</option>-->
    <!--                                        <option value="PN" label="Pitcairn Islands">Pitcairn Islands</option>-->
    <!--                                        <option value="WS" label="Samoa">Samoa</option>-->
    <!--                                        <option value="SB" label="Solomon Islands">Solomon Islands</option>-->
    <!--                                        <option value="GS" label="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>-->
    <!--                                        <option value="TK" label="Tokelau">Tokelau</option>-->
    <!--                                        <option value="TO" label="Tonga">Tonga</option>-->
    <!--                                        <option value="TV" label="Tuvalu">Tuvalu</option>-->
    <!--                                        <option value="UM" label="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option>-->
    <!--                                        <option value="VU" label="Vanuatu">Vanuatu</option>-->
    <!--                                        <option value="WF" label="Wallis and Futuna">Wallis and Futuna</option>-->
    <!--                                        </optgroup>-->
    <!--                                      </select>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">Address</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">City</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">State</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <div class="select-style">-->
    <!--                                      <select class = "input-lg inp1">-->
    <!--                                        <option value="Alabama">Alabama</option>-->
    <!--                                        <option value="Alaska">Alaska</option>-->
    <!--                                        <option value="Arizona">Arizona</option>-->
    <!--                                        <option value="Arkansas">Arkansas</option>-->
    <!--                                        <option value="California">California</option>-->
    <!--                                        <option value="Colorado">Colorado</option>-->
    <!--                                        <option value="Connecticut">Connecticut</option>-->
    <!--                                        <option value="Delaware">Delaware</option>-->
    <!--                                        <option value="District of Columbia">District of Columbia</option>-->
    <!--                                        <option value="Florida">Florida</option>-->
    <!--                                        <option value="Georgia">Georgia</option>-->
    <!--                                        <option value="Guam">Guam</option>-->
    <!--                                        <option value="Hawaii">Hawaii</option>-->
    <!--                                        <option value="Idaho">Idaho</option>-->
    <!--                                        <option value="Illinois">Illinois</option>-->
    <!--                                        <option value="Indiana">Indiana</option>-->
    <!--                                        <option value="Iowa">Iowa</option>-->
    <!--                                        <option value="Kansas">Kansas</option>-->
    <!--                                        <option value="Kentucky">Kentucky</option>-->
    <!--                                        <option value="Louisiana">Louisiana</option>-->
    <!--                                        <option value="Maine">Maine</option>-->
    <!--                                        <option value="Maryland">Maryland</option>-->
    <!--                                        <option value="Massachusetts">Massachusetts</option>-->
    <!--                                        <option value="Michigan">Michigan</option>-->
    <!--                                        <option value="Minnesota">Minnesota</option>-->
    <!--                                        <option value="Mississippi">Mississippi</option>-->
    <!--                                        <option value="Missouri">Missouri</option>-->
    <!--                                        <option value="Montana">Montana</option>-->
    <!--                                        <option value="Nebraska">Nebraska</option>-->
    <!--                                        <option value="Nevada">Nevada</option>-->
    <!--                                        <option value="New Hampshire">New Hampshire</option>-->
    <!--                                        <option value="New Jersey">New Jersey</option>-->
    <!--                                        <option value="New Mexico">New Mexico</option>-->
    <!--                                        <option value="New York">New York</option>-->
    <!--                                        <option value="North Carolina">North Carolina</option>-->
    <!--                                        <option value="North Dakota">North Dakota</option>-->
    <!--                                        <option value="Northern Marianas Islands">Northern Marianas Islands</option>-->
    <!--                                        <option value="Ohio">Ohio</option>-->
    <!--                                        <option value="Oklahoma">Oklahoma</option>-->
    <!--                                        <option value="Oregon">Oregon</option>-->
    <!--                                        <option value="Pennsylvania">Pennsylvania</option>-->
    <!--                                        <option value="Puerto Rico">Puerto Rico</option>-->
    <!--                                        <option value="Rhode Island">Rhode Island</option>-->
    <!--                                        <option value="South Carolina">South Carolina</option>-->
    <!--                                        <option value="South Dakota">South Dakota</option>-->
    <!--                                        <option value="Tennessee">Tennessee</option>-->
    <!--                                        <option value="Texas">Texas</option>-->
    <!--                                        <option value="Utah">Utah</option>-->
    <!--                                        <option value="Vermont">Vermont</option>-->
    <!--                                        <option value="Virginia">Virginia</option>-->
    <!--                                        <option value="Virgin Islands">Virgin Islands</option>-->
    <!--                                        <option value="Washington">Washington</option>-->
    <!--                                        <option value="West Virginia">West Virginia</option>-->
    <!--                                        <option value="Wisconsin">Wisconsin</option>-->
    <!--                                        <option value="Wyoming">Wyoming</option>-->
    <!--                                      </select>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">Zip Code</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">Card Number</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">Expiration Date</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <div class="select-style">-->
    <!--                                      <select class = "input-lg inp1">-->
    <!--                                        <option value="January">January</option>-->
    <!--                                        <option value="Febuary">Febuary</option>-->
    <!--                                        <option value="March">March</option>-->
    <!--                                        <option value="April">April</option>-->
    <!--                                        <option value="May">May</option>-->
    <!--                                        <option value="June">June</option>-->
    <!--                                        <option value="July">July</option>-->
    <!--                                        <option value="August">August</option>-->
    <!--                                        <option value="September">September</option>-->
    <!--                                        <option value="October">October</option>-->
    <!--                                        <option value="November">November</option>-->
    <!--                                        <option value="December">December</option>-->
    <!--                                      </select>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <div class="select-style">-->
    <!--                                      <select class = "input-lg inp1">-->
    <!--                                        <option value="2020">2020</option>-->
    <!--                                        <option value="2019">2019</option>-->
    <!--                                        <option value="2018">2018</option>-->
    <!--                                        <option value="2017">2017</option>-->
    <!--                                        <option value="2016">2016</option>-->
    <!--                                        <option value="2015">2015</option>-->
    <!--                                        <option value="2014">2014</option>-->
    <!--                                        <option value="2013">2013</option>-->
    <!--                                        <option value="2012">2012</option>-->
    <!--                                        <option value="2011">2011</option>-->
    <!--                                        <option value="2010">2010</option>-->
    <!--                                        <option value="2009">2009</option>-->
    <!--                                        <option value="2008">2008</option>-->
    <!--                                        <option value="2007">2007</option>-->
    <!--                                        <option value="2006">2006</option>-->
    <!--                                        <option value="2005">2005</option>-->
    <!--                                        <option value="2004">2004</option>-->
    <!--                                        <option value="2003">2003</option>-->
    <!--                                        <option value="2002">2002</option>-->
    <!--                                        <option value="2001">2001</option>-->
    <!--                                        <option value="2000">2000</option>-->
    <!--                                        <option value="1999">1999</option>-->
    <!--                                        <option value="1998">1998</option>-->
    <!--                                        <option value="1997">1997</option>-->
    <!--                                        <option value="1996">1996</option>-->
    <!--                                        <option value="1995">1995</option>-->
    <!--                                        <option value="1994">1994</option>-->
    <!--                                        <option value="1993">1993</option>-->
    <!--                                        <option value="1992">1992</option>-->
    <!--                                        <option value="1991">1991</option>-->
    <!--                                        <option value="1990">1990</option>-->
    <!--                                        <option value="1989">1989</option>-->
    <!--                                        <option value="1988">1988</option>-->
    <!--                                        <option value="1987">1987</option>-->
    <!--                                        <option value="1986">1986</option>-->
    <!--                                        <option value="1985">1985</option>-->
    <!--                                        <option value="1984">1984</option>-->
    <!--                                        <option value="1983">1983</option>-->
    <!--                                        <option value="1982">1982</option>-->
    <!--                                        <option value="1981">1981</option>-->
    <!--                                        <option value="1980">1980</option>-->
    <!--                                        <option value="1979">1979</option>-->
    <!--                                        <option value="1978">1978</option>-->
    <!--                                        <option value="1977">1977</option>-->
    <!--                                        <option value="1976">1976</option>-->
    <!--                                        <option value="1975">1975</option>-->
    <!--                                        <option value="1974">1974</option>-->
    <!--                                        <option value="1973">1973</option>-->
    <!--                                        <option value="1972">1972</option>-->
    <!--                                        <option value="1971">1971</option>-->
    <!--                                        <option value="1970">1970</option>-->
    <!--                                        <option value="1969">1969</option>-->
    <!--                                        <option value="1968">1968</option>-->
    <!--                                        <option value="1967">1967</option>-->
    <!--                                        <option value="1966">1966</option>-->
    <!--                                        <option value="1965">1965</option>-->
    <!--                                        <option value="1964">1964</option>-->
    <!--                                        <option value="1963">1963</option>-->
    <!--                                        <option value="1962">1962</option>-->
    <!--                                        <option value="1961">1961</option>-->
    <!--                                        <option value="1960">1960</option>-->
    <!--                                        <option value="1959">1959</option>-->
    <!--                                        <option value="1958">1958</option>-->
    <!--                                        <option value="1957">1957</option>-->
    <!--                                        <option value="1956">1956</option>-->
    <!--                                        <option value="1955">1955</option>-->
    <!--                                        <option value="1954">1954</option>-->
    <!--                                        <option value="1953">1953</option>-->
    <!--                                        <option value="1952">1952</option>-->
    <!--                                        <option value="1951">1951</option>-->
    <!--                                        <option value="1950">1950</option>-->
    <!--                                        <option value="1949">1949</option>-->
    <!--                                        <option value="1948">1948</option>-->
    <!--                                        <option value="1947">1947</option>-->
    <!--                                        <option value="1946">1946</option>-->
    <!--                                        <option value="1945">1945</option>-->
    <!--                                        <option value="1944">1944</option>-->
    <!--                                        <option value="1943">1943</option>-->
    <!--                                        <option value="1942">1942</option>-->
    <!--                                        <option value="1941">1941</option>-->
    <!--                                        <option value="1940">1940</option>-->
    <!--                                        <option value="1939">1939</option>-->
    <!--                                        <option value="1938">1938</option>-->
    <!--                                        <option value="1937">1937</option>-->
    <!--                                        <option value="1936">1936</option>-->
    <!--                                        <option value="1935">1935</option>-->
    <!--                                        <option value="1934">1934</option>-->
    <!--                                        <option value="1933">1933</option>-->
    <!--                                        <option value="1932">1932</option>-->
    <!--                                        <option value="1931">1931</option>-->
    <!--                                        <option value="1930">1930</option>-->
    <!--                                      </select>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <label class = "billing-label">CVC</label>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <input type="text" class="account-form-control form-control input-lg inp1" placeholder="CVC" required="">-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                            <div class="col-sm-6">-->
    <!--                                <div class="form-group">-->
    <!--                                    <p class="whats-this-text"><a href = "">What's This</a></p>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class = "row">-->
    <!--                            <div class="col-sm-12">-->
    <!--                                <button type="submit" class="process-payment-btn" data-toggle="modal" data-target="#myModal">Process My Payment</button>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </fieldset>-->
    <!--                </form>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    </div>
    </div>
    </div>
    <script>
        // Create a Stripe client.
        var stripe = Stripe('pk_test_PyLosOqd6JzHC1NeVxJjtnVp');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                fontSize: '14px',
                border: '1px solid #e4e4e4',
                borderRadius: '0px',
                boxShadow: 'none',
                display: 'block',
                color: '#333',
                height: '46px',
                padding: '12px',
                outline: 'none',
                width: '100%',
                fontWeight: '400',
                boxShadow: '0 1px 1px 0 rgba(45, 44, 44, 0.05) !important'
                /*color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                  color: '#aab7c4'
                }*/
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var cardNumber = elements.create('cardNumber', {
            style: style
        });
        var cardExpiry = elements.create('cardExpiry', {
            style: style
        });
        var cardCvc = elements.create('cardCvc', {
            style: style
        });

        // Add an instance of the card Element into the `card-element` <div>.
        cardNumber.mount('#card-element');
        cardExpiry.mount('#expiry-element');
        cardCvc.mount('#cvc-element');

        // Handle real-time validation errors from the card Element.
        cardNumber.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
    <?php include('new_backend_footer.php'); ?>
</body>

</html>