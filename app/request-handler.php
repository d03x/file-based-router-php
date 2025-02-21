<?php
$request_uri = $_SERVER['REQUEST_URI'] ?? "index.php";
if (($position = strpos($request_uri, '.php'))) {
    $uri = substr($request_uri, $position, strlen($request_uri));
    $request_uri = str_replace($uri, '', $request_uri);
}

handleRoute(
    $request_uri,
    $_SERVER['REQUEST_METHOD'],
    ROOT_DIR . DS . "app" . DS . "pages"
);
