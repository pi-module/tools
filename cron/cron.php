<?php

$siteUrl = 'http://local.envie-de-queyras.com/';
$cronToken = '5ae8c4083dc7d66b4aed5a57efcd5ab1'; // get from admin / tools module

function getUrlContent($url){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return ($httpcode>=200 && $httpcode<300) ? array('data' => $data, 'contentType' => $contentType) : false;
}

$data = getUrlContent(trim($siteUrl, '/') . '/tools/cron/index/token-' . $cronToken);

header('Content-type: ' . $data['contentType']);
echo $data['data'];
exit;