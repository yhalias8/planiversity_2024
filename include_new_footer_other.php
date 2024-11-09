 <footer class="footer position-relative text-left">
     <style>
     .mycode{
         background:#101010;
     }
         .footer-social {
             display: flex;
             margin-bottom: 3rem;

         }

         .footer-social li {
             color: white;
             list-style: none;
             padding: 0 30px 0 0px;
         }

         .footer-social li a {
             color: white;
         }

         footer {
             background: #0C246B;
             font-family: Montserrat, sans-serif;
         }

         .footer-img {
             position: absolute;
             bottom: 0px;
             left: 0px;
             height: 125px;
         }

         .footer-title {
             font-size: 26px;
             color: white;
             font-weight: 700;
         }

         .footer-content-titile {
             font-size: 18px;
             color: #F7B74A;
             font-weight: 600;
         }

         .footer-content-body .footer-sitemap {
             color: #E9F7FE;
             font-size: 15px;
         }

         .footer-content-body {
             padding: 10px 0;
         }

         .footer-content-text {
             color: #E9F7FE;
         }

         .footer-section3-text,
         .footer-section3-text a {
             color: #F7B74A;
         }

         .copyright {
             padding: 20px 0;
             border-top: 1px solid;
         }
         
        .footer_item2{
        padding-left: 40px;
        display: inline-block;
        vertical-align: top;
        position: relative;
        top: 0;
        }
    
        @media only screen and (max-width: 479px){
        .footer_item2 {
            padding-left: 0px;
            width:100%;
        }
        }
         
     </style>
     <img src="<?php echo SITE; ?>assets/images/landingImage/part-3.png" alt="create route" class="footer-img">
     <div class="container">
         <div class="row footer-border-bottom">
             <div class="col-md-3">
                 <p class="footer-title">Planiversity</p>
                 <p class="footer-content">A revolutionary travel logistics</p>
                 <p class="footer-content"> service, dedicated to consolidating</p>
                 <p class="footer-content pb-5">so much of your travel information.</p>
                 <div class="footer-social">
                     <li><a href="https://twitter.com/planiversity" class=""><i class="fa fa-twitter"></i></a></li>
                     <li><a href="https://www.facebook.com/Planiversity/" class=""><i class="fa fa-facebook"></i></a></li>
                     <li><a href="https://www.instagram.com/planiversity/" class=""><i class="fa fa-instagram"></i></a></li>
                     <li><a href="https://www.linkedin.com/company/planiversity/" class=""><i class="fa fa-linkedin"></i></a></li>
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="footer_item2">
                 <p class="footer-content-titile">Site map</p>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="<?php echo SITE; ?>contact-us">Contact Us</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="<?= SITE; ?>faq">FAQs</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="<?php echo SITE; ?>what-you-get">What You'll get</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="https://www.planiversity.com/sitemap.xml">Sitemap</a>
                 </div>
                 </div>
                 
             <div class="footer_item2">
                    <p class="footer-content-titile">Legal</p>
                    <div class="footer-content-body">
                      <a class="footer-sitemap" href="https://getterms.io/view/98sYm/privacy/en-us">Privacy Policy</a>
                    </div>
                    <div class="footer-content-body">
                      <a class="footer-sitemap" href="https://getterms.io/view/98sYm/tos/en-us">Terms of Service</a>
                    </div>
                    <div class="footer-content-body">
                      <a class="footer-sitemap" href="https://getterms.io/view/98sYm/cookie/en-us">Cookies</a>
                    </div>
                    <div class="footer-content-body">
                      <a class="footer-sitemap" href="https://getterms.io/view/98sYm/aup/en-us">AUP</a>
                    </div>
                  </div>
                
                <div class="footer_item2">
                    
                    <p class="footer-content-titile">Quick Links</p>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="<?= SITE ?>data-security">Data Security</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="<?= SITE ?>affiliate">Become an Affiliate</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="">Partners</a>
                 </div>
                 <div class="footer-content-body">
                     <a class="footer-sitemap" href="https://planivers.com" target="_blank">Planivers Magazine</a>
                 </div>
                     
                </div>
                
             </div>
             
             <div class="col-md-3">
                 <p class="footer-content-titile">Contacts</p>
                 <p class="footer-content-text">Have a question? Need to get </p>
                 <p class="footer-content-text">something off your chest?</p>
                 <p class="footer-content-text">Email Us and we'll contact you...</p>
                 <p class="footer-section3-text pt-4">4023 Kennett Pike</p>
                 <p class="footer-section3-text">Suite 690</p>
                 <p class="footer-section3-text">Wilmington, DE 19807</p>
                 <div class="footer-section3-text pb-5 mb-3">
                     <a class="footer-tag" href="mailto:plans@planiversity.com">plans@planiversity.com</a>
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-md-12">
                 <p class="copyright">&copy; Copyright. 2015 - <?= date('Y'); ?>. Planiversity, LLC. All Rights Reserved.</p>
             </div>
         </div>
     </div>
 </footer>

 </div>
 </div>

 </body>

 </html>