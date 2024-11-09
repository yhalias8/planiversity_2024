<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include('include_doctype.php');
include('include_new_header.php');
?>
<link href="<?= SITE ?>assets/billing/css/mystyle.css?v=20230621" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<section class="what_is_costs_sec" style="padding-top: 150px;">
    <div class="container">
        <div class="what_is_cost_heading">
            <h1>What It Costs</h1>
            <div class="container text-center">
                <div class="custom-controls-stacked d-block my-3">
                    <label class="custom-control border-switch">
                        <span class="border-switch-control-description">For the Month</span>
                        <input type="checkbox" onclick="change_plan(this)" class="border-switch-control-input">
                        <span class="border-switch-control-indicator"></span>
                        <span class="border-switch-control-description">For the Year</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="what_is_cost_custom_price">

            <h3>Flexible Pricing Plans for Business Users</h3>

            <p> Welcome to our customized pricing page designed for business users.
                We understand that each team has unique requirements and varying sizes,
                which is why we offer customizable pricing plans to accommodate your needs.
                Whether you have a small team or an enterprise-level organization, we have the right solution for you .</p>

            <div class="slider-container">
                <img src="<?php echo SITE; ?>assets/images/team-color.png" alt="User Icon" id="userIcon" width="45" height="45">
                <input type="range" min="1" max="500" step="1" value="10" class="price-slider" id="userSlider">
            </div>

            <div class="team-member-block">
                <div class="price-contact">
                    <h5 class="price-info" data-toggle="modal" data-target="#businessModal"> <i class="fa fa-info-circle" aria-hidden="true"></i> Learn More </h5>
                    <h5 class="price-info contact-agent" data-toggle="modal" data-target="#contactAgentModal"> <i class="fa fa-info-circle" aria-hidden="true"></i> Contact Sales </h5>
                </div>
                <h5>Team Member : <span id="member_count">0</span></h5>
            </div>

        </div>        
        
        <div class="what_is_cost_bill_monthly">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-4 col-sm-12 col-12 col">
                    <div class="cost_bill_monthly_items month_package_1">
                        <div class="individual_heading">
                            <h2>For the Individual</h2>
                            <h3><i class="fa fa-usd" aria-hidden="true"></i>3.99&nbsp;<span>/One time use</span></h3>
                            <p class="text-dark">An ideal selection for those who wants to test drive the service and only pay if they choose to export the itinerary.</p>
                        </div>
                        <div class="why_chose_planiversity">
                            <h3>WHY CHOOSE PLANIVERSITY</h3>
                            <ul class="list-unstyled">
                                <li>Build itinerary</li>
                                <li>Create a schedule</li>
                                <li>Add trip notes</li>
                                <li>Locate destination resources</li>
                                <li>Add filters</li>
                                <li>Add unlimited documents</li>
                                <li>Manage travel calendar</li>
                                <li>Create plans for trip destination</li>
                                <li>Create events</li>
                                <li>Smart Travel destination information</li>
                                <li>Google calendar sync </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-4 col-sm-12 col-12 col">
                    <div class="cost_bill_monthly_items month_package_2">
                        <div class="individual_heading">
                            <h2>For the Individual <span>Popular</span></h2>
                            <h3 id="individual-plan"><i class="fa fa-usd" aria-hidden="true"></i>9.99&nbsp;<span>/month</span><span class="popular" style="display: none;">Popular</span></h3>
                            <p class="text-dark">An ideal selection for those individuals seeking the full package of travel planning solutions for a low price.</p>
                        </div>
                        <div class="why_chose_planiversity">
                            <h3>WHY CHOOSE PLANIVERSITY</h3>
                            <ul class="list-unstyled">
                                <li>Build itinerary</li>
                                <li>Create schedule</li>
                                <li>Add trip notes</li>
                                <li>Locate destination resources</li>
                                <li>Add filters</li>
                                <li>Add unlimited documents</li>
                                <li>Manage travel calendar</li>
                                <li>Create plans for trip destination</li>
                                <li>Create events</li>
                                <li>Smart Travel destination information</li>
                                <li>Google calendar sync </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-4 col-sm-12 col-12 col">
                    <div class="cost_bill_monthly_items month_package_3">
                        <div class="individual_heading">
                            <h2>For the Business</h2>
                            <h3 id="business-plan"><i class="fa fa-usd" aria-hidden="true"></i>49.99&nbsp;<span>/month</span></h3>
                            <p>A business oriented travel planning solution package with all the bells and whistles.</p>
                        </div>
                        <div class="why_chose_planiversity">
                            <h3>WHY CHOOSE PLANIVERSITY</h3>
                            <ul class="list-unstyled">
                                <li>Build itinerary</li>
                                <li>Create schedule</li>
                                <li>Add trip notes</li>
                                <li>Locate destination resources and add filters</li>
                                <li>Add unlimited documents</li>
                                <li>Create trip plans for destination</li>
                                <li>Manage travel calendar</li>
                                <li>Smart Travel destination information</li>
                                <li>Create events</li>
                                <li>Create meetings</li>
                                <li>Create job files</li>
                                <li>Add employees and then connect employee to trip</li>
                                <li>Google calendar sync </li>
                                <li>Outlook calendar sync </li>
                                <li>Connect people </li>                                                                
                                <li>Migration function </li>                                    
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="what_is_cost_start_today" style="padding-top: 550px;">
    <div class="container">
        <div class="what_is_cost_start_today_text">
            <p class="text-dark">Planiversity was built to accommodate the individual and business alike, because each comes with their own basic needs. That is why we’ve kept the planning process similar, but added features for the business’s organization. We understand that the common traveler wants things simplified, organized, and affordable, while the businesses need services with a lower price tag and greater amount of utility; something that will add value to their operations and is responsive to their challenges. We offer a service unmatched by the competition, mainly because we have the experience and know the areas where improvement is needed.</p>
            <div class="what_is_cost_start_today_btn">
                <a href="<?php echo SITE; ?>registration">Start Today</a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Sales Modal Start-->
<div class="modal fade modal-blur" id="contactAgentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content agent-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contact Sales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4></h4>
                </div>
                <div class="d-flex flex-column text-center">

                    <form id="contactform" class="payment-form">
                        <div class="form-group">
                            <label>Your Name *</label>
                            <input type="text" class="form-control" name="name" placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <label>Your Email *</label>
                            <input type="email" class="form-control" name="email" placeholder="Your Email">
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownSelectLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="emoji">🇺🇸</span>
                                    </button>
                                    <ul class="dropdown-menu scrollable-menu" aria-labelledby="dropdownSelectLink">
                                        <li class="">
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇸</span>
                                                <span class="country-code sr-only">US</span>
                                                <span class="country-name truncate col-9">United States</span>
                                                <span class="dial-code col-2 text-right">+1</span>
                                                <span class="example-number sr-only">(201) 555-0123</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇧</span>
                                                <span class="country-code sr-only">GB</span>
                                                <span class="country-name truncate col-9">United Kingdom</span>
                                                <span class="dial-code col-2 text-right">+44</span>
                                                <span class="example-number sr-only">07400 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇦</span>
                                                <span class="country-code sr-only">CA</span>
                                                <span class="country-name truncate col-9">Canada</span>
                                                <span class="dial-code col-2 text-right">+1</span>
                                                <span class="example-number sr-only">(204) 234 5678</span>
                                            </button>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇫</span>
                                                <span class="country-code sr-only">AF</span>
                                                <span class="country-name truncate col-9">Afghanistan</span>
                                                <span class="dial-code col-2 text-right">+93</span>
                                                <span class="example-number sr-only">070 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇽</span>
                                                <span class="country-code sr-only">AX</span>
                                                <span class="country-name truncate col-9">Åland Islands</span>
                                                <span class="dial-code col-2 text-right">+358</span>
                                                <span class="example-number sr-only">041 2345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇱</span>
                                                <span class="country-code sr-only">AL</span>
                                                <span class="country-name truncate col-9">Albania (Shqipëri)</span>
                                                <span class="dial-code col-2 text-right">+355</span>
                                                <span class="example-number sr-only">066 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇿</span>
                                                <span class="country-code sr-only">DZ</span>
                                                <span class="country-name truncate col-9">Algeria (‫الجزائر‬‎)</span>
                                                <span class="dial-code col-2 text-right">+213</span>
                                                <span class="example-number sr-only">0551 23 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇸</span>
                                                <span class="country-code sr-only">AS</span>
                                                <span class="country-name truncate col-9">American Samoa</span>
                                                <span class="dial-code col-2 text-right">+1684</span>
                                                <span class="example-number sr-only">(684) 733-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇩</span>
                                                <span class="country-code sr-only">AD</span>
                                                <span class="country-name truncate col-9">Andorra</span>
                                                <span class="dial-code col-2 text-right">+376</span>
                                                <span class="example-number sr-only">312 345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇴</span>
                                                <span class="country-code sr-only">AO</span>
                                                <span class="country-name truncate col-9">Angola</span>
                                                <span class="dial-code col-2 text-right">+244</span>
                                                <span class="example-number sr-only">923 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇮</span>
                                                <span class="country-code sr-only">AI</span>
                                                <span class="country-name truncate col-9">Anguilla</span>
                                                <span class="dial-code col-2 text-right">+1264</span>
                                                <span class="example-number sr-only">(264) 235-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇶</span>
                                                <span class="country-code sr-only">AQ</span>
                                                <span class="country-name truncate col-9">Antarctica</span>
                                                <span class="dial-code col-2 text-right">+672</span>
                                                <span class="example-number sr-only">55 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇬</span>
                                                <span class="country-code sr-only">AG</span>
                                                <span class="country-name truncate col-9">Antigua &amp; Barbuda</span>
                                                <span class="dial-code col-2 text-right">+1268</span>
                                                <span class="example-number sr-only">(268) 464-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇷</span>
                                                <span class="country-code sr-only">AR</span>
                                                <span class="country-name truncate col-9">Argentina</span>
                                                <span class="dial-code col-2 text-right">+54</span>
                                                <span class="example-number sr-only">011 15-2345-6789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇲</span>
                                                <span class="country-code sr-only">AM</span>
                                                <span class="country-name truncate col-9">Armenia (Հայաստան)</span>
                                                <span class="dial-code col-2 text-right">+374</span>
                                                <span class="example-number sr-only">077 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇼</span>
                                                <span class="country-code sr-only">AW</span>
                                                <span class="country-name truncate col-9">Aruba</span>
                                                <span class="dial-code col-2 text-right">+297</span>
                                                <span class="example-number sr-only">560 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇨</span>
                                                <span class="country-code sr-only">AC</span>
                                                <span class="country-name truncate col-9">Ascension Island</span>
                                                <span class="dial-code col-2 text-right">+247</span>
                                                <span class="example-number sr-only">1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇺</span>
                                                <span class="country-code sr-only">AU</span>
                                                <span class="country-name truncate col-9">Australia</span>
                                                <span class="dial-code col-2 text-right">+61</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇹</span>
                                                <span class="country-code sr-only">AT</span>
                                                <span class="country-name truncate col-9">Austria (Österreich)</span>
                                                <span class="dial-code col-2 text-right">+43</span>
                                                <span class="example-number sr-only">0664 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇿</span>
                                                <span class="country-code sr-only">AZ</span>
                                                <span class="country-name truncate col-9">Azerbaijan (Azərbaycan)</span>
                                                <span class="dial-code col-2 text-right">+994</span>
                                                <span class="example-number sr-only">040 123 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇸</span>
                                                <span class="country-code sr-only">BS</span>
                                                <span class="country-name truncate col-9">Bahamas</span>
                                                <span class="dial-code col-2 text-right">+1242</span>
                                                <span class="example-number sr-only">(242) 359-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇭</span>
                                                <span class="country-code sr-only">BH</span>
                                                <span class="country-name truncate col-9">Bahrain (‫البحرين‬‎)</span>
                                                <span class="dial-code col-2 text-right">+973</span>
                                                <span class="example-number sr-only">3600 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇩</span>
                                                <span class="country-code sr-only">BD</span>
                                                <span class="country-name truncate col-9">Bangladesh (বাংলাদেশ)</span>
                                                <span class="dial-code col-2 text-right">+880</span>
                                                <span class="example-number sr-only">01812-345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇧</span>
                                                <span class="country-code sr-only">BB</span>
                                                <span class="country-name truncate col-9">Barbados</span>
                                                <span class="dial-code col-2 text-right">+1246</span>
                                                <span class="example-number sr-only">(246) 250-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇾</span>
                                                <span class="country-code sr-only">BY</span>
                                                <span class="country-name truncate col-9">Belarus (Беларусь)</span>
                                                <span class="dial-code col-2 text-right">+375</span>
                                                <span class="example-number sr-only">8 029 491-19-11</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇪</span>
                                                <span class="country-code sr-only">BE</span>
                                                <span class="country-name truncate col-9">Belgium (België)</span>
                                                <span class="dial-code col-2 text-right">+32</span>
                                                <span class="example-number sr-only">0470 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇿</span>
                                                <span class="country-code sr-only">BZ</span>
                                                <span class="country-name truncate col-9">Belize</span>
                                                <span class="dial-code col-2 text-right">+501</span>
                                                <span class="example-number sr-only">622-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇯</span>
                                                <span class="country-code sr-only">BJ</span>
                                                <span class="country-name truncate col-9">Benin (Bénin)</span>
                                                <span class="dial-code col-2 text-right">+229</span>
                                                <span class="example-number sr-only">622-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇲</span>
                                                <span class="country-code sr-only">BM</span>
                                                <span class="country-name truncate col-9">Bermuda</span>
                                                <span class="dial-code col-2 text-right">+1441</span>
                                                <span class="example-number sr-only">(441) 370-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇹</span>
                                                <span class="country-code sr-only">BT</span>
                                                <span class="country-name truncate col-9">Bhutan (འབྲུག)</span>
                                                <span class="dial-code col-2 text-right">+975</span>
                                                <span class="example-number sr-only">17 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇴</span>
                                                <span class="country-code sr-only">BO</span>
                                                <span class="country-name truncate col-9">Bolivia</span>
                                                <span class="dial-code col-2 text-right">+591</span>
                                                <span class="example-number sr-only">71234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇦</span>
                                                <span class="country-code sr-only">BA</span>
                                                <span class="country-name truncate col-9">Bosnia &amp; Herzegovina (Босна и Херцеговина)</span>
                                                <span class="dial-code col-2 text-right">+387</span>
                                                <span class="example-number sr-only">061 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇼</span>
                                                <span class="country-code sr-only">BW</span>
                                                <span class="country-name truncate col-9">Botswana</span>
                                                <span class="dial-code col-2 text-right">+267</span>
                                                <span class="example-number sr-only">71 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇻</span>
                                                <span class="country-code sr-only">BV</span>
                                                <span class="country-name truncate col-9">Bouvet Island</span>
                                                <span class="dial-code col-2 text-right">+47</span>
                                                <span class="example-number sr-only">406 12 345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇷</span>
                                                <span class="country-code sr-only">BR</span>
                                                <span class="country-name truncate col-9">Brazil (Brasil)</span>
                                                <span class="dial-code col-2 text-right">+55</span>
                                                <span class="example-number sr-only">(11) 96123-4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇴</span>
                                                <span class="country-code sr-only">IO</span>
                                                <span class="country-name truncate col-9">British Indian Ocean Territory</span>
                                                <span class="dial-code col-2 text-right">+246</span>
                                                <span class="example-number sr-only">380 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇬</span>
                                                <span class="country-code sr-only">VG</span>
                                                <span class="country-name truncate col-9">British Virgin Islands</span>
                                                <span class="dial-code col-2 text-right">+1284</span>
                                                <span class="example-number sr-only">(284) 300-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇳</span>
                                                <span class="country-code sr-only">BN</span>
                                                <span class="country-name truncate col-9">Brunei</span>
                                                <span class="dial-code col-2 text-right">+673</span>
                                                <span class="example-number sr-only">712 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇬</span>
                                                <span class="country-code sr-only">BG</span>
                                                <span class="country-name truncate col-9">Bulgaria (България)</span>
                                                <span class="dial-code col-2 text-right">+359</span>
                                                <span class="example-number sr-only">048 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇫</span>
                                                <span class="country-code sr-only">BF</span>
                                                <span class="country-name truncate col-9">Burkina Faso</span>
                                                <span class="dial-code col-2 text-right">+226</span>
                                                <span class="example-number sr-only">70 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇮</span>
                                                <span class="country-code sr-only">BI</span>
                                                <span class="country-name truncate col-9">Burundi (Uburundi)</span>
                                                <span class="dial-code col-2 text-right">+257</span>
                                                <span class="example-number sr-only">79 56 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇭</span>
                                                <span class="country-code sr-only">KH</span>
                                                <span class="country-name truncate col-9">Cambodia (កម្ពុជា)</span>
                                                <span class="dial-code col-2 text-right">+855</span>
                                                <span class="example-number sr-only">091 234 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇲</span>
                                                <span class="country-code sr-only">CM</span>
                                                <span class="country-name truncate col-9">Cameroon (Cameroun)</span>
                                                <span class="dial-code col-2 text-right">+237</span>
                                                <span class="example-number sr-only">6 71 23 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇻</span>
                                                <span class="country-code sr-only">CV</span>
                                                <span class="country-name truncate col-9">Cape Verde (Kabu Verdi)</span>
                                                <span class="dial-code col-2 text-right">+238</span>
                                                <span class="example-number sr-only">991 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇶</span>
                                                <span class="country-code sr-only">BQ</span>
                                                <span class="country-name truncate col-9">Caribbean Netherlands</span>
                                                <span class="dial-code col-2 text-right">+599</span>
                                                <span class="example-number sr-only">318 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇾</span>
                                                <span class="country-code sr-only">KY</span>
                                                <span class="country-name truncate col-9">Cayman Islands</span>
                                                <span class="dial-code col-2 text-right">+1345</span>
                                                <span class="example-number sr-only">(345) 323-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇫</span>
                                                <span class="country-code sr-only">CF</span>
                                                <span class="country-name truncate col-9">Central African Republic (République centrafricaine)</span>
                                                <span class="dial-code col-2 text-right">+236</span>
                                                <span class="example-number sr-only">70 01 23 45</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇩</span>
                                                <span class="country-code sr-only">TD</span>
                                                <span class="country-name truncate col-9">Chad (Tchad)</span>
                                                <span class="dial-code col-2 text-right">+235</span>
                                                <span class="example-number sr-only">63 01 23 45</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇱</span>
                                                <span class="country-code sr-only">CL</span>
                                                <span class="country-name truncate col-9">Chile</span>
                                                <span class="dial-code col-2 text-right">+56</span>
                                                <span class="example-number sr-only">09 6123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇳</span>
                                                <span class="country-code sr-only">CN</span>
                                                <span class="country-name truncate col-9">China (中国)</span>
                                                <span class="dial-code col-2 text-right">+86</span>
                                                <span class="example-number sr-only">131 2345 6789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇽</span>
                                                <span class="country-code sr-only">CX</span>
                                                <span class="country-name truncate col-9">Christmas Island</span>
                                                <span class="dial-code col-2 text-right">+61</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇨</span>
                                                <span class="country-code sr-only">CC</span>
                                                <span class="country-name truncate col-9">Cocos (Keeling) Islands</span>
                                                <span class="dial-code col-2 text-right">+61</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇴</span>
                                                <span class="country-code sr-only">CO</span>
                                                <span class="country-name truncate col-9">Colombia</span>
                                                <span class="dial-code col-2 text-right">+57</span>
                                                <span class="example-number sr-only">321 1234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇲</span>
                                                <span class="country-code sr-only">KM</span>
                                                <span class="country-name truncate col-9">Comoros (‫جزر القمر‬‎)</span>
                                                <span class="dial-code col-2 text-right">+269</span>
                                                <span class="example-number sr-only">321 23 45</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇬</span>
                                                <span class="country-code sr-only">CG</span>
                                                <span class="country-name truncate col-9">Congo (Republic) (Congo-Brazzaville)</span>
                                                <span class="dial-code col-2 text-right">+242</span>
                                                <span class="example-number sr-only">0991 234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇩</span>
                                                <span class="country-code sr-only">CD</span>
                                                <span class="country-name truncate col-9">Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)</span>
                                                <span class="dial-code col-2 text-right">+243</span>
                                                <span class="example-number sr-only">06 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇰</span>
                                                <span class="country-code sr-only">CK</span>
                                                <span class="country-name truncate col-9">Cook Islands</span>
                                                <span class="dial-code col-2 text-right">+682</span>
                                                <span class="example-number sr-only">71 234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇷</span>
                                                <span class="country-code sr-only">CR</span>
                                                <span class="country-name truncate col-9">Costa Rica</span>
                                                <span class="dial-code col-2 text-right">+506</span>
                                                <span class="example-number sr-only">8312 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇮</span>
                                                <span class="country-code sr-only">CI</span>
                                                <span class="country-name truncate col-9">Côte d’Ivoire</span>
                                                <span class="dial-code col-2 text-right">+225</span>
                                                <span class="example-number sr-only">01 23 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇷</span>
                                                <span class="country-code sr-only">HR</span>
                                                <span class="country-name truncate col-9">Croatia (Hrvatska)</span>
                                                <span class="dial-code col-2 text-right">+385</span>
                                                <span class="example-number sr-only">091 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇺</span>
                                                <span class="country-code sr-only">CU</span>
                                                <span class="country-name truncate col-9">Cuba</span>
                                                <span class="dial-code col-2 text-right">+53</span>
                                                <span class="example-number sr-only">05 1234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇼</span>
                                                <span class="country-code sr-only">CW</span>
                                                <span class="country-name truncate col-9">Curaçao</span>
                                                <span class="dial-code col-2 text-right">+599</span>
                                                <span class="example-number sr-only">9 518 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇾</span>
                                                <span class="country-code sr-only">CY</span>
                                                <span class="country-name truncate col-9">Cyprus (Κύπρος)</span>
                                                <span class="dial-code col-2 text-right">+357</span>
                                                <span class="example-number sr-only">96 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇿</span>
                                                <span class="country-code sr-only">CZ</span>
                                                <span class="country-name truncate col-9">Czech Republic (Česká republika)</span>
                                                <span class="dial-code col-2 text-right">+420</span>
                                                <span class="example-number sr-only">601 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇰</span>
                                                <span class="country-code sr-only">DK</span>
                                                <span class="country-name truncate col-9">Denmark (Danmark)</span>
                                                <span class="dial-code col-2 text-right">+45</span>
                                                <span class="example-number sr-only">20 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇬</span>
                                                <span class="country-code sr-only">DG</span>
                                                <span class="country-name truncate col-9">Diego Garcia</span>
                                                <span class="dial-code col-2 text-right">+246</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇯</span>
                                                <span class="country-code sr-only">DJ</span>
                                                <span class="country-name truncate col-9">Djibouti</span>
                                                <span class="dial-code col-2 text-right">+253</span>
                                                <span class="example-number sr-only">77 83 10 01</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇲</span>
                                                <span class="country-code sr-only">DM</span>
                                                <span class="country-name truncate col-9">Dominica</span>
                                                <span class="dial-code col-2 text-right">+1767</span>
                                                <span class="example-number sr-only">(767) 225-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇴</span>
                                                <span class="country-code sr-only">DO</span>
                                                <span class="country-name truncate col-9">Dominican Republic (República Dominicana)</span>
                                                <span class="dial-code col-2 text-right">+1</span>
                                                <span class="example-number sr-only">(809) 234-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇨</span>
                                                <span class="country-code sr-only">EC</span>
                                                <span class="country-name truncate col-9">Ecuador</span>
                                                <span class="dial-code col-2 text-right">+593</span>
                                                <span class="example-number sr-only">099 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇬</span>
                                                <span class="country-code sr-only">EG</span>
                                                <span class="country-name truncate col-9">Egypt (‫مصر‬‎)</span>
                                                <span class="dial-code col-2 text-right">+20</span>
                                                <span class="example-number sr-only">0100 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇻</span>
                                                <span class="country-code sr-only">SV</span>
                                                <span class="country-name truncate col-9">El Salvador</span>
                                                <span class="dial-code col-2 text-right">+503</span>
                                                <span class="example-number sr-only">7012 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇶</span>
                                                <span class="country-code sr-only">GQ</span>
                                                <span class="country-name truncate col-9">Equatorial Guinea (Guinea Ecuatorial)</span>
                                                <span class="dial-code col-2 text-right">+240</span>
                                                <span class="example-number sr-only">222 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇷</span>
                                                <span class="country-code sr-only">ER</span>
                                                <span class="country-name truncate col-9">Eritrea</span>
                                                <span class="dial-code col-2 text-right">+291</span>
                                                <span class="example-number sr-only">07 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇪</span>
                                                <span class="country-code sr-only">EE</span>
                                                <span class="country-name truncate col-9">Estonia (Eesti)</span>
                                                <span class="dial-code col-2 text-right">+372</span>
                                                <span class="example-number sr-only">5123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇹</span>
                                                <span class="country-code sr-only">ET</span>
                                                <span class="country-name truncate col-9">Ethiopia</span>
                                                <span class="dial-code col-2 text-right">+251</span>
                                                <span class="example-number sr-only">091 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇰</span>
                                                <span class="country-code sr-only">FK</span>
                                                <span class="country-name truncate col-9">Falkland Islands (Islas Malvinas)</span>
                                                <span class="dial-code col-2 text-right">+500</span>
                                                <span class="example-number sr-only">51234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇴</span>
                                                <span class="country-code sr-only">FO</span>
                                                <span class="country-name truncate col-9">Faroe Islands (Føroyar)</span>
                                                <span class="dial-code col-2 text-right">+298</span>
                                                <span class="example-number sr-only">211234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇯</span>
                                                <span class="country-code sr-only">FJ</span>
                                                <span class="country-name truncate col-9">Fiji</span>
                                                <span class="dial-code col-2 text-right">+679</span>
                                                <span class="example-number sr-only">701 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇮</span>
                                                <span class="country-code sr-only">FI</span>
                                                <span class="country-name truncate col-9">Finland (Suomi)</span>
                                                <span class="dial-code col-2 text-right">+358</span>
                                                <span class="example-number sr-only">041 2345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇷</span>
                                                <span class="country-code sr-only">FR</span>
                                                <span class="country-name truncate col-9">France</span>
                                                <span class="dial-code col-2 text-right">+33</span>
                                                <span class="example-number sr-only">06 12 34 56 78</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇫</span>
                                                <span class="country-code sr-only">GF</span>
                                                <span class="country-name truncate col-9">French Guiana (Guyane française)</span>
                                                <span class="dial-code col-2 text-right">+594</span>
                                                <span class="example-number sr-only">0694 20 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇫</span>
                                                <span class="country-code sr-only">PF</span>
                                                <span class="country-name truncate col-9">French Polynesia (Polynésie française)</span>
                                                <span class="dial-code col-2 text-right">+689</span>
                                                <span class="example-number sr-only">87 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇦</span>
                                                <span class="country-code sr-only">GA</span>
                                                <span class="country-name truncate col-9">Gabon</span>
                                                <span class="dial-code col-2 text-right">+241</span>
                                                <span class="example-number sr-only">06 03 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇲</span>
                                                <span class="country-code sr-only">GM</span>
                                                <span class="country-name truncate col-9">Gambia</span>
                                                <span class="dial-code col-2 text-right">+220</span>
                                                <span class="example-number sr-only">301 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇪</span>
                                                <span class="country-code sr-only">GE</span>
                                                <span class="country-name truncate col-9">Georgia (საქართველო)</span>
                                                <span class="dial-code col-2 text-right">+995</span>
                                                <span class="example-number sr-only">555 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇩🇪</span>
                                                <span class="country-code sr-only">DE</span>
                                                <span class="country-name truncate col-9">Germany (Deutschland)</span>
                                                <span class="dial-code col-2 text-right">+49</span>
                                                <span class="example-number sr-only">01512 3456789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇭</span>
                                                <span class="country-code sr-only">GH</span>
                                                <span class="country-name truncate col-9">Ghana (Gaana)</span>
                                                <span class="dial-code col-2 text-right">+233</span>
                                                <span class="example-number sr-only">023 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇮</span>
                                                <span class="country-code sr-only">GI</span>
                                                <span class="country-name truncate col-9">Gibraltar</span>
                                                <span class="dial-code col-2 text-right">+350</span>
                                                <span class="example-number sr-only">57123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇷</span>
                                                <span class="country-code sr-only">GR</span>
                                                <span class="country-name truncate col-9">Greece (Ελλάδα)</span>
                                                <span class="dial-code col-2 text-right">+30</span>
                                                <span class="example-number sr-only">691 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇱</span>
                                                <span class="country-code sr-only">GL</span>
                                                <span class="country-name truncate col-9">Greenland (Kalaallit Nunaat)</span>
                                                <span class="dial-code col-2 text-right">+299</span>
                                                <span class="example-number sr-only">22 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇩</span>
                                                <span class="country-code sr-only">GD</span>
                                                <span class="country-name truncate col-9">Grenada</span>
                                                <span class="dial-code col-2 text-right">+1473</span>
                                                <span class="example-number sr-only">(473) 403-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇵</span>
                                                <span class="country-code sr-only">GP</span>
                                                <span class="country-name truncate col-9">Guadeloupe</span>
                                                <span class="dial-code col-2 text-right">+590</span>
                                                <span class="example-number sr-only">0690 30-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇺</span>
                                                <span class="country-code sr-only">GU</span>
                                                <span class="country-name truncate col-9">Guam</span>
                                                <span class="dial-code col-2 text-right">+1671</span>
                                                <span class="example-number sr-only">(671) 300-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇹</span>
                                                <span class="country-code sr-only">GT</span>
                                                <span class="country-name truncate col-9">Guatemala</span>
                                                <span class="dial-code col-2 text-right">+502</span>
                                                <span class="example-number sr-only">5123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇬</span>
                                                <span class="country-code sr-only">GG</span>
                                                <span class="country-name truncate col-9">Guernsey</span>
                                                <span class="dial-code col-2 text-right">+44</span>
                                                <span class="example-number sr-only">07781 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇳</span>
                                                <span class="country-code sr-only">GN</span>
                                                <span class="country-name truncate col-9">Guinea (Guinée)</span>
                                                <span class="dial-code col-2 text-right">+224</span>
                                                <span class="example-number sr-only">601 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇼</span>
                                                <span class="country-code sr-only">GW</span>
                                                <span class="country-name truncate col-9">Guinea-Bissau (Guiné Bissau)</span>
                                                <span class="dial-code col-2 text-right">+245</span>
                                                <span class="example-number sr-only">955 012 345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇬🇾</span>
                                                <span class="country-code sr-only">GY</span>
                                                <span class="country-name truncate col-9">Guyana</span>
                                                <span class="dial-code col-2 text-right">+592</span>
                                                <span class="example-number sr-only">609 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇹</span>
                                                <span class="country-code sr-only">HT</span>
                                                <span class="country-name truncate col-9">Haiti</span>
                                                <span class="dial-code col-2 text-right">+509</span>
                                                <span class="example-number sr-only">34 10 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇲</span>
                                                <span class="country-code sr-only">HM</span>
                                                <span class="country-name truncate col-9">Heard &amp; McDonald Islands</span>
                                                <span class="dial-code col-2 text-right">+672</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇳</span>
                                                <span class="country-code sr-only">HN</span>
                                                <span class="country-name truncate col-9">Honduras</span>
                                                <span class="dial-code col-2 text-right">+504</span>
                                                <span class="example-number sr-only">9123-4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇰</span>
                                                <span class="country-code sr-only">HK</span>
                                                <span class="country-name truncate col-9">Hong Kong (香港)</span>
                                                <span class="dial-code col-2 text-right">+852</span>
                                                <span class="example-number sr-only">5123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇭🇺</span>
                                                <span class="country-code sr-only">HU</span>
                                                <span class="country-name truncate col-9">Hungary (Magyarország)</span>
                                                <span class="dial-code col-2 text-right">+36</span>
                                                <span class="example-number sr-only">(20) 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇸</span>
                                                <span class="country-code sr-only">IS</span>
                                                <span class="country-name truncate col-9">Iceland (Ísland)</span>
                                                <span class="dial-code col-2 text-right">+354</span>
                                                <span class="example-number sr-only">611 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇳</span>
                                                <span class="country-code sr-only">IN</span>
                                                <span class="country-name truncate col-9">India (भारत)</span>
                                                <span class="dial-code col-2 text-right">+91</span>
                                                <span class="example-number sr-only">099876 54321</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇩</span>
                                                <span class="country-code sr-only">ID</span>
                                                <span class="country-name truncate col-9">Indonesia</span>
                                                <span class="dial-code col-2 text-right">+62</span>
                                                <span class="example-number sr-only">0812-345-678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇷</span>
                                                <span class="country-code sr-only">IR</span>
                                                <span class="country-name truncate col-9">Iran (‫ایران‬‎)</span>
                                                <span class="dial-code col-2 text-right">+98</span>
                                                <span class="example-number sr-only">0912 345 6789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇶</span>
                                                <span class="country-code sr-only">IQ</span>
                                                <span class="country-name truncate col-9">Iraq (‫العراق‬‎)</span>
                                                <span class="dial-code col-2 text-right">+964</span>
                                                <span class="example-number sr-only">0791 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇪</span>
                                                <span class="country-code sr-only">IE</span>
                                                <span class="country-name truncate col-9">Ireland</span>
                                                <span class="dial-code col-2 text-right">+353</span>
                                                <span class="example-number sr-only">085 012 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇲</span>
                                                <span class="country-code sr-only">IM</span>
                                                <span class="country-name truncate col-9">Isle of Man</span>
                                                <span class="dial-code col-2 text-right">+44</span>
                                                <span class="example-number sr-only">07924 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇱</span>
                                                <span class="country-code sr-only">IL</span>
                                                <span class="country-name truncate col-9">Israel (‫ישראל‬‎)</span>
                                                <span class="dial-code col-2 text-right">+972</span>
                                                <span class="example-number sr-only">050-123-4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇮🇹</span>
                                                <span class="country-code sr-only">IT</span>
                                                <span class="country-name truncate col-9">Italy (Italia)</span>
                                                <span class="dial-code col-2 text-right">+39</span>
                                                <span class="example-number sr-only">312 345 6789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇯🇲</span>
                                                <span class="country-code sr-only">JM</span>
                                                <span class="country-name truncate col-9">Jamaica</span>
                                                <span class="dial-code col-2 text-right">+1876</span>
                                                <span class="example-number sr-only">(876) 210-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇯🇵</span>
                                                <span class="country-code sr-only">JP</span>
                                                <span class="country-name truncate col-9">Japan (日本)</span>
                                                <span class="dial-code col-2 text-right">+81</span>
                                                <span class="example-number sr-only">090-1234-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇯🇪</span>
                                                <span class="country-code sr-only">JE</span>
                                                <span class="country-name truncate col-9">Jersey</span>
                                                <span class="dial-code col-2 text-right">+44</span>
                                                <span class="example-number sr-only">07797 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇯🇴</span>
                                                <span class="country-code sr-only">JO</span>
                                                <span class="country-name truncate col-9">Jordan (‫الأردن‬‎)</span>
                                                <span class="dial-code col-2 text-right">+962</span>
                                                <span class="example-number sr-only">07 9012 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇿</span>
                                                <span class="country-code sr-only">KZ</span>
                                                <span class="country-name truncate col-9">Kazakhstan (Казахстан)</span>
                                                <span class="dial-code col-2 text-right">+7</span>
                                                <span class="example-number sr-only">8 (771) 000 9998</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇪</span>
                                                <span class="country-code sr-only">KE</span>
                                                <span class="country-name truncate col-9">Kenya</span>
                                                <span class="dial-code col-2 text-right">+254</span>
                                                <span class="example-number sr-only">0712 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇮</span>
                                                <span class="country-code sr-only">KI</span>
                                                <span class="country-name truncate col-9">Kiribati</span>
                                                <span class="dial-code col-2 text-right">+686</span>
                                                <span class="example-number sr-only">72012345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇽🇰</span>
                                                <span class="country-code sr-only">XK</span>
                                                <span class="country-name truncate col-9">Kosovo</span>
                                                <span class="dial-code col-2 text-right">+383</span>
                                                <span class="example-number sr-only"></span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇼</span>
                                                <span class="country-code sr-only">KW</span>
                                                <span class="country-name truncate col-9">Kuwait (‫الكويت‬‎)</span>
                                                <span class="dial-code col-2 text-right">+965</span>
                                                <span class="example-number sr-only">500 12345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇬</span>
                                                <span class="country-code sr-only">KG</span>
                                                <span class="country-name truncate col-9">Kyrgyzstan (Кыргызстан)</span>
                                                <span class="dial-code col-2 text-right">+996</span>
                                                <span class="example-number sr-only">0700 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇦</span>
                                                <span class="country-code sr-only">LA</span>
                                                <span class="country-name truncate col-9">Laos (ລາວ)</span>
                                                <span class="dial-code col-2 text-right">+856</span>
                                                <span class="example-number sr-only">020 23 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇻</span>
                                                <span class="country-code sr-only">LV</span>
                                                <span class="country-name truncate col-9">Latvia (Latvija)</span>
                                                <span class="dial-code col-2 text-right">+371</span>
                                                <span class="example-number sr-only">21 234 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇧</span>
                                                <span class="country-code sr-only">LB</span>
                                                <span class="country-name truncate col-9">Lebanon (‫لبنان‬‎)</span>
                                                <span class="dial-code col-2 text-right">+961</span>
                                                <span class="example-number sr-only">71 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇸</span>
                                                <span class="country-code sr-only">LS</span>
                                                <span class="country-name truncate col-9">Lesotho</span>
                                                <span class="dial-code col-2 text-right">+266</span>
                                                <span class="example-number sr-only">5012 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇷</span>
                                                <span class="country-code sr-only">LR</span>
                                                <span class="country-name truncate col-9">Liberia</span>
                                                <span class="dial-code col-2 text-right">+231</span>
                                                <span class="example-number sr-only">0770 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇾</span>
                                                <span class="country-code sr-only">LY</span>
                                                <span class="country-name truncate col-9">Libya (‫ليبيا‬‎)</span>
                                                <span class="dial-code col-2 text-right">+218</span>
                                                <span class="example-number sr-only">091-2345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇮</span>
                                                <span class="country-code sr-only">LI</span>
                                                <span class="country-name truncate col-9">Liechtenstein</span>
                                                <span class="dial-code col-2 text-right">+423</span>
                                                <span class="example-number sr-only">660 234 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇹</span>
                                                <span class="country-code sr-only">LT</span>
                                                <span class="country-name truncate col-9">Lithuania (Lietuva)</span>
                                                <span class="dial-code col-2 text-right">+370</span>
                                                <span class="example-number sr-only">(8-612) 34567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇺</span>
                                                <span class="country-code sr-only">LU</span>
                                                <span class="country-name truncate col-9">Luxembourg</span>
                                                <span class="dial-code col-2 text-right">+352</span>
                                                <span class="example-number sr-only">628 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇴</span>
                                                <span class="country-code sr-only">MO</span>
                                                <span class="country-name truncate col-9">Macau (澳門)</span>
                                                <span class="dial-code col-2 text-right">+853</span>
                                                <span class="example-number sr-only">6612 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇰</span>
                                                <span class="country-code sr-only">MK</span>
                                                <span class="country-name truncate col-9">Macedonia (FYROM) (Македонија)</span>
                                                <span class="dial-code col-2 text-right">+389</span>
                                                <span class="example-number sr-only">072 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇬</span>
                                                <span class="country-code sr-only">MG</span>
                                                <span class="country-name truncate col-9">Madagascar (Madagasikara)</span>
                                                <span class="dial-code col-2 text-right">+261</span>
                                                <span class="example-number sr-only">032 12 345 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇼</span>
                                                <span class="country-code sr-only">MW</span>
                                                <span class="country-name truncate col-9">Malawi</span>
                                                <span class="dial-code col-2 text-right">+265</span>
                                                <span class="example-number sr-only">0991 23 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇾</span>
                                                <span class="country-code sr-only">MY</span>
                                                <span class="country-name truncate col-9">Malaysia</span>
                                                <span class="dial-code col-2 text-right">+60</span>
                                                <span class="example-number hidden" 012-345-6789></span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇻</span>
                                                <span class="country-code sr-only">MV</span>
                                                <span class="country-name truncate col-9">Maldives</span>
                                                <span class="dial-code col-2 text-right">+960</span>
                                                <span class="example-number sr-only">771-2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇱</span>
                                                <span class="country-code sr-only">ML</span>
                                                <span class="country-name truncate col-9">Mali</span>
                                                <span class="dial-code col-2 text-right">+223</span>
                                                <span class="example-number sr-only">65 01 23 45</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇹</span>
                                                <span class="country-code sr-only">MT</span>
                                                <span class="country-name truncate col-9">Malta</span>
                                                <span class="dial-code col-2 text-right">+356</span>
                                                <span class="example-number sr-only">9696 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇭</span>
                                                <span class="country-code sr-only">MH</span>
                                                <span class="country-name truncate col-9">Marshall Islands</span>
                                                <span class="dial-code col-2 text-right">+692</span>
                                                <span class="example-number sr-only">235-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇶</span>
                                                <span class="country-code sr-only">MQ</span>
                                                <span class="country-name truncate col-9">Martinique</span>
                                                <span class="dial-code col-2 text-right">+596</span>
                                                <span class="example-number sr-only">0696 20 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇷</span>
                                                <span class="country-code sr-only">MR</span>
                                                <span class="country-name truncate col-9">Mauritania (‫موريتانيا‬‎)</span>
                                                <span class="dial-code col-2 text-right">+222</span>
                                                <span class="example-number sr-only">22 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇺</span>
                                                <span class="country-code sr-only">MU</span>
                                                <span class="country-name truncate col-9">Mauritius (Moris)</span>
                                                <span class="dial-code col-2 text-right">+230</span>
                                                <span class="example-number sr-only">5251 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇾🇹</span>
                                                <span class="country-code sr-only">YT</span>
                                                <span class="country-name truncate col-9">Mayotte</span>
                                                <span class="dial-code col-2 text-right">+262</span>
                                                <span class="example-number sr-only">0639 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇽</span>
                                                <span class="country-code sr-only">MX</span>
                                                <span class="country-name truncate col-9">Mexico (México)</span>
                                                <span class="dial-code col-2 text-right">+52</span>
                                                <span class="example-number sr-only">044 22 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇫🇲</span>
                                                <span class="country-code sr-only">FM</span>
                                                <span class="country-name truncate col-9">Micronesia</span>
                                                <span class="dial-code col-2 text-right">+691</span>
                                                <span class="example-number sr-only">350 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇩</span>
                                                <span class="country-code sr-only">MD</span>
                                                <span class="country-name truncate col-9">Moldova (Republica Moldova)</span>
                                                <span class="dial-code col-2 text-right">+373</span>
                                                <span class="example-number sr-only">0621 12 345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇨</span>
                                                <span class="country-code sr-only">MC</span>
                                                <span class="country-name truncate col-9">Monaco</span>
                                                <span class="dial-code col-2 text-right">+377</span>
                                                <span class="example-number sr-only">06 12 34 56 78</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇳</span>
                                                <span class="country-code sr-only">MN</span>
                                                <span class="country-name truncate col-9">Mongolia (Монгол)</span>
                                                <span class="dial-code col-2 text-right">+976</span>
                                                <span class="example-number sr-only">8812 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇪</span>
                                                <span class="country-code sr-only">ME</span>
                                                <span class="country-name truncate col-9">Montenegro (Crna Gora)</span>
                                                <span class="dial-code col-2 text-right">+382</span>
                                                <span class="example-number sr-only">067 622 901</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇸</span>
                                                <span class="country-code sr-only">MS</span>
                                                <span class="country-name truncate col-9">Montserrat</span>
                                                <span class="dial-code col-2 text-right">+1664</span>
                                                <span class="example-number sr-only">(664) 492-3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇦</span>
                                                <span class="country-code sr-only">MA</span>
                                                <span class="country-name truncate col-9">Morocco (‫المغرب‬‎)</span>
                                                <span class="dial-code col-2 text-right">+212</span>
                                                <span class="example-number sr-only">0650-123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇿</span>
                                                <span class="country-code sr-only">MZ</span>
                                                <span class="country-name truncate col-9">Mozambique (Moçambique)</span>
                                                <span class="dial-code col-2 text-right">+258</span>
                                                <span class="example-number sr-only">82 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇲</span>
                                                <span class="country-code sr-only">MM</span>
                                                <span class="country-name truncate col-9">Myanmar (Burma) (မြန်မာ)</span>
                                                <span class="dial-code col-2 text-right">+95</span>
                                                <span class="example-number sr-only">09 212 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇦</span>
                                                <span class="country-code sr-only">NA</span>
                                                <span class="country-name truncate col-9">Namibia (Namibië)</span>
                                                <span class="dial-code col-2 text-right">+264</span>
                                                <span class="example-number sr-only">081 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇷</span>
                                                <span class="country-code sr-only">NR</span>
                                                <span class="country-name truncate col-9">Nauru</span>
                                                <span class="dial-code col-2 text-right">+674</span>
                                                <span class="example-number sr-only">555 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇵</span>
                                                <span class="country-code sr-only">NP</span>
                                                <span class="country-name truncate col-9">Nepal (नेपाल)</span>
                                                <span class="dial-code col-2 text-right">+977</span>
                                                <span class="example-number sr-only">984-1234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇱</span>
                                                <span class="country-code sr-only">NL</span>
                                                <span class="country-name truncate col-9">Netherlands (Nederland)</span>
                                                <span class="dial-code col-2 text-right">+31</span>
                                                <span class="example-number sr-only">06 12345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇨</span>
                                                <span class="country-code sr-only">NC</span>
                                                <span class="country-name truncate col-9">New Caledonia (Nouvelle-Calédonie)</span>
                                                <span class="dial-code col-2 text-right">+687</span>
                                                <span class="example-number sr-only">75.12.34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇿</span>
                                                <span class="country-code sr-only">NZ</span>
                                                <span class="country-name truncate col-9">New Zealand</span>
                                                <span class="dial-code col-2 text-right">+64</span>
                                                <span class="example-number sr-only">021 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇮</span>
                                                <span class="country-code sr-only">NI</span>
                                                <span class="country-name truncate col-9">Nicaragua</span>
                                                <span class="dial-code col-2 text-right">+505</span>
                                                <span class="example-number sr-only">8123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇪</span>
                                                <span class="country-code sr-only">NE</span>
                                                <span class="country-name truncate col-9">Niger (Nijar)</span>
                                                <span class="dial-code col-2 text-right">+227</span>
                                                <span class="example-number sr-only">93 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇬</span>
                                                <span class="country-code sr-only">NG</span>
                                                <span class="country-name truncate col-9">Nigeria</span>
                                                <span class="dial-code col-2 text-right">+234</span>
                                                <span class="example-number sr-only">0802 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇺</span>
                                                <span class="country-code sr-only">NU</span>
                                                <span class="country-name truncate col-9">Niue</span>
                                                <span class="dial-code col-2 text-right">+683</span>
                                                <span class="example-number sr-only">1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇫</span>
                                                <span class="country-code sr-only">NF</span>
                                                <span class="country-name truncate col-9">Norfolk Island</span>
                                                <span class="dial-code col-2 text-right">+672</span>
                                                <span class="example-number sr-only">3 81234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇵</span>
                                                <span class="country-code sr-only">MP</span>
                                                <span class="country-name truncate col-9">Northern Mariana Islands</span>
                                                <span class="dial-code col-2 text-right">+1670</span>
                                                <span class="example-number sr-only">(670) 234-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇵</span>
                                                <span class="country-code sr-only">KP</span>
                                                <span class="country-name truncate col-9">North Korea (조선 민주주의 인민 공화국)</span>
                                                <span class="dial-code col-2 text-right">+850</span>
                                                <span class="example-number sr-only">0192 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇳🇴</span>
                                                <span class="country-code sr-only">NO</span>
                                                <span class="country-name truncate col-9">Norway (Norge)</span>
                                                <span class="dial-code col-2 text-right">+47</span>
                                                <span class="example-number sr-only">406 12 345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇴🇲</span>
                                                <span class="country-code sr-only">OM</span>
                                                <span class="country-name truncate col-9">Oman (‫عُمان‬‎)</span>
                                                <span class="dial-code col-2 text-right">+968</span>
                                                <span class="example-number sr-only">9212 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇰</span>
                                                <span class="country-code sr-only">PK</span>
                                                <span class="country-name truncate col-9">Pakistan (‫پاکستان‬‎)</span>
                                                <span class="dial-code col-2 text-right">+92</span>
                                                <span class="example-number sr-only">0301 2345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇼</span>
                                                <span class="country-code sr-only">PW</span>
                                                <span class="country-name truncate col-9">Palau</span>
                                                <span class="dial-code col-2 text-right">+680</span>
                                                <span class="example-number sr-only">620 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇸</span>
                                                <span class="country-code sr-only">PS</span>
                                                <span class="country-name truncate col-9">Palestinian Territories (‫فلسطين‬‎)</span>
                                                <span class="dial-code col-2 text-right">+970</span>
                                                <span class="example-number sr-only">0599 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇦</span>
                                                <span class="country-code sr-only">PA</span>
                                                <span class="country-name truncate col-9">Panama (Panamá)</span>
                                                <span class="dial-code col-2 text-right">+507</span>
                                                <span class="example-number sr-only">6001-2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇬</span>
                                                <span class="country-code sr-only">PG</span>
                                                <span class="country-name truncate col-9">Papua New Guinea</span>
                                                <span class="dial-code col-2 text-right">+675</span>
                                                <span class="example-number sr-only">681 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇾</span>
                                                <span class="country-code sr-only">PY</span>
                                                <span class="country-name truncate col-9">Paraguay</span>
                                                <span class="dial-code col-2 text-right">+595</span>
                                                <span class="example-number sr-only">0961 456789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇪</span>
                                                <span class="country-code sr-only">PE</span>
                                                <span class="country-name truncate col-9">Peru (Perú)</span>
                                                <span class="dial-code col-2 text-right">+51</span>
                                                <span class="example-number sr-only">912 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇭</span>
                                                <span class="country-code sr-only">PH</span>
                                                <span class="country-name truncate col-9">Philippines</span>
                                                <span class="dial-code col-2 text-right">+63</span>
                                                <span class="example-number sr-only">0905 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇳</span>
                                                <span class="country-code sr-only">PN</span>
                                                <span class="country-name truncate col-9">Pitcairn Islands</span>
                                                <span class="dial-code col-2 text-right">+672</span>
                                                <span class="example-number sr-only">0412 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇱</span>
                                                <span class="country-code sr-only">PL</span>
                                                <span class="country-name truncate col-9">Poland (Polska)</span>
                                                <span class="dial-code col-2 text-right">+48</span>
                                                <span class="example-number sr-only">512 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇹</span>
                                                <span class="country-code sr-only">PT</span>
                                                <span class="country-name truncate col-9">Portugal</span>
                                                <span class="dial-code col-2 text-right">+351</span>
                                                <span class="example-number sr-only">912 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇷</span>
                                                <span class="country-code sr-only">PR</span>
                                                <span class="country-name truncate col-9">Puerto Rico</span>
                                                <span class="dial-code col-2 text-right">+1</span>
                                                <span class="example-number sr-only">(787) 234-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇶🇦</span>
                                                <span class="country-code sr-only">QA</span>
                                                <span class="country-name truncate col-9">Qatar (‫قطر‬‎)</span>
                                                <span class="dial-code col-2 text-right">+974</span>
                                                <span class="example-number sr-only">3312 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇷🇪</span>
                                                <span class="country-code sr-only">RE</span>
                                                <span class="country-name truncate col-9">Réunion (La Réunion)</span>
                                                <span class="dial-code col-2 text-right">+262</span>
                                                <span class="example-number sr-only">0692 12 34 56</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇷🇴</span>
                                                <span class="country-code sr-only">RO</span>
                                                <span class="country-name truncate col-9">Romania (România)</span>
                                                <span class="dial-code col-2 text-right">+40</span>
                                                <span class="example-number sr-only">0712 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇷🇺</span>
                                                <span class="country-code sr-only">RU</span>
                                                <span class="country-name truncate col-9">Russia (Россия)</span>
                                                <span class="dial-code col-2 text-right">+7</span>
                                                <span class="example-number sr-only">8 (912) 345-67-89</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇷🇼</span>
                                                <span class="country-code sr-only">RW</span>
                                                <span class="country-name truncate col-9">Rwanda</span>
                                                <span class="dial-code col-2 text-right">+250</span>
                                                <span class="example-number sr-only">0720 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇼🇸</span>
                                                <span class="country-code sr-only">WS</span>
                                                <span class="country-name truncate col-9">Samoa</span>
                                                <span class="dial-code col-2 text-right">+685</span>
                                                <span class="example-number sr-only">601234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇲</span>
                                                <span class="country-code sr-only">SM</span>
                                                <span class="country-name truncate col-9">San Marino</span>
                                                <span class="dial-code col-2 text-right">+378</span>
                                                <span class="example-number sr-only">66 66 12 12</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇹</span>
                                                <span class="country-code sr-only">ST</span>
                                                <span class="country-name truncate col-9">São Tomé &amp; Príncipe (São Tomé e Príncipe)</span>
                                                <span class="dial-code col-2 text-right">+239</span>
                                                <span class="example-number sr-only">981 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇦</span>
                                                <span class="country-code sr-only">SA</span>
                                                <span class="country-name truncate col-9">Saudi Arabia (‫المملكة العربية السعودية‬‎)</span>
                                                <span class="dial-code col-2 text-right">+966</span>
                                                <span class="example-number sr-only">051 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇳</span>
                                                <span class="country-code sr-only">SN</span>
                                                <span class="country-name truncate col-9">Senegal (Sénégal)</span>
                                                <span class="dial-code col-2 text-right">+221</span>
                                                <span class="example-number sr-only">70 123 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇷🇸</span>
                                                <span class="country-code sr-only">RS</span>
                                                <span class="country-name truncate col-9">Serbia (Србија)</span>
                                                <span class="dial-code col-2 text-right">+381</span>
                                                <span class="example-number sr-only">060 1234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇨</span>
                                                <span class="country-code sr-only">SC</span>
                                                <span class="country-name truncate col-9">Seychelles</span>
                                                <span class="dial-code col-2 text-right">+248</span>
                                                <span class="example-number sr-only">2 510 123</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇱</span>
                                                <span class="country-code sr-only">SL</span>
                                                <span class="country-name truncate col-9">Sierra Leone</span>
                                                <span class="dial-code col-2 text-right">+232</span>
                                                <span class="example-number sr-only">(025) 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇬</span>
                                                <span class="country-code sr-only">SG</span>
                                                <span class="country-name truncate col-9">Singapore</span>
                                                <span class="dial-code col-2 text-right">+65</span>
                                                <span class="example-number sr-only">8123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇽</span>
                                                <span class="country-code sr-only">SX</span>
                                                <span class="country-name truncate col-9">Sint Maarten</span>
                                                <span class="dial-code col-2 text-right">+1721</span>
                                                <span class="example-number sr-only">(721) 520-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇰</span>
                                                <span class="country-code sr-only">SK</span>
                                                <span class="country-name truncate col-9">Slovakia (Slovensko)</span>
                                                <span class="dial-code col-2 text-right">+421</span>
                                                <span class="example-number sr-only">0912 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇮</span>
                                                <span class="country-code sr-only">SI</span>
                                                <span class="country-name truncate col-9">Slovenia (Slovenija)</span>
                                                <span class="dial-code col-2 text-right">+386</span>
                                                <span class="example-number sr-only">031 234 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇧</span>
                                                <span class="country-code sr-only">SB</span>
                                                <span class="country-name truncate col-9">Solomon Islands</span>
                                                <span class="dial-code col-2 text-right">+677</span>
                                                <span class="example-number sr-only">74 21234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇴</span>
                                                <span class="country-code sr-only">SO</span>
                                                <span class="country-name truncate col-9">Somalia (Soomaaliya)</span>
                                                <span class="dial-code col-2 text-right">+252</span>
                                                <span class="example-number sr-only">7 1123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇿🇦</span>
                                                <span class="country-code sr-only">ZA</span>
                                                <span class="country-name truncate col-9">South Africa</span>
                                                <span class="dial-code col-2 text-right">+27</span>
                                                <span class="example-number sr-only">071 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇷</span>
                                                <span class="country-code sr-only">KR</span>
                                                <span class="country-name truncate col-9">South Korea (대한민국)</span>
                                                <span class="dial-code col-2 text-right">+82</span>
                                                <span class="example-number sr-only">010-0000-0000</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇸</span>
                                                <span class="country-code sr-only">SS</span>
                                                <span class="country-name truncate col-9">South Sudan (‫جنوب السودان‬‎)</span>
                                                <span class="dial-code col-2 text-right">+211</span>
                                                <span class="example-number sr-only">0977 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇸</span>
                                                <span class="country-code sr-only">ES</span>
                                                <span class="country-name truncate col-9">Spain (España)</span>
                                                <span class="dial-code col-2 text-right">+34</span>
                                                <span class="example-number sr-only">612 34 56 78</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇰</span>
                                                <span class="country-code sr-only">LK</span>
                                                <span class="country-name truncate col-9">Sri Lanka (ශ්‍රී ලංකාව)</span>
                                                <span class="dial-code col-2 text-right">+94</span>
                                                <span class="example-number sr-only">071 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇧🇱</span>
                                                <span class="country-code sr-only">BL</span>
                                                <span class="country-name truncate col-9">St. Barthélemy (Saint-Barthélemy)</span>
                                                <span class="dial-code col-2 text-right">+590</span>
                                                <span class="example-number sr-only">0690 30-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇭</span>
                                                <span class="country-code sr-only">SH</span>
                                                <span class="country-name truncate col-9">St. Helena</span>
                                                <span class="dial-code col-2 text-right">+290</span>
                                                <span class="example-number sr-only">2 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇰🇳</span>
                                                <span class="country-code sr-only">KN</span>
                                                <span class="country-name truncate col-9">St. Kitts &amp; Nevis</span>
                                                <span class="dial-code col-2 text-right">+1869</span>
                                                <span class="example-number sr-only">(869) 765-2917</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇱🇨</span>
                                                <span class="country-code sr-only">LC</span>
                                                <span class="country-name truncate col-9">St. Lucia</span>
                                                <span class="dial-code col-2 text-right">+1758</span>
                                                <span class="example-number sr-only">(758) 284-5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇲🇫</span>
                                                <span class="country-code sr-only">MF</span>
                                                <span class="country-name truncate col-9">St. Martin (Saint-Martin (partie française))</span>
                                                <span class="dial-code col-2 text-right">+590</span>
                                                <span class="example-number sr-only">0690 30-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇵🇲</span>
                                                <span class="country-code sr-only">PM</span>
                                                <span class="country-name truncate col-9">St. Pierre &amp; Miquelon (Saint-Pierre-et-Miquelon)</span>
                                                <span class="dial-code col-2 text-right">+508</span>
                                                <span class="example-number sr-only">055 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇨</span>
                                                <span class="country-code sr-only">VC</span>
                                                <span class="country-name truncate col-9">St. Vincent &amp; the Grenadines</span>
                                                <span class="dial-code col-2 text-right">+1784</span>
                                                <span class="example-number sr-only">(784) 430-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇩</span>
                                                <span class="country-code sr-only">SD</span>
                                                <span class="country-name truncate col-9">Sudan (‫السودان‬‎)</span>
                                                <span class="dial-code col-2 text-right">+249</span>
                                                <span class="example-number sr-only">091 123 1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇷</span>
                                                <span class="country-code sr-only">SR</span>
                                                <span class="country-name truncate col-9">Suriname</span>
                                                <span class="dial-code col-2 text-right">+597</span>
                                                <span class="example-number sr-only">741-2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇯</span>
                                                <span class="country-code sr-only">SJ</span>
                                                <span class="country-name truncate col-9">Svalbard &amp; Jan Mayen</span>
                                                <span class="dial-code col-2 text-right">+47</span>
                                                <span class="example-number sr-only">412 34 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇿</span>
                                                <span class="country-code sr-only">SZ</span>
                                                <span class="country-name truncate col-9">Swaziland</span>
                                                <span class="dial-code col-2 text-right">+268</span>
                                                <span class="example-number sr-only">7612 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇪</span>
                                                <span class="country-code sr-only">SE</span>
                                                <span class="country-name truncate col-9">Sweden (Sverige)</span>
                                                <span class="dial-code col-2 text-right">+46</span>
                                                <span class="example-number sr-only">070-123 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇨🇭</span>
                                                <span class="country-code sr-only">CH</span>
                                                <span class="country-name truncate col-9">Switzerland (Schweiz)</span>
                                                <span class="dial-code col-2 text-right">+41</span>
                                                <span class="example-number sr-only">078 123 45 67</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇸🇾</span>
                                                <span class="country-code sr-only">SY</span>
                                                <span class="country-name truncate col-9">Syria (‫سوريا‬‎)</span>
                                                <span class="dial-code col-2 text-right">+963</span>
                                                <span class="example-number sr-only">0944 567 890</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇼</span>
                                                <span class="country-code sr-only">TW</span>
                                                <span class="country-name truncate col-9">Taiwan (台灣)</span>
                                                <span class="dial-code col-2 text-right">+886</span>
                                                <span class="example-number sr-only">0912 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇯</span>
                                                <span class="country-code sr-only">TJ</span>
                                                <span class="country-name truncate col-9">Tajikistan</span>
                                                <span class="dial-code col-2 text-right">+992</span>
                                                <span class="example-number sr-only">(8) 917 12 3456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇿</span>
                                                <span class="country-code sr-only">TZ</span>
                                                <span class="country-name truncate col-9">Tanzania</span>
                                                <span class="dial-code col-2 text-right">+255</span>
                                                <span class="example-number sr-only">0621 234 567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇭</span>
                                                <span class="country-code sr-only">TH</span>
                                                <span class="country-name truncate col-9">Thailand (ไทย)</span>
                                                <span class="dial-code col-2 text-right">+66</span>
                                                <span class="example-number sr-only">081 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇱</span>
                                                <span class="country-code sr-only">TL</span>
                                                <span class="country-name truncate col-9">Timor-Leste</span>
                                                <span class="dial-code col-2 text-right">+670</span>
                                                <span class="example-number sr-only">7721 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇬</span>
                                                <span class="country-code sr-only">TG</span>
                                                <span class="country-name truncate col-9">Togo</span>
                                                <span class="dial-code col-2 text-right">+228</span>
                                                <span class="example-number sr-only">90 11 23 45</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇰</span>
                                                <span class="country-code sr-only">TK</span>
                                                <span class="country-name truncate col-9">Tokelau</span>
                                                <span class="dial-code col-2 text-right">+690</span>
                                                <span class="example-number sr-only">7290</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇴</span>
                                                <span class="country-code sr-only">TO</span>
                                                <span class="country-name truncate col-9">Tonga</span>
                                                <span class="dial-code col-2 text-right">+676</span>
                                                <span class="example-number sr-only">771 5123</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇹</span>
                                                <span class="country-code sr-only">TT</span>
                                                <span class="country-name truncate col-9">Trinidad &amp; Tobago</span>
                                                <span class="dial-code col-2 text-right">+1868</span>
                                                <span class="example-number sr-only">(868) 291-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇳</span>
                                                <span class="country-code sr-only">TN</span>
                                                <span class="country-name truncate col-9">Tunisia (‫تونس‬‎)</span>
                                                <span class="dial-code col-2 text-right">+216</span>
                                                <span class="example-number sr-only">20 123 456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇷</span>
                                                <span class="country-code sr-only">TR</span>
                                                <span class="country-name truncate col-9">Turkey (Türkiye)</span>
                                                <span class="dial-code col-2 text-right">+90</span>
                                                <span class="example-number sr-only">0501 234 5678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇲</span>
                                                <span class="country-code sr-only">TM</span>
                                                <span class="country-name truncate col-9">Turkmenistan</span>
                                                <span class="dial-code col-2 text-right">+993</span>
                                                <span class="example-number sr-only">8 66 123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇨</span>
                                                <span class="country-code sr-only">TC</span>
                                                <span class="country-name truncate col-9">Turks &amp; Caicos Islands</span>
                                                <span class="dial-code col-2 text-right">+1649</span>
                                                <span class="example-number sr-only">(649) 213-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇹🇻</span>
                                                <span class="country-code sr-only">TV</span>
                                                <span class="country-name truncate col-9">Tuvalu</span>
                                                <span class="dial-code col-2 text-right">+688</span>
                                                <span class="example-number sr-only">901234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇬</span>
                                                <span class="country-code sr-only">UG</span>
                                                <span class="country-name truncate col-9">Uganda</span>
                                                <span class="dial-code col-2 text-right">+256</span>
                                                <span class="example-number sr-only">0712 345678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇦</span>
                                                <span class="country-code sr-only">UA</span>
                                                <span class="country-name truncate col-9">Ukraine (Україна)</span>
                                                <span class="dial-code col-2 text-right">+380</span>
                                                <span class="example-number sr-only">039 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇦🇪</span>
                                                <span class="country-code sr-only">AE</span>
                                                <span class="country-name truncate col-9">United Arab Emirates (‫الإمارات العربية المتحدة‬‎)</span>
                                                <span class="dial-code col-2 text-right">+971</span>
                                                <span class="example-number sr-only">050 123 4567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇾</span>
                                                <span class="country-code sr-only">UY</span>
                                                <span class="country-name truncate col-9">Uruguay</span>
                                                <span class="dial-code col-2 text-right">+598</span>
                                                <span class="example-number sr-only">094 231 234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇲</span>
                                                <span class="country-code sr-only">UM</span>
                                                <span class="country-name truncate col-9">U.S. Outlying Islands</span>
                                                <span class="dial-code col-2 text-right">+1</span>
                                                <span class="example-number sr-only">(201) 555-0123</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇮</span>
                                                <span class="country-code sr-only">VI</span>
                                                <span class="country-name truncate col-9">U.S. Virgin Islands</span>
                                                <span class="dial-code col-2 text-right">+1340</span>
                                                <span class="example-number sr-only">(340) 642-1234</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇺🇿</span>
                                                <span class="country-code sr-only">UZ</span>
                                                <span class="country-name truncate col-9">Uzbekistan (Oʻzbekiston)</span>
                                                <span class="dial-code col-2 text-right">+998</span>
                                                <span class="example-number sr-only">8 91 234 56 78</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇺</span>
                                                <span class="country-code sr-only">VU</span>
                                                <span class="country-name truncate col-9">Vanuatu</span>
                                                <span class="dial-code col-2 text-right">+678</span>
                                                <span class="example-number sr-only">591 2345</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇦</span>
                                                <span class="country-code sr-only">VA</span>
                                                <span class="country-name truncate col-9">Vatican City (Città del Vaticano)</span>
                                                <span class="dial-code col-2 text-right">+39</span>
                                                <span class="example-number sr-only">312 345 6789</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇪</span>
                                                <span class="country-code sr-only">VE</span>
                                                <span class="country-name truncate col-9">Venezuela</span>
                                                <span class="dial-code col-2 text-right">+58</span>
                                                <span class="example-number sr-only">0412-1234567</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇻🇳</span>
                                                <span class="country-code sr-only">VN</span>
                                                <span class="country-name truncate col-9">Vietnam (Việt Nam)</span>
                                                <span class="dial-code col-2 text-right">+84</span>
                                                <span class="example-number sr-only">091 234 56 78</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇼🇫</span>
                                                <span class="country-code sr-only">WF</span>
                                                <span class="country-name truncate col-9">Wallis &amp; Futuna</span>
                                                <span class="dial-code col-2 text-right">+681</span>
                                                <span class="example-number sr-only">50 12 34</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇪🇭</span>
                                                <span class="country-code sr-only">EH</span>
                                                <span class="country-name truncate col-9">Western Sahara (‫الصحراء الغربية‬‎)</span>
                                                <span class="dial-code col-2 text-right">+212</span>
                                                <span class="example-number sr-only">0650-123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇾🇪</span>
                                                <span class="country-code sr-only">YE</span>
                                                <span class="country-name truncate col-9">Yemen (‫اليمن‬‎)</span>
                                                <span class="dial-code col-2 text-right">+967</span>
                                                <span class="example-number sr-only">0712 345 678</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇿🇲</span>
                                                <span class="country-code sr-only">ZM</span>
                                                <span class="country-name truncate col-9">Zambia</span>
                                                <span class="dial-code col-2 text-right">+260</span>
                                                <span class="example-number sr-only">095 5123456</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                <span class="flag-emoji col-1">🇿🇼</span>
                                                <span class="country-code sr-only">ZW</span>
                                                <span class="country-name truncate col-9">Zimbabwe</span>
                                                <span class="dial-code col-2 text-right">+263</span>
                                                <span class="example-number sr-only">071 123 4567</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <span class="input-group-addon">+1</span>
                                <input type="text" class="form-control user-mobile" name="mobile" placeholder="(555) 123-4567">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                                <div class="form-group">
                                    <label>Country *</label>
                                    <select id="country" name="country" class="form-control">
                                        <option value="">Select a country</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                                <div class="form-group">
                                    <label>State *</label>
                                    <select id="state" name="state" class="form-control">
                                        <option value="">Select a state</option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">
                            <label>Your Message *</label>
                            <textarea class="form-control" name="message" placeholder="Your Message" rows="3"></textarea>
                        </div>

                        <input type="hidden" name="country_code" id="country_code" value="<?= "+1"; ?>" readonly>
                        <button type="submit" class="btn btn-info btn-block btn-round process_button">Send request</button>
                    </form>

                    <div class="text-center text-muted delimiter"></div>
                    <div class="d-flex justify-content-center social-buttons">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Contact Sales Modal End-->

<!-- Business Modal Start-->
<div class="modal" id="businessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Business Pricing Breakdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body price-modal-body">

                <h6>Our pricing structure is simple and transparent, allowing you to choose the plan that best suits your team size. Take a look at our price ranges below:</h6>

                <ol class="list price-list">
                    <li class="list-item">
                        <b>Basic Plan</b> - $49.99 per month ( up to 10 team members ):
                        Ideal for startups and small businesses, this plan offers all the essential features you need to collaborate effectively with your team.
                    </li>

                    <li class="list-item">
                        <b>Standard Plan</b> - $79.99 per month ( up to 50 team members ):
                        If your team is growing or already consists of a moderate number of members, this plan provides additional resources and functionalities to enhance your workflow.
                    </li>

                    <li class="list-item">
                        <b>Advanced Plan</b> - $149.99 per month ( up to 150 team members ):
                        Designed for larger organizations, this plan offers expanded capabilities, allowing you to manage a substantial team while maintaining efficiency and productivity.
                    </li>

                    <li class="list-item">
                        <b>Pro Plan</b> - $300.00 per month ( up to 500 team members ):
                        Tailored for businesses with a significant workforce, the pro plan accommodates larger teams while providing comprehensive features to facilitate collaboration and coordination.
                    </li>

                    <li class="list-item">
                        <b>Enterprise Plan</b> - Contact a Planiversity Sales Agent for specific pricing (more than 500 team members):
                        For enterprises with an extensive number of team members, our enterprise plan offers custom pricing to meet your unique needs. Get in touch with our dedicated sales team to discuss the most suitable plan for your organization.
                    </li>
                </ol>

                <h6>All our pricing plans also come with the option to subscribe annually, providing you with even more cost savings. By choosing the annual account, you will pay only for 11 months, granting you an additional month of service free of charge.</h6>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Business Modal End-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.16/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="<?= SITE ?>assets/billing/js/country-states.js"></script>

<script type="text/javascript">
    let teamMembers = 0;
    let totalPrice = 0;
    let count = 0;
    let user_country_code = "US";

    $(document).ready(function() {

        const country_array = country_and_states.country;
        const states_array = country_and_states.states;

        const id_state_option = document.getElementById("state");
        const id_country_option = document.getElementById("country");

        const createCountryNamesDropdown = () => {
            let option = '';
            option += '<option value="">select a country</option>';

            for (let country_code in country_array) {
                // set selected option user country
                let selected = (country_code == user_country_code) ? ' selected' : '';
                option += '<option value="' + country_code + '"' + selected + '>' + country_array[country_code] + '</option>';
            }
            id_country_option.innerHTML = option;
        };

        const createStatesNamesDropdown = () => {
            let selected_country_code = id_country_option.value;
            // get state names
            let state_names = states_array[selected_country_code];

            // if invalid country code
            if (!state_names) {
                id_state_option.innerHTML = '<option value="">select a state</option>';
                return;
            }
            let option = '';
            option += '<select id="state">';
            option += '<option value="">select a state</option>';
            for (let i = 0; i < state_names.length; i++) {
                option += '<option value="' + state_names[i].name + '">' + state_names[i].name + '</option>';
            }
            option += '</select>';
            id_state_option.innerHTML = option;
        };

        // country select change event
        id_country_option.addEventListener('change', createStatesNamesDropdown);

        createCountryNamesDropdown();
        createStatesNamesDropdown();

        function updateTotalPrice() {

            teamMembers = parseInt($('#userSlider').val());

            switch (true) {
                case (teamMembers <= 10):
                    totalPrice = 49.99;
                    break;
                case (teamMembers <= 50):
                    totalPrice = 79.99;
                    break;
                case (teamMembers <= 150):
                    totalPrice = 149.99;
                    break;
                default:
                    totalPrice = 300.00;
            }

            $('#member_count').text(teamMembers);

            if (count == 1) {
                $('#business-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>${(totalPrice * 11).toFixed(2)}&nbsp;<span>/year</span>`);
            } else {
                $('#business-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>${totalPrice.toFixed(2)}&nbsp;<span>/month</span>`);
            }
            //$('#member_count').text(totalPrice.toFixed(2));
        }

        // Function to update the position of the user icon
        function updateUserIconPosition() {
            var slider = $('#userSlider');
            var sliderWidth = slider.width();
            var sliderValue = slider.val();
            var min = slider.attr('min');
            var max = slider.attr('max');

            var iconWidth = parseInt($('#userIcon').css('width'));
            var newPosition = (sliderValue - min) / (max - min) * (sliderWidth - iconWidth);
            $('#userIcon').css('left', newPosition + 'px');

        }

        $('#userSlider').on('input', function() {
            updateTotalPrice();
            updateUserIconPosition();
        });

        updateTotalPrice();
        updateUserIconPosition();

    });

    function change_plan() {
        if (count == 1) {
            count = 0;
            $('#business-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>${totalPrice.toFixed(2)}&nbsp;<span>/month</span>`);
            $('#individual-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>9.99&nbsp;<span>/month</span>`);
        } else {
            count = 1;
            $('#business-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>${(totalPrice * 11).toFixed(2)}&nbsp;<span>/year</span>`);
            $('#individual-plan').html(`<i class="fa fa-usd" aria-hidden="true"></i>109&nbsp;<span>/year</span>`);
        }

        console.log(count);
    }


    $(".dropdown-menu li button").click(function(evt) {
        // Setup VARs
        var inputGroup = $('.input-group');
        var inputGroupBtn = inputGroup.find('.input-group-btn .btn');
        var inputGroupAddon = inputGroup.find('.input-group-addon');
        var inputGroupInput = inputGroup.find('.form-control');

        // Get info for the selected country
        var selectedCountry = $(evt.target).closest('li');
        var selectedEmoji = selectedCountry.find('.flag-emoji').html();
        var selectedExampleNumber = selectedCountry.find('.example-number').html();
        var selectedDialCode = selectedCountry.find('.dial-code').html();

        // Dynamically update the picker
        inputGroupBtn.find('.emoji').html(selectedEmoji);
        inputGroupAddon.html(selectedDialCode);
        $('#country_code').val(selectedDialCode);
        inputGroupInput.attr("placeholder", selectedExampleNumber);
    });

    $("#contactform").validate({

        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            mobile: {
                maxlength: 20,
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
            message: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
        },

        messages: {
            name: {
                required: "Please type your name",
            },
            email: {
                required: "Please type your email",
                email: "Please type valid email",
            },
            country: {
                required: "Please select a country",
            },
            state: {
                required: "Please select a state",
            },
            message: {
                required: "Please type your message",
                minlength: "Minimum length 5 characters",
                maxlength: "Maximum length 150 characters",
            },
        },

        submitHandler: function(form) {

            $('.process_button').css('cursor', 'wait');
            $('.process_button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/price/process",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $('#contactAgentModal').modal('hide');
                    $(form).trigger("reset");

                    Swal.fire({
                        title: 'Inquery Submitted',
                        text: "Our agent will contact you shortly.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        timer: 4000
                    });

                    $('.process_button').css('cursor', 'pointer');
                    $('.process_button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.responseJSON.data.error) {
                        toastr.error(jqXHR.responseJSON.data.error);
                    }

                    $('.process_button').css('cursor', 'pointer');
                    $('.process_button').removeAttr('disabled');

                }
            });
        },
        //errorElement: "span",                                               // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE ?>assets/billing/js/menuzord.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<?php include_once("include_new_footer_other.php") ?>