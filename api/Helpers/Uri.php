<?php
namespace Helpers;

final class Uri
{    
    public function getAppUri()
    {
    $request_uri = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $app_uri = parse_url($request_uri, PHP_URL_SCHEME)."://".parse_url($request_uri, PHP_URL_HOST).parse_url($request_uri, PHP_URL_PATH);
    return $app_uri;    
    }
}