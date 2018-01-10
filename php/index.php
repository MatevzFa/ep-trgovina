<?php

// enables sessions for the entire app
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

define("FORMS", realpath(dirname(__FILE__)) . "/view/forms/");
define("HEAD", realpath(dirname(__FILE__)) . "/view/components/head.component.php");
define("NAVBAR", realpath(dirname(__FILE__)) . "/view/components/pageheader.component.php");

define("BASE_URL", $_SERVER["SCRIPT_NAME"] . "/");
define("API_PREFIX", "api" . "/");
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/img/");
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");
define("BOOTSTRAP", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/bootstrap/");
define("METHOD", $_SERVER['REQUEST_METHOD']);

require_once("controller/IzdelkiController.php");
require_once("controller/UporabnikiController.php");
require_once("controller/NarocilaController.php");
require_once("controller/KosaricaController.php");
require_once("controller/APIController.php");

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
    "kosarica" => function () {
        KosaricaController::kosarica();
    },
    "kosarica/zakljuci" => function () {
        KosaricaController::zakljuci();
    },
    "izdelki-add" => function () {
        IzdelkiController::dodajIzdelek();
    },
    "administrator-nadzorna-plosca" => function () {
        UporabnikiController::administratorNadzornaPlosca();
    },
    "prodajalec-nadzorna-plosca" => function () {
        UporabnikiController::prodajalecNadzornaPlosca();
    },
    "registracija" => function () {
        UporabnikiController::registracija();
    },
    "registracija-captcha" => function() {
        UporabnikiController::registracijaCaptcha();
    },
    "registracija-prodajalec" => function () {
        UporabnikiController::registracijaProdajalca();
    },
    "prikaz-uporabnikov" => function () {
        UporabnikiController::prikaziVseUporabnikeZVlogo();
    },
    "prikaz-izdelkov-cmp" => function () {
        IzdelkiController::prikaziVseIzdelke();
    },
    "urejanje-izdelka" => function () {
        IzdelkiController::urejanjeIzdelka();
    },
    "izbrisi-sliko" => function () {
        IzdelkiController::izbrisiSliko();
    },
    "dodaj-sliko-izdelku" => function () {
        IzdelkiController::dodajSlikoIzdelku();
    },
    "deaktiviraj-uporabnika" => function () {
        UporabnikiController::deaktivirajUporabnika();
    },
    "aktiviraj-uporabnika" => function () {
        UporabnikiController::aktivirajUporabnika();
    },
    "oceni-izdelek" => function () {
        IzdelkiController::oceniIzdelek();
    },
    "aktivacija-izdelka" => function () {
        IzdelkiController::aktivirajIzdelek();
    },
    "deaktivacija-izdelka" => function () {
        IzdelkiController::deaktivirajIzdelek();
    },
    "narocila" => function () {
        NarocilaController::narocila();
    },
    "narocila-list" => function () {
        NarocilaController::vsaNarocilaStanje();
    },
    "spremeni-stanje-narocila" => function () {
        NarocilaController::spremeniStanjeNarocila();
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
    "urejanje-stranka-control-panel" => function () {
        UporabnikiController::urejanjeIzCMPstranka();
    },
    "urejanje-zaposleni-control-panel" => function () {
        UporabnikiController::urejanjeIzCMPzaposleni();
    },
    "urejanje-stranke" => function () {
        UporabnikiController::urejanjeStranke();
    },
    "urejanje-zaposleni" => function () {
        UporabnikiController::urejanjeZaposlenih();
    },
    "spremeni-geslo" => function () {
        UporabnikiController::spremeniGeslo();
    },
    "x509login" => function () {
        UporabnikiController::x509Prijava();
    },
//  API controllers
    API_PREFIX . "izdelki" => function () {
        APIController::izdelki();
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
