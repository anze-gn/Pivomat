<?php

// enables sessions for the entire app
session_start();

require_once("controller/PivaController.php");
require_once("controller/PivaRESTController.php");
require_once("controller/ProdajalciController.php");
require_once("controller/StrankeController.php");
require_once("controller/PrijavaRegistracijaController.php");
require_once("ViewHelper.php"); #REMOVE
require_once("Twig.php");
require_once("forms/CustomRenderer.php");

define("BASE_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php"));
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/images/"); #REMOVE
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/"); #REMOVE
define("STATIC_URL", BASE_URL . "static/");
define("DEBUG", true);

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";


$urls = [
    "/^piva$/" => function ($method) {
        PivaController::index();
    },
    "/^piva\/(\d+)$/" => function ($method, $id) {
        PivaController::get($id);
    },
    "/^piva\/(\d+)\.jpg$/" => function ($method, $id) {
        PivaController::getSlika($id);
    },
    "/^piva\/add$/" => function ($method) {
        PivaController::add();
    },
    "/^piva\/edit\/(\d+)$/" => function ($method, $id) {
        PivaController::edit($id);
    },
    "/^piva\/delete$/" => function ($method) {
        PivaController::delete();
    },
    "/^$/" => function () {
        ViewHelper::redirect(BASE_URL . "piva");
    },
    "/^stranke$/" => function ($method) {
        StrankeController::index();
    },
    "/^stranke\/(\d+)$/" => function ($method, $id) {
        StrankeController::get($id);
    },
    "/^stranke\/add$/" => function ($method) {
        StrankeController::add();
    },
    "/^stranke\/edit\/(\d+)$/" => function ($method, $id) {
        StrankeController::edit($id);
    },
    "/^stranke\/delete$/" => function ($method) {
        StrankeController::delete();
    },
    "/^registracija$/" => function ($method) {
        PrijavaRegistracijaController::registracija();
    },
    "/^prijava$/" => function ($method) {
        PrijavaRegistracijaController::prijava();
    },
    "/^potrditev.+$/" => function ($method) {
        PrijavaRegistracijaController::potrdiEmail();
    },
    "/^potrditev$/" => function ($method) {
        PrijavaRegistracijaController::potrdiEmail();
    },
    "/^prodajalci$/" => function ($method) {
        ProdajalciController::index();
    },
    "/^prodajalci\/(\d+)$/" => function ($method, $id) {
        ProdajalciController::get($id);
    },
    "/^prodajalci\/add$/" => function ($method) {
        ProdajalciController::add();
    },
    "/^prodajalci\/edit\/(\d+)$/" => function ($method, $id) {
        ProdajalciController::edit($id);
    },
    "/^prodajalci\/delete$/" => function ($method) {
        ProdajalciController::delete();
    },
    "/^admin$/" => function ($method) {
        ProdajalciController::admin();
    },
    "/^admin\/edit$/" => function ($method) {
        ProdajalciController::editAdmin();
    },
    "/^odjava$/" => function ($method) {
        session_destroy();
        ViewHelper::redirect(BASE_URL);
    },

    # REST API
    "/^api\/piva\/(\d+)$/" => function ($method, $id) {
        switch ($method) {
//            case "DELETE":
//                PivaRESTController::delete($id);
//                break;
//            case "PUT":
//                PivaRESTController::edit($id);
//                break;
            default: # GET
                PivaRESTController::get($id);
                break;
        }
    },
    "/^api\/piva$/" => function ($method) {
        switch ($method) {
//            case "POST":
//                PivaRESTController::add();
//                break;
            default: # GET
                PivaRESTController::index();
                break;
        }
    },
];

foreach ($urls as $pattern => $controller) {
    if (preg_match($pattern, $path, $params)) {
        try {
            $params[0] = $_SERVER["REQUEST_METHOD"];
            $controller(...$params);
        } catch (InvalidArgumentException $e) {
            ViewHelper::error404();
        } catch (Exception $e) {
            ViewHelper::displayError($e, true);
        }

        exit();
    }
}

ViewHelper::displayError(new InvalidArgumentException("No controller matched."), true);