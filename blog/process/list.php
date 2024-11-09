<?php
include_once("../../config.ini.php");
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

function processBlogCategoryData($mdata, $url, $value)
{

    if (!$mdata) {
        return '<div class="not-found">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }

    $hold = '<li class="nav-item"><a href="' . $url . '" class="nav-link filter-blog">All</a></li>';

    $url = $url . "/category/";

    foreach ($mdata as $item) {

        $active = ($item->slug == $value) ? 'active' : '';

        $hold .= '<li class="nav-item"><a href="' . $url . '' . $item->slug . '" class="nav-link filter-blog ' . $active . '">' . $item->category_name . '</a></li>';
    }
    return $hold;
}

function processBlogData($mdata, $path, $site)
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
        $categories = blog_category_process($item->category_type, $site);
        $blog_url = $site . "post/" . $item->post_slug;
        $author_url = $site . "blog/author/" . $item->author->slug;

        $hold .= '
        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
        <div class="blog-wrapper">
        <a href=' . $blog_url . '><img src="' . $props . '" alt="' . $item->post_title . '" /></a>
            <div class="blog-content">
                <div class="info-header">
                    <img src="' . $authorprops . '">
                    <div class="info-text">
                        <h4>' . $categories . '</h4>
                        <h5><a href=' . $author_url . '>' . $item->author->author_name . '</a> <span>' . $item->published_at . '</span></h5>
                    </div>
                </div>
                <h3><a href=' . $blog_url . '>' . $title . '</a></h3>
                <p><a href=' . $blog_url . '>Read more</a></p>
            </div>
        </div>
    </div>        
        ';
    }
    return $hold;
}

function blog_category_process($request_data, $site)
{
    $hold = null;

    if (($request_data)) {
        foreach ($request_data as $list) {
            $category_url = $site . "blog/category/" . $list->slug;
            $hold .= '<span><a href=' . $category_url . '>' . $list->category_name . '</a></span>';
        }
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
