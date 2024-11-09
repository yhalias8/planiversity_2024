<?php
include_once("config.ini.php");

include("class/class.Plan.php");
include("class/class.Googlecalendar.php");
include("class/class.MicrosoftGraph.php");
include_once("class/class.ToolsWelcome.php");
include_once("class/class.TripPlan.php");

$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) $img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

$event_list = include('event_list.php');

// Mengurutkan array berdasarkan 'id' dalam urutan menurun
usort($event_list, function ($a, $b) {
    return $b['start'] <=  $a['start']; // Menggunakan spaceship operator
});

// print_r($event_list[4]['peoples'][0]['role']); die();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('includes/layouts/head.php'); ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('includes/layouts/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('includes/layouts/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <section class="d-sm-flex align-items-center justify-content-between mb-4">

                        <div>
                            <h1 class="h3 mb-0 text-gray-800 text-primary">Plan View</h1>
                            <span class="text-primary" style="font-size: 14px">Track, manage, and edit your plans in your dashboard.</span>
                        </div>
                        <div>
                            <div class="btn-group btn-group-toggle mt-1" data-toggle="buttons">
                                <label class="btn btn-xs btn-outline-secondary text-black active">
                                    <input type="radio" name="view" id="planView" autocomplete="off" checked> Plan View
                                </label>
                                <label class="btn btn-xs btn-outline-secondary">
                                    <input type="radio" name="view" id="projectView" autocomplete="off"> Project View
                                </label>
                            </div>

                            <button class="btn btn-md btn-outline-dark btn-secondary-custom mt-1">
                                Request Agent
                            </button>
                            <a href="javascript:void(0)" class="btn btn-md btn-primary mt-1">
                                Add New Project
                            </a>
                            <a href="<?= SITE ?>trip/itinerary-option" class="btn btn-md btn-warning mt-1">
                                Start New Plan
                            </a>
                        </div>
                    </section>

                    <div class="slick-slider mb-2">
                        <?php

                        foreach ($event_list as $item) {
                            $randomNumber = rand(1, 10000);
                            $date = new DateTime($item['start']);
                            $paket_number = $item['packet_number'] ? "PACKET #" . htmlspecialchars(strtoupper($item["packet_number"])) : "";

                            $planType = $item['extendedProps']['tipe'] == 'Appt' ?  'Appointment' : $item['extendedProps']['tipe'];


                            $title = $item['title'] ? htmlspecialchars($item['title']) : '** Incomplete '.$planType.' Plan **';
                            $imgBg = !empty($item['cover_image']) ? $item['cover_image'] : "https://picsum.photos/1920/1080?random=$randomNumber";
                        
                            $editLink = $item["is_button"] ? "<a href='" . SITE . "trip/connect/" . $item['id'] . "' style='text-decoration: none' class='trip_name'><i class='bi bi-pencil-square ml-2' style='font-size:14px; color:white'></i></a>" : "";
                            $actionButtons = $item["is_button"] ? ($item['title'] ? "
                                <a class='trip_action_button' onclick='trip_expand(" . $item['id'] . ")' data-trip_ref='" . $item['id'] . "'><i class='bi bi-arrows-fullscreen bi-card-custom' style='font-size:11px'></i></a>
                                <a class='trip_action_button' onclick='delete_trip(" . $item['id'] . ")'><i class='bi bi-trash bi-card-custom'></i></a>" : " <a class='trip_action_button' onclick='delete_trip(" . $item['id'] . ")'><i class='bi bi-trash bi-card-custom'></i></a>") : "";
                        
                                $peopleImages = '';
                                if (is_array($item['peoples']) && !empty($item['peoples'])) {
                                    // Ambil hanya dua orang dari array peoples
                                    $count = min(2, count($item['peoples'])); // Menghitung jumlah yang akan diambil (maksimal 2)
                                    
                                    for ($i = 0; $i < $count; $i++) { 
                                        $photo = htmlspecialchars($item['peoples'][$i]['photo']);
                                        $peopleImages .= "
                                            <img alt='Person " . ($i + 1) . "' height='30' src='ajaxfiles/people/$photo' width='30' />";
                                    }
                                
                                    // Jika ada lebih dari 2 orang, tambahkan indikator tambahan
                                    if (count($item['peoples']) > 2) {
                                        $peopleImages .= "<div class='more'>+ " . (count($item['peoples']) - 2) . "</div>";
                                    }
                                }

                            $eventTime = '';

                            if($item["is_button"]) {
                                $eventTime = date('M d h:i A', strtotime($item['location_datel'] . ' ' . $item['location_datel_deptime']))  .' - '. date('h:i A', strtotime($item['location_datel_arrtime']));
                            }else {
                                $eventTime = $item['date_from'].' - '.$item['date_to'];
                            }


                        
                            echo "
                                <div class='card-custom'>
                                    <img alt='City skyline at sunset' height='200' src='$imgBg' width='300' />
                                    <div class='card-body-custom'>
                                        <div class='d-flex justify-content-between align-items-center'>
                                            <div class='d-flex' style='width:70%; align-items:baseline'>
                                                <h5 class='card-title-custom'>" . $title . "</h5>
                                                $editLink
                                            </div>
                                            <div class='action-icons-custom'>$actionButtons</div>
                                        </div>
                                        <span class='card-text-custom'>" .$eventTime. 
                                        "</span>
                                        <!-- <div class='avatar-group-custom'>$peopleImages</div> -->
                                        <div class='card-footer-custom'>
                                            <span class='card-text-custom'>$paket_number</span> 
                                            <i class='bi bi-copy' data-paket='" . htmlspecialchars($paket_number) . "' onclick='copyToClipboard(this)' style='margin-right:30px;cursor:pointer;'></i>
                                            <button class='btn btn-job'>" . htmlspecialchars($item['extendedProps']['tipe']) . "</button>
                                        </div>
                                    </div>
                                </div>";
                        }
                        ?>

                    </div>

                    <!-- Page Heading -->
                    <section class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-primary">Calendar</h1>
                        <div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-xs btn-outline-secondary text-black active">
                                    <input type="radio" name="calendarEvents" id="filterAll" autocomplete="off" checked> All
                                </label>
                                <label class="btn btn-xs btn-outline-secondary text-black">
                                    <input type="radio" name="calendarEvents" id="filterTrips" autocomplete="off"> Trips
                                </label>
                                <label class="btn btn-xs btn-outline-secondary text-black">
                                    <input type="radio" name="calendarEvents" id="filterEvents" autocomplete="off"> Events
                                </label>
                                <label class="btn btn-xs btn-outline-secondary text-black">
                                    <input type="radio" name="calendarEvents" id="filterJobs" autocomplete="off"> Jobs
                                </label>
                                <label class="btn btn-xs btn-outline-secondary text-black">
                                    <input type="radio" name="calendarEvents" id="filterAppointments" autocomplete="off"> Appointments
                                </label>
                            </div>
                        </div>
                    </section>


                    <section id='calendar'></section>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include('includes/layouts/footer.php'); ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="bi bi-chevron-up"></i>
    </a>

    <?php include('includes/layouts/script.php'); ?>

</body>

</html>

<?php include('includes/modal_dashboard.php'); ?>