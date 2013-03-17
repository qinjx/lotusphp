<?php
/**
 * Web server gateway
 */
define("LOTUS_UNITTEST_DEBUG", true);
function callWeb($url, $post = null, $header = null, $returnHeader = false)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, LOTUS_UNITTEST_WEB_ROOT . $url);
    curl_setopt($ch, CURLOPT_HEADER, $returnHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $header[] = "Expect:";//阻止lighttpd返回417错误
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $reponse = curl_exec($ch);

    if (LOTUS_UNITTEST_DEBUG)
    {
        echo $reponse;
    }
    curl_close($ch);
    return $reponse;
}

function formatSize($size)
{
    if ($size >= 1073741824)
    {
        $size = round($size / 1073741824, 2) . ' GB';
    }
    else if ($size >= 1048576)
    {
        $size = round($size / 1048576, 2) . ' MB';
    }
    else if ($size >= 1024)
    {
        $size = round($size / 1024, 2) . ' KB';
    }
    else
    {
        $size = round($size, 2) . ' Bytes';
    }
    return $size;
}