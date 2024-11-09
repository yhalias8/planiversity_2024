<?php

function trip($dbh, $user_id, $id, $acc_type)
{
    $mapBoxKey     = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";
    

    $stmt = $dbh->prepare("SELECT * FROM trips WHERE id_user=? AND id_trip=? ORDER BY `id_trip` DESC");
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $tripl = $stmt->fetch(PDO::FETCH_OBJ);


    if ($acc_type == 'Individual') {
        $trip_reason = "Personal";
    } else {
        $trip_reason = "Business";
    }



    $from_address = explode(" ", $tripl->location_from);
    $to_address = explode(" ", $tripl->location_to);

    $employee_ = "";
    if (!empty($tripl->id_employee)) {
        $employee_ = ' ' . get_employee($tripl->id_employee);
    }

    $triptitle = trim($tripl->title);
    $triptitle = str_replace('&#39;', '_', $triptitle);
    $triptitle = str_replace(' ', '_', $triptitle);
    $pdfname = $triptitle . '-' . $tripl->id_trip . '-' . $user_id;

    $export_url = SITE . "trip/name/" . $tripl->id_trip;

    if ($tripl->pdf_generated) {
        $file_path = $pdfname . '.pdf';

        $titledata = $titledata2 = $tripl->title . ' ' . $employee_;
        $action  = '<a data-file-path=' . $file_path . ' class="file_download">Download &nbsp;&nbsp;<i class="bi bi-download"></i></a>';
    } else {
        $titledata = $titledata2 = $from_address[0] . ' to ' . $to_address[0];
        $action  = '<a href=' . $export_url . ' target="_blank">Export &nbsp;&nbsp;<i class="bi bi-box-arrow-in-right"></i></a>';
    }

    $trip_date = date('M d h:i A', strtotime($tripl->location_datel . ' ' . $tripl->location_datel_deptime)) . ' - ' . date('h:i A', strtotime($tripl->location_datel_arrtime));
    $link = SITE . "/assets/billing2/images/map_img.jpg";

    $destination_r = str_replace('(', '', $tripl->location_to_latlng);
    $destination_r = str_replace(')', '', $destination_r);
    $destination = $destination_r;
    $des_lon = trim(explode(',', $destination)[1]);
    $des_lat = trim(explode(',', $destination)[0]);

    $destinataion_update = explode(",", $destination)[1] . ',' . explode(",", $destination)[0];

    //$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9(" . $des_lon . ")/" . $des_lat . ",11,0/740x940@2x?access_token=$mapBoxKey";

    $destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9(" . trim($destinataion_update, " ") . ")/" . trim($destinataion_update, " ") . ",6,0/600x400@2x?access_token=$mapBoxKey";

    //$destination_route_map = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+0688e9($des_lon)/$des_lat,16,0/400x600@2x?access_token=$mapBoxKey";
    $export_url = SITE . "trip/name/" . $tripl->id_trip;



    return '<div id="meeting" class="tabcontent">
    <div class="trips_meeting_righ_box_item">
        <div class="trips_meeting_righ_box_item_text">
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="time_head">
                        <h6>' . $trip_date . '</h6>
                        <h2>' . $titledata . '</h2>
                        <h3></h3>
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="trips_meeting_righ_box_btn text-right">
                        <a href="javascript:avoid(0)" class="yellow_color export-color">TRIP</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <p>' . $trip_reason . ' trip</p>

            </div>
        </div>
        <div class="map_trips">
            <img src=' . $destination_route_map . ' class="img-fluid">
        </div>
        <div class="trips_meeting_righ_box_item_text">
            <div class="col-xl-12">
                <div class="open_and_export_button">
                    <ul class="list-unstyled">
                        <!-- <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-eye"></i></a></li>-->                        
                        <li> ' . $action . '</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>';
}



function meeting($dbh, $user_id, $id, $acc_type)
{
    $mapBoxKey     = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";

    $stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting' AND id=? ORDER BY `id` DESC");
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $meetingl = $stmt->fetch(PDO::FETCH_OBJ);


    if ($acc_type == 'Individual') {
        $trip_reason = "Personal";
    } else {
        $trip_reason = "Business";
    }

    $eventtitle = trim($meetingl->event_title);

    if (strlen($eventtitle) >= 80)
        $eventtitle = substr($eventtitle, 0, 80) . '...';



    $date = explode('-', $meetingl->event_date);
    $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $meetingl->event_time_from;


    $event_date = date('M d h:i A', strtotime($date)) . ' - ' . date('h:i A', strtotime($meetingl->event_time_to));

    return '<div id="meeting" class="tabcontent">
    <div class="trips_meeting_righ_box_item">
        <div class="trips_meeting_righ_box_item_text">
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="time_head">
                        <h6>' . $event_date . '</h6>
                        <h2>' . $eventtitle . '</h2>                        
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="trips_meeting_righ_box_btn text-right">
                        <a href="javascript:avoid(0)" class="yellow_color export-color">MEETING</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <p>' . $trip_reason . ' meeting</p>
            </div>

            <div class="col-xl-12">
                <h3>Customer Name : <span>' . $meetingl->customer_name . '</span></h3>
                <h3>Customer Number : <span>' . $meetingl->customer_number . '</span></h3>
                <h3>Meeting Location : <span>' . $meetingl->event_location . '</span></h3>
                <h3>Meeting Overview : <span>' . $meetingl->event_overview . '</span></h3>
            </div>


        </div>
        <div class="map_trips">
            
        </div>
        <div class="trips_meeting_righ_box_item_text">
            <div class="col-xl-12">
                <div class="open_and_export_button">
                    <ul class="list-unstyled">
                        <!-- <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-eye"></i></a></li>-->                        
                        <!--<li><a href=' . $export_url . ' target="_blank">Export&nbsp;&nbsp;<i class="bi bi-box-arrow-in-right"></i></a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>';
}



function event($dbh, $user_id, $id, $acc_type)
{
    $mapBoxKey     = "pk.eyJ1IjoicGxhbml2ZXJzaXR5IiwiYSI6ImNrbWwwMXVhZjAxYnMyd2xlcW5yZGR5cTUifQ.SLgwBubC1t4UpKZ2MEyzZg";

    $stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event' AND id=? ORDER BY `id` DESC");
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $event1 = $stmt->fetch(PDO::FETCH_OBJ);


    if ($acc_type == 'Individual') {
        $trip_reason = "Personal";
    } else {
        $trip_reason = "Business";
    }

    $eventtitle = trim($event1->event_title);

    if (strlen($eventtitle) >= 80)
        $eventtitle = substr($eventtitle, 0, 80) . '...';



    $date = explode('-', $event1->event_date);
    $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $event1->event_time_from;


    $event_date = date('M d h:i A', strtotime($date)) . ' - ' . date('h:i A', strtotime($event1->event_time_to));

    return '<div id="meeting" class="tabcontent">
    <div class="trips_meeting_righ_box_item">
        <div class="trips_meeting_righ_box_item_text">
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="time_head">
                        <h6>' . $event_date . '</h6>
                        <h2>' . $eventtitle . '</h2>                        
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="trips_meeting_righ_box_btn text-right">
                        <a href="javascript:avoid(0)" class="yellow_color export-color">EVENT</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <p>' . $trip_reason . ' event</p>
            </div>

            <div class="col-xl-12">
                <h3>Customer Name : <span>' . $event1->customer_name . '</span></h3>
                <h3>Customer Number : <span>' . $event1->customer_number . '</span></h3>
                <h3>Meeting Location : <span>' . $event1->event_location . '</span></h3>
                <h3>Meeting Overview : <span>' . $event1->event_overview . '</span></h3>
            </div>


        </div>
        <div class="map_trips">
            
        </div>
        <div class="trips_meeting_righ_box_item_text">
            <div class="col-xl-12">
                <div class="open_and_export_button">
                    <ul class="list-unstyled">
                        <!-- <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-eye"></i></a></li>-->                        
                        <!--<li><a href=' . $export_url . ' target="_blank">Export&nbsp;&nbsp;<i class="bi bi-box-arrow-in-right"></i></a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>';
}
