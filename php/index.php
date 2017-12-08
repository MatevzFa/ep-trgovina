<?php

// enables sessions for the entire app
session_start();

define("FORMS", realpath(dirname(__FILE__)) . "/view/forms/");
define("HEAD", realpath(dirname(__FILE__)) . "/view/components/head.component.php");
define("NAVBAR", realpath(dirname(__FILE__)) . "/view/components/pageheader.component.php");

define("BASE_URL", $_SERVER["SCRIPT_NAME"] . "/");
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/img/");
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");
define("BOOTSTRAP", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/bootstrap/");

require_once("controller/IzdelkiController.php");
require_once("controller/UporabnikiController.php");

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

/* Uncomment to see the contents of variables */
//var_dump(FORMS);
//var_dump(IMAGES_URL);
//var_dump(CSS_URL);
//var_dump($path);
//exit();

// ROUTER: defines mapping between URLS and controllers
$urls = [
    "izdelki" => function () {
        IzdelkiController::izdelki();
    },
    "izdelki/add" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            IzdelkiController::add();
        } else {
            IzdelkiController::addForm();
        }
    },   
    "registracija" => function () {
        UporabnikiController::registracija();
    },
    "prijava" => function () {
        UporabnikiController::prijava();
    },
    "odjava" => function () {
        UporabnikiController::odjava();
    },
    "profil" => function () {
        UporabnikiController::profil();
    },
    "" => function () {
        ViewHelper::redirect(BASE_URL . "izdelki");
    },
];

try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
        echo "No controller for '$path'";
    }
} catch (InvalidArgumentException $e) {
    ViewHelper::error404();
} catch (Exception $e) {
    echo "An error occurred: <pre>$e</pre>";
} 
