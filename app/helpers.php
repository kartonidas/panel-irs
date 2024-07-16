<?php

if (!function_exists("a"))
{
    function a($file)
    {
        $time = "";
        if(file_exists(public_path($file)))
            $time = filemtime(public_path($file));

        return $time ? ($file . "?t=" . $time) : $file;
    }
}

if (!function_exists("meta_desc"))
{
    function meta_desc()
    {
        $settings = \App\Models\Setting::byNames("meta_description");
        return $settings["meta_description"];
    }
}

if (!function_exists("meta_title"))
{
    function meta_title($title = null, $default = null)
    {
        if(empty(trim($title)))
            $title = $default;
        
        if(empty(trim($title)))
        {
            $settings = \App\Models\Setting::byNames("meta_title");
            $title = $settings["meta_title"];
        }
        return $title;
    }
}

if (!function_exists("amount"))
{
    function amount($amount)
    {
        return \App\Libraries\Helper::amount($amount);
    }
}