<?php
$request_uri = $_SERVER['REQUEST_URI'] ?? "index.php";
if (($position = strpos($request_uri, '.php'))) {
    $uri = substr($request_uri, $position, strlen($request_uri));
    $request_uri = str_replace($uri, '', $request_uri);
}

$content = resolveRoute(
    $request_uri,
    strtolower($_SERVER['REQUEST_METHOD']),
    ROOT_DIR . DS . "routes"
);

echo str_replace("%SCRIPT%", embed_script_to("/public/assets/bundle.js"), $content);
