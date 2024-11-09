<div id="navigation">
    <ul class="navigation-menu text-center plan-nav">
        <li class="<?php if ($step_index == "connect") {
            echo 'selected';
        } ?>">
            <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/share_outline.png" alt="Connect">Connect</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "schedule") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button scale">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/calendar_check.png" alt="Schedule">Schedule</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "plan") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/plans.png" alt="Schedule">Plans</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "note") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/file_blank.png" alt="Notes">Notes</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "resources") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/resources/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/slider_02.png" alt="Resources">Resources</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "documents") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/folder_open.png" alt="Documents">Documents</p>
            </a>
        </li>
        <li class="<?php if ($step_index == "name") {
                        echo 'selected';
                    } ?>">
            <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/download.png" alt="Export">Export</p>
            </a>
        </li>
    </ul>
</div>