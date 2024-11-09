<?php
$URL = ADMIN_URL;
$FILE_PATH = ADMIN_URL_UPLOADS;


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

function processCategoryData($mdata, $path)
{

    if (!$mdata) {
        return '<div class="not-found">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }

    $hold = "";
    $parms = "images/category/";

    foreach ($mdata as $item) {

        $props = file_path_process($path, $parms, $item->category_image);
        $hold .= '
        <div class="category-item item">
        <div class="category-content">
            <img src="' . $props . '" alt="" class="category_image">
            <div class="category-body">
                <p>1.853 Courses</p>
                <h3>' . $item->category_name . '</h3>
            </div>
        </div>
    </div>
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
    foreach ($mdata as $item) {

        $props = file_path_process($path, $parms, $item->service_image);
        $title = limitServiceTitle($item->service_title);

        $hold .= '
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 service-item">
        <div class="service-wrapper">
            <img src="' . $props . '" alt="" class="service_image">
            <div class="service-collect">
                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
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
                            <p>4.7 <span> 684 reviews </span></p>
                        </div>                        
                    </div>
                </div>

                <div class="service-footer">
                    <div class="seller-info">
                        <img src="https://www.planiversity.com/staging/ajaxfiles/profile/IMG_1466443467.png">
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
    return $path . $parms . $file;
}
