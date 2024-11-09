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
        <div class="category-item item" data-category-slug=' . $item->slug . '>
        <div class="category-content">
            <img src="' . $props . '" alt="" class="category_image">
            <div class="category-body">
                <p>' . $item->services_count . ' Services</p>
                <h3>' . $item->category_name . '</h3>
            </div>
        </div>
    </div>
    ';
    }
    return $hold;
}


function processBlogData($mdata, $path)
{

    if (!$mdata) {
        return '<div class="not-found">
        <i class="fa fa-window-close"></i> <p> No data found</p>
        </div>';
    }

    $hold = "";
    $parms = "images/blog/post/";
    $author_parms = "images/blog/author/";
    foreach ($mdata as $item) {

        $props = file_path_process($path, $parms, $item->featured_image);
        $authorprops = file_path_process($path, $author_parms, $item->author->photo);
        $title = limitServiceTitle($item->post_title);
        //$wishlist = wishlist_calculation($item->wishlist, $item->id);
        $wishlist = "";


        $hold .= '
        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
        <div class="blog-wrapper">
            <img src="' . $props . '" alt="' . $item->post_title . '" />
            <div class="blog-content">
                <div class="info-header">
                    <img src="' . $authorprops . '">
                    <div class="info-text">
                        <h4>TRAVEL TIPS</h4>
                        <h5>' . $item->author->author_name . ' <span>April 01, 2022</span></h5>
                    </div>
                </div>
                <h3>' . $title . '</h3>

                <p><a href="">Read more</a></p>

            </div>
        </div>
    </div>        
        ';
    }
    return $hold;
}


function limitServiceTitle($string)
{
    return substr_replace($string, "...", 120);
}

function file_path_process($path, $parms, $file)
{
    return $path . $parms . $file;
}
