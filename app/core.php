<?php

/**
 * @author dadan hidayat
 */
defined("SCRIPTS") or die("No allowed accse this script");
define("DS", DIRECTORY_SEPARATOR);
define("ROOT_DIR", dirname(__DIR__));
define("COMPOSER_FILE", ROOT_DIR . DS . "vendors" . DS . "autoload.php");
if (file_exists(COMPOSER_FILE)) {
    die(COMPOSER_FILE . " Not found! Please contact adminitrator web");
}
include_once(COMPOSER_FILE);
//start session for global
if (!session_id()) {
    session_start();
    session_set_cookie_params([
        'name' => "skeep_session",
    ]);
}
//include('functions/router.php');
include("functions/requests/router.php");
//for handler all request yng masuk
include_once("request-handler.php");
