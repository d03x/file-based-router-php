<?php

use SkeepTalk\Platform\Engine\Registry;
use SkeepTalk\Platform\Engine\Router;
use SkeepTalk\Platform\Engine\Session;
use SkeepTalk\Platform\Engine\Template;

defined("SCRIPTS") or die("No allowed accse this script");
define("DS", DIRECTORY_SEPARATOR);
define("ROOT_DIR", dirname(__DIR__));
define("COMPOSER_FILE", ROOT_DIR . DS . "vendor" . DS . "autoload.php");

if (!file_exists(COMPOSER_FILE)) {
    die(COMPOSER_FILE . " Not found! Please contact adminitrator web");
}
include_once(COMPOSER_FILE);
//start session for global
\Dotenv\Dotenv::createImmutable(ROOT_DIR)->safeLoad();
//register all component registry
$registry = Registry::instance();
$registry->session = new Session;
//start session
$registry->session->start();
$registry->template = new Template;
$registry->config = require("app.config.php");
$registry->router = new Router($registry);
include "functions/global.php";
//render router content
$registry->router->resolve(
    strtolower($_SERVER['REQUEST_METHOD']),
    $registry->config['routes_directory']
);