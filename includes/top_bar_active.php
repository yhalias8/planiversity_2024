<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */
?><div class="navbar-custom old-site-colors">
    <div class="container-fluid">
        <div id="navigation">
            <ul class="navigation-menu text-center plan-nav">
                <li>
                    <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button scale" data-toggle="modal" data-target="#schedule-modal">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/calendar_check.png" alt="Schedule">Schedule</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/file_blank.png" alt="Schedule">Notes</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SITE; ?>trip/resources/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/slider_02.png" alt="Resources">Resources</p>
                    </a>
                </li>
                <li class="selected">
                    <a href="javascript:void(0)" class="left-nav-button">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/plans.png" alt="Schedule">Plans</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/folder_open.png" alt="Schedule">Documents</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/share_outline.png" alt="Schedule">Connect</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                        <!--<img src="<?php echo SITE; ?>assets/images/step_pdf.png" alt="Export">-->
                        <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/download.png" alt="Schedule">Export</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
