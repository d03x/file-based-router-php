<?php

enum TypeRoute: int
{
    case DIR = 1;
    case FILE = 0;
}

function handleRoute(string $url, string $request_method, string $destination_path)
{
    $parts = array_filter(explode('/', $url));


    $request_method = strtolower($request_method);
    $allowed_methods = ['get', 'delete', 'put', 'post', 'patch'];
    if (!in_array($request_method, $allowed_methods)) return;

    $middleware_file = "+middleware.php";
    $global_middleware = "+middleware.global.php";
    $middleware_path = false;

    foreach ($parts as $part) {
        if (file_exists($destination_path . "/$global_middleware")) {
            $middleware_path = $destination_path . "/$global_middleware";
        }
        if (file_exists($destination_path . "/$middleware_file")) {
            $middleware_path = $destination_path . "/$middleware_file";
        }

        $file_path = "$destination_path/$part.$request_method.php";
        $dir_path = "$destination_path/$part";

        if (file_exists($file_path)) {
            $destination_path = $file_path;
            break;
        } elseif (is_dir($dir_path)) {
            $destination_path = $dir_path;
        } else {
            foreach (scandir($destination_path) as $item) {
                if (str_starts_with($item, "[") && str_ends_with($item, "]")) {
                    $_GET[trim($item, "[]")] = $part;
                    $destination_path = "$destination_path/$item";
                    break 2;
                }
            }
            echo "404 Not Found";
            return;
        }
    }

    if (is_dir($destination_path) && file_exists("$destination_path/index.$request_method.php")) {
        $destination_path .= "/index.$request_method.php";
    }

    if ($middleware_path) include $middleware_path;
    if (is_file($destination_path)) require_once $destination_path;
    else echo "404 Not Found";
}
