
define("ENTRY_DIR", ROOT_DIR . DS . "app" . DS . "pages");
$request_method = $_SERVER['REQUEST_METHOD'];

function handleRequest($url, $request_method)
{
    $uris = explode('/', $url);
    $REQ_DEPTH = count($uris);
    $destinationPath = ENTRY_DIR;
    $currentType = 1;
    $notfound = false;


    $request_method = strtolower($request_method);

    $validRequestMethod = ['get', 'post', 'delete', 'put', 'patch'];
    if (!in_array($request_method, $validRequestMethod)) {
        $REQ_DEPTH = 0;
    }
    for ($r = 0; $r < $REQ_DEPTH; $r++) {
        $part = $uris[$r];
        if ($part == '') continue;
        $mov = $destinationPath . DS . $part;

        $with_extenstion = $mov . '.' . $request_method . ".php";
        if (file_exists($with_extenstion)) {
            $destinationPath = $with_extenstion;
            $currentType = 0;
            $notfound = $r != $REQ_DEPTH - 1;
            break;
        } else if (is_dir($mov) == 1) {
            $destinationPath = $mov;
            $currentType = 1;
        } else {
            $is_slug_found = false;
            $is_slugged  = false;
            $scanned = scandir($destinationPath);
            for ($i = 2, $x = count($scanned); $i < $x; $i++) {
                if (str_contains($scanned[$i], '[')) {
                    if (str_contains($scanned[$i], '') === false) {
                        $_GET[substr($scanned[$i], 1, -1)] = $part;
                        $destinationPath = $destinationPath . DS . $scanned[$i];
                        $currentType = 1;
                    } else if ($r == $REQ_DEPTH - 1) {
                        $filename = explode('.', $scanned[$i])[0];
                        $_GET[substr($filename, 1, -1)] = $part;
                        $destinationPath = $destinationPath.DS.$filename.'.'.$request_method.".php";
                        $currentType = 0;
                        $is_slugged = true;

                    }
                    $is_slug_found = true;
                    break;
                }
            }
        }
    }
    echo $destinationPath;
}


handleRequest(ltrim($request_uri, '/'), $request_method);
