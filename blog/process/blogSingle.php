<?php

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

function blog_category_process($request_data, $site)
{
    $hold = null;

    if (($request_data)) {
        foreach ($request_data as $list) {
            $category_url = $site . "blog/category/" . $list->slug;
            $hold .= '<span><a href=' . $category_url . ' class="text-primary">' . $list->category_name . '</a></span>';
        }
    }

    echo $hold;
}

function blog_post_process($post_content)
{
    $replaced_word =
        [
            '<p><br></p>',
        ];



    $replace_with   =
        [
            '',
        ];

    $new_content = str_replace($replaced_word, $replace_with, $post_content);

    return $new_content;
}

function file_path_process($path, $parms, $file)
{
    return $path . $parms . $file;
}
