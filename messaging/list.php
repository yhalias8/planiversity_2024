<?php
$URL = ADMIN_URL;

$FILE_PATH = "https://www.planiversity.com/";


function curlRequestGet($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    $headers = array(
        "Content-type: application/json",
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function curlRequestPost($url, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);


    $headers = array(
        "Content-type: application/json",
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function processHeadData($mdata, $path)
{
    $hold = "";
    $parms = "ajaxfiles/profile/";

    foreach ($mdata as $item) {
        $props = file_path_process($path, $parms, $item->recipient->picture);
        $need_action = $item->need_action ? 'need-action' : '';

        $hold .= '
    <li class="nav-item head-item">
    <button type="button" tabindex="0" class="dropdown-item receipt-button" value="' . $item->id . '" id="head_' . $item->id . '">
    <div class="widget-content p-0">
    <div class="widget-content-wrapper">
    <div class="widget-content-left mr-3">
    <div class="avatar-icon-wrapper">
    <div class="badge badge-bottom badge-success badge-dot badge-dot-lg">
    </div>
    <div class="avatar-icon">
    <img src="' . $props . '" alt="">
    </div>
    </div>
    </div>
    <div class="widget-content-left">
    <div class="widget-heading">' . $item->recipient->name . '</div>
    <div class="widget-subheading">' . $item->recipient->account_type . '</div>
    </div>
    </div>
    <div class="' . $need_action . '"></div>
    </div>
    </button>
    </li>                                                            
    ';
    }

    return $hold;
}

function processReceiptData($mdata, $path)
{
    
    if (!$mdata) {
        return '<div class="mx-auto text-center mt-5">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }
    $hold = "";
    $parms = "ajaxfiles/profile/";

    foreach ($mdata as $item) {
        $props = file_path_process($path, $parms, $item->picture);

        $hold .= '
        <div class="col-lg-3 col-md-4 col-6 col-sm-6 receipt-col">
        <button class="receipt_button receipt_action" value="' . $item->id . '">
            <div class="receipt_box">
                <div class="receipt_image">
                <img src="' . $props . '" alt="">
                </div>

                <div class="receipt_info">
                    <h4>' . $item->name . '</h4>
                    <p>#' . $item->customer_number . '</p>
                </div>
            </div>
        </button>

    </div>                                                            
    ';
    }

    return $hold;
}

function processMessageData($mdata, $path)
{

    if (!$mdata) {
        return '<div class="not-message-found">
        <i class="fa fa-window-close"></i> <p>No message found</p>
        </div>';
    }

    $hold = "";
    $parms = "ajaxfiles/profile/";

    foreach (array_reverse($mdata) as $item) {
        $props = file_path_process($path, $parms, $item->senders->picture);
        $action_data = null;
        //$payments = null;
        if (isset($item->requests)) {
            $action_data = $item->requests;
        }
        $migration = migration_action($action_data);
        $payment = payment_action($action_data);
        $hold .= '
        <div class="chat-box">
        <div class="chat-box-wrapper">
        <div>
        <div class="avatar-icon-wrapper mr-1">
        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
        <div class="avatar-icon avatar-icon-lg rounded">
        <img src="' . $props . '" alt="">
        </div>
        </div>
        </div>
        <div>
        <div class="chat-box">' . $item->message . ' </div>
        <small class="opacity-6">
        <i class="fa fa-calendar-alt mr-1"></i>
        ' . $item->created_at . ' 
        </small>
        </div>
        </div>
        </div>
        ' . $migration . '
        ' . $payment . '
        ';
    }
    return $hold;
}


function processServiceData($mdata, $path)
{

    if (!$mdata) {
        return '<div class="not-found">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }

    $hold = "";
    $parms = "images/service/";
    $author_parms = "images/author/";
    foreach ($mdata as $item) {

        $props = file_path_process($path, $parms, $item->service_image);
        $authorprops = file_path_process($path, $author_parms, $item->author_image);
        $title = limitServiceTitle($item->service_title);
        $wishlist = wishlist_calculation($item->wishlist, $item->id);

        $hold .= '
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 service-item">
        <div class="service-wrapper" data-service-id=' . $item->id . '>
            <img src="' . $props . '" alt="" class="service_image">

            <div class="service-collect">
            <button ' . $wishlist . '>
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            </button>
            </div>
            
            <div class="service-content">
                <div class="service-header">
                    <p>' . $item->category->category_name . '</p>
                    <h4>' . $title . '</h4>
                </div>

                <div class="rating-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="ratings">
                            <i class="fa fa-star rating-color"></i>
                            <p>' . $item->service_rating . ' <span> | ' . $item->reviews_count . ' reviews </span></p>
                        </div>                        
                    </div>
                </div>

                <div class="service-footer">
                    <div class="seller-info">
                        <img src="' . $authorprops . '">
                        <p>' . $item->author_name . '</p>
                    </div>
                    <div class="service-price">
                        <p>Starting at <span>$' . $item->regular_price . '</span></p>
                    </div>
                </div>

            </div>
        </div>
    </div>        
        ';
    }
    return $hold;
}




function limitServiceTitle($string)
{
    return substr_replace($string, "...", 50);
}

function file_path_process($path, $parms, $file)
{
    $default_value = $path . "images/user.png";
    if ($file) {
        $default_value = $path . $parms . $file;
    }
    return $default_value;
}


function wishlist_calculation($wish, $service_id)
{
    $id = '';
    $status = "";
    if (!empty($wish)) {
        $id = $wish[0]->id;
        $status = "added";
    }

    return 'class="wish-class ' . $status . '" value="' . $id . '" data-service-id=' . $service_id;
}

function price_calculation($mdata, $member = 0)
{
    $default_value = $mdata->regular_price;
    if ($mdata->sale_price != 0) {
        $default_value = $mdata->sale_price;
    }

    if ($member) {
        $default_value = $mdata->member_price;
    }
    return $default_value;
}

function migration_action($request_data)
{

    $hold = null;
    if (!empty($request_data->migrations)) {
        $migration = $request_data->migrations;
        $migration_button = migration_process_section($request_data);
        $hold = '
    <div class="message_event_section">
    <div class="message_event_header migration">
    <p> #' . $migration->trip_id . ' ' . $migration->trips->itinerary_type . ' ' . $migration->type . ' request - ' . $migration->status . ' </p>
    </div>    
    ' . $migration_button . '
    </div>
    ';
    }

    return $hold;
}


function migration_process_section($request_data)
{
    $hold = null;
    global $uid;
    if (($request_data->is_action) && (!empty($request_data->migrations) && ($uid == $request_data->migrations->planner_user_id) && ($request_data->migrations->status == "pending"))) {
        $hold = '<div class="message_event_footer" id="section_block_' . $request_data->migrations->id . '">
    <div class="message_event_action" data-did=' . $request_data->migrations->id . '>    
    <button class="btn btn-danger message_event_button migration_action" data-flag=' . $request_data->false_flag . '>' . $request_data->false_flag . '</button>
    <button class="btn btn-primary message_event_button positive migration_action" data-flag=' . $request_data->true_flag . '>' . $request_data->true_flag . '</button>
    </div>
    </div>';
    }
    return $hold;
}

function payment_action($request_data)
{
    $hold = null;
    if (!empty($request_data->payments)) {
        $payment = $request_data->payments;
        $hold = '
    <div class="message_event_section">
    <div class="message_event_header payment">
    <p> ' . $payment->title . ' </p>
    <p>$' . $payment->amount . '</p>
    </div>    
    <div class="message_event_body">
    <p>' . $payment->description . '</p>
    </div>
    <div class="message_event_footer">
    <div class="message_event_action">    
    <button class="btn btn-primary message_event_button positive">Pay</button>
    </div>
    </div>
    </div>
    ';
    }

    return $hold;
}
