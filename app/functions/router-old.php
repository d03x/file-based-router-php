<?php

enum TypeRoute: int
{
    case DIR = 1;
    case FILE = 0;
}

function handleRoute(string $url, string $request_method, string $destination_path)
{
    $REQUEST_URI = explode('/', $url);
    $REQUEST_DEEPH = count($REQUEST_URI);


    $destination_path = $destination_path;
    $current_type = TypeRoute::DIR->value;
    $is_not_found = false;

    $middleware_path = false;
    $middleware_filename = "+middleware.php";
    $middleware_global_name = "+middleware.global.php";
    $registered_middlewares = [];
    $request_method_allowed = [
        'get',
        'delete',
        'put',
        'post',
        'patch'
    ];

    $request_method = strtolower($request_method);
    if (!in_array($request_method, $request_method_allowed)) {
        $REQUEST_DEEPH = 0;
    }

    for ($i = 0; $i < $REQUEST_DEEPH; $i++) {
        $part = $REQUEST_URI[$i];
        $global_middleware = $destination_path . DS . $middleware_global_name;
        if (file_exists($global_middleware)) {
            $registered_middlewares[] = $global_middleware;
        }
        $moving_path = $destination_path . DS . $part;
        if (file_exists($destination_path . DS . $middleware_filename)) {
            $middleware_path = $destination_path . DS . $middleware_filename;
        }
        if (file_exists($moving_path . DS . $middleware_filename)) {
            $middleware_path = $moving_path . DS . $middleware_filename;
        }

        if ($part == '') continue;

        $with_extenstion = $moving_path . "." . $request_method . ".php";

        if (file_exists($with_extenstion)) {
            $destination_path = $with_extenstion;
            $current_type = TypeRoute::FILE->value;
            $is_not_found = $i != $REQUEST_DEEPH - 1;
            break;
        } else if (is_dir($moving_path)  == 1) {
            $destination_path = $moving_path;
            $current_type = TypeRoute::DIR->value;
        } else {
            $is_not_found = false;
            $is_sluged = false;
            $is_slug_found = false;
            $scaned_dir = scandir($destination_path);
            for ($f = 2,  $x = count($scaned_dir); $f < $x; $f++) {
                if (str_contains($scaned_dir[$f], '[')) {
                    //slug folder
                    if (str_contains($scaned_dir[$f], '.') == false) {
                        $_GET[substr($scaned_dir[$f], 1, -1)] = $part;
                        $destination_path = $destination_path . DS . $scaned_dir[$f];
                        $current_type = TypeRoute::DIR->value;
                        //slug fle
                    } else if ($f === $REQUEST_DEEPH - 1) {
                        $filename = explode('.', $scaned_dir[$f])[0];
                        $_GET[substr($filename, 1, -1)] = $part;
                        $destination_path = $destination_path . DS . $filename . "." . $request_method . ".php";
                        $is_sluged = true;
                        $current_type = TypeRoute::FILE->value;
                    }
                    $is_slug_found = true;
                    break;
                }
            }
            //jika slug tidak ada maka not found
            $is_not_found = !$is_slug_found;
            if ($is_sluged || $is_not_found) {
                break;
            }
        }
    }
    $indexfile  = 'index.' . $request_method . ".php";
    if ($current_type == TypeRoute::DIR->value && file_exists($destination_path . DS . $indexfile)) {
        $destination_path .= DS . $indexfile;
    }

    if (is_file($middleware_path)) {
        include $middleware_path;
    }

    if (is_file($destination_path)) {
        require_once($destination_path);
    } else {
        echo "404 not found";
    }
}
