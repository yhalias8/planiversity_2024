<?php

$API_PATH = "https://api.unsplash.com/search/photos";
$ACCESS_TOKEN = "-ee5S8aSfkIJ-fpilZqkGroNzE22HvFkS0lrpjWB7Hg";
$orientation = "portrait";
$per_page = "18";




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
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function processData($mdata)
{

    if (!$mdata) {
        return '<div class="no-data">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }

    $hold = "";

    foreach ($mdata as $item) {

        $props = "selectImageProcessing('$item->id')";

        $hold .= '

        <div class="item selfie col-lg-2 col-md-4 col-6 col-sm">
        <a href="javascript:void(0)" class="fancylight popup-btn" data-fancybox-group="light" onclick=' . $props . '>
            <img class="img-fluid" src="' . $item->urls->thumb . '" alt="">
        </a>
        </div>

        ';
    }
    return $hold;
}



function previous_setp_calculate($current_step)
{
    $hold = null;
    if ($current_step != 1) {
        $hold = $current_step - 1;
    }

    return $hold;
}
