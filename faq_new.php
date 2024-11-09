<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("include_new_header.php")
?>
<div class="faqs-main-wrapper spacer">
    <div class="container">
        <div class="col-md-12 section-header">
            <h2 class="lhight blue-color">Frequently Asked Questions, <br> Consistently Prepared Answers</h2>
        </div>
        <div class="row faqs-box m-t-40">
            <div id="accordion" class="faqs-accordion-wrap" data-aos="fade-up">
                <div class="accordion-faq">
                    <a class="accordion-title p-4 active" data-toggle="collapse" href="#acc1">
                        <i class="fa fa-angle-down mr-3"></i>
                        Is there a service comparable to Planiversity?
                    </a>
                    <div id="acc1" class="collapse show" role="tabpanel" data-parent="#accordion">
                        <div class="accordion-text">
                            The answer is simply a no. Planiversity is a unique software, in the sense that it turns you, the traveler, into a well prepared and confident traveler. Using the experience of our Chief Executive Officer, the idea behind the service is to make traveling more efficient by compiling necessary information. Locations of essential facilities will make you more prepared to respond to any unforeseen emergencies while traveling. While there are other companies that can compile your itineraries, none of them allow you the possibility to compile weather, fuel stops, locations of embassies, etc. At Planiversity we are in a league of our own.
                        </div>
                    </div>
                </div>
                <div class="accordion-faq">
                    <a class="accordion-title p-4" data-toggle="collapse" href="#acc2">
                        <i class="fa fa-angle-down mr-3"></i>
                        Why would I need to have digital copies of my license or passport compiled in the exported document?
                    </a>
                    <div id="acc2" class="collapse" role="tabpanel" data-parent="#accordion">
                        <div class="accordion-text">
                            The idea behind including digital copies is to allow travelers to keep their important documents stored in a safe location while touring the town. Travelers will not have to worry about keeping difficult to replace documents on them, nor will they have to confront the possibility of having their important documents confiscated from their person. If you need to verify your identification during a spot check, use the digital image. Do not burden yourself with potential corruption or worry that your documents may fall into the wrong hands, delaying your return home.
                        </div>
                    </div>
                </div>
                <div class="accordion-faq">
                    <a class="accordion-title p-4" data-toggle="collapse" href="#acc3">
                        <i class="fa fa-angle-down mr-3"></i>
                        Is Planiversity planning to improve or make the service more intuitive to users?
                    </a>
                    <div id="acc3" class="collapse" role="tabpanel" data-parent="#accordion">
                        <div class="accordion-text">
                            An intuitive process is important these days. Software users want to see the systems they are using become more responsive, as well as simpler to use. However, with Planiversity, our intent is to balance that level of intuitiveness and user responsibility. The key to the service is to be the planner and have control over your travel packet; something which the service will not do for you. The functionality of certain aspects will be improved as time goes on and as user feedback continues to accumulate.
                        </div>
                    </div>
                </div>
                <div class="accordion-faq">
                    <a class="accordion-title p-4" data-toggle="collapse" href="#acc4">
                        <i class="fa fa-angle-down mr-3"></i>
                        Why use Planiversity? How does it benefit me?
                    </a>
                    <div id="acc4" class="collapse" role="tabpanel" data-parent="#accordion">
                        <div class="accordion-text">
                            Most people have had, or will have the chance to travel. The many items and pieces of information that there are to remember to bring, in addition to wanting to utilize while traveling, are numerous and difficult to keep track of. We at Planiversity understand the necessity for positive information consolidation and have created a site where users can combine all of those pieces of essential information into one single source. As time goes on Planiversity will work to improve the filter process, making your travel packet better than what it is now.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= SITE; ?>newpage/Planiversity/js/jquery.min.js"></script>
<script src="<?= SITE; ?>newpage/Planiversity/js/bootstrap.min.js"></script>
<script src="<?= SITE; ?>newpage/Planiversity/js/custom.js"></script>
<script type="text/javascript">
    $(".accordion-title").on('click', function() {
        $('.accordion-title').removeClass('active');
        $(this).addClass('active');
    });
</script>
<?php include_once("include_new_footer_other.php") ?>