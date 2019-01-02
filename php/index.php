<?php

// enables sessions for the entire app
session_start();

require_once("controller/PivoController.php");
require_once("controller/ProdajalciController.php");
require_once("controller/StrankeController.php");
require_once("controller/PrijavaRegistracijaController.php");

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
    "prodajalci" => function () {
        ProdajalciController::index();
    },
    "prodajalci/add" => function () {
        ProdajalciController::add();
    },
    "prodajalci/edit" => function () {
        ProdajalciController::edit();
    },
    "prodajalci/delete" => function () {
        ProdajalciController::delete();
    },
    "admin" => function () {
        ProdajalciController::admin();  
    },
    "admin/edit" => function () {
        ProdajalciController::editAdmin();
    },
    "stranke" => function () {
        StrankeController::index();
    },
    "stranke/add" => function () {
        StrankeController::add();
    },
    "stranke/edit" => function () {
        StrankeController::edit();
    },
    "stranke/delete" => function () {
        StrankeController::delete();
    },
    "registracija" => function () {
        PrijavaRegistracijaController::registracija();
    },
    "prijava" => function () {
        PrijavaRegistracijaController::prijava();
    }
];

try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
        ViewHelper::error404();
    }
} catch (InvalidArgumentException $e) {
    ViewHelper::displayError($e, DEBUG);
} catch (Exception $e) {
    ViewHelper::displayError($e, DEBUG);
}