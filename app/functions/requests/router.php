<?php

function embed_script_to($file)
{
    return <<<EOF
     <!-- Server Script Embeded -->
         <script src='{$file}'></script>
      <!-- END:Server script embeded -->
    EOF;
}

function resolveRoute(string $url, string $request_method, string $base_path)
{
    $segments = array_filter(explode('/', $url));
    $current_path  = $base_path;
    $params = [];

    $allowed_method = ['get', 'post', 'patch', 'put', 'delete'];
    if (!in_array($request_method, $allowed_method)) {
        echo "REQUEST $request_method Not allowed";
        return;
    }

    foreach ($segments as  $segment) {
        $found = false;
        foreach (scandir($current_path) as $item) {
            if ($item == '.' || $item === '..') continue;
            if ($segment == $item && is_dir($current_path . DS . $item)) {
                $current_path .= DS . $item;
                $found = true;
                break;
            }
            if (preg_match('/\[(.*?)\]/', $item, $mathes)) {
                $params[$mathes[1]] = $segment;
                $current_path .= DS . $item;
                $found = true;
                break;
            }
        }
    }

    $filename = $current_path;
    if (!is_file($current_path)) {
        $filename = $current_path . DS . "index.$request_method.php";
    }
    $_GET = array_merge($_GET, $params);
    if (file_exists($filename)) {
        ob_start();
        require $filename;
        return ob_get_clean();
    } else {
        echo "Page Not Found";
    }
    return;
}
