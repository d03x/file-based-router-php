<?php

namespace SkeepTalk\Platform\Engine;

class Router
{
    protected $paramsPattern = '/\[(.*?)\]/';
    public Registry $app;
    public array $params = [];
    public function __construct(Registry $registry)
    {
        $this->app = $registry;
    }

    public function getRequestUri()
    {
        $request_uri = $_SERVER['REQUEST_URI'] ?? "index.php";
        if (($position = strpos($request_uri, '.php'))) {
            $uri = substr($request_uri, $position, strlen($request_uri));
            $request_uri = str_replace($uri, '', $request_uri);
        }
        return   $request_uri;
    }


    private function resolveDirectoryRouter(&$current_path, &$segment, &$found, &$params)
    {
        foreach (scandir($current_path) as $item) {
            if ($item == '.' || $item === '..') continue;
            if ($segment == $item && is_dir($current_path . DS . $item)) {
                $current_path .= DS . $item;
                $found = true;
                break;
            }
            if (preg_match($this->paramsPattern, $item, $mathes)) {
                $params[$mathes[1]] = $segment;
                $current_path .= DS . $item;
                $found = true;
                break;
            }
        }
        return $current_path;
    }
    function mregeToParams(array $data)
    {
        $this->params = array_merge($this->params, $data);
    }
    function resolve(string $request_method, string $base_path)
    {
        $url = $this->getRequestUri();
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
            $this->resolveDirectoryRouter(
                $current_path,
                $segment,
                $found,
                $params
            );
        }

        $filename = $current_path;
        if (!is_file($current_path)) {
            $filename = $current_path . DS . "index.$request_method.php";
        }
        $_GET = array_merge($_GET, $params);
        if (file_exists($filename)) {
            require $filename;
        } else {
            echo "Page Not Found";
        }
        return;
    }
}
