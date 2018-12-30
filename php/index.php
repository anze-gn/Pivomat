<?php

// enables sessions for the entire app
session_start();

require_once("controller/PivoController.php");

define("BASE_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php"));
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/images/");
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");
define("DEBUG", true);

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

// ROUTER:
$urls = [
    "piva" => function () {
        PivoController::index();
    },
    "piva/add" => function () {
        PivoController::add();
    },
    "piva/edit" => function () {
        PivoController::edit();
    },
    "piva/delete" => function () {
        PivoController::delete();
    },
    "" => function () {
        ViewHelper::redirect(BASE_URL . "piva");
    },
];

try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
        ViewHelper::error404();
    }
} catch (InvalidArgumentException $e) {
    ViewHelper::error404();
} catch (Exception $e) {
    ViewHelper::displayError($e, DEBUG);
}