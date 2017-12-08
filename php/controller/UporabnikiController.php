<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/UporabnikDB.php");
require_once(FORMS . "RegistracijaForm.php");
require_once(FORMS . "PrijavaForm.php");

class UporabnikiController extends AbstractController {

    /**
     * tukaj notri poskrbimo za registracijo, prijavo in update?
     * ali poseben controller za vsako od teh?
     */
    public static function uporabnik() {
        
    }

    public static function registracija() {

        $form = new RegistracijaForm("registracija");

        if ($form->validate()) {
            $novUporabnik = $form->getValue();
            $novUporabnik['vloga'] = 'stranka';
            UporabnikDB::dodajUporabnika($novUporabnik);
            ViewHelper::redirect(BASE_URL);
        } else {
            echo ViewHelper::render("view/registracija.php", [
                "form" => $form
            ]);
        }
    }

    public static function prijava() {

        $form = new PrijavaForm("prijava");

        if ($form->validate()) {
            $uporabnik = $form->getValue();

            $email = $uporabnik['email'];
            $geslo = $uporabnik['geslo'];

            $idUporabnika = UporabnikDB::pridobiId($email);

            $pravilnoGeslo = UporabnikDB::preveriGeslo($idUporabnika, $geslo);

            if ($pravilnoGeslo) {
                session_regenerate_id();
                $_SESSION['user_id'] = $idUporabnika;
                if (isset($_SESSION['goto'])) {
                    ViewHelper::redirect(BASE_URL . $_SESSION['goto']);
                } else {
                    ViewHelper::redirect(BASE_URL);
                }
            } else {
                ViewHelper::redirect(BASE_URL . "prijava");
            }
        } else {
            echo ViewHelper::render("view/prijava.php", [
                "form" => $form
            ]);
        }
    }

    public static function odjava() {
        session_destroy();
        ViewHelper::redirect(BASE_URL);
    }

    public static function profil() {

        if (isset($_SESSION['user_id'])) {
            $uporabnik = UporabnikDB::get(array('id' => $_SESSION['user_id']));
            if (isset($uporabnik)) {
                var_dump($uporabnik);
            } else {
                echo ViewHelper::redirect(BASE_URL . "prijava");
            }
        } else {
            $_SESSION['goto'] = "profil";
            echo ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    /**
     * Returns an array of filtering rules for manipulation books
     * @return type
     */
    private static function getRules() {
        return [
            'ime' => FILTER_SANITIZE_SPECIAL_CHARS,
            'priimek' => FILTER_SANITIZE_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
            'geslo' => FILTER_DEFAULT,
            'naslov' => FILTER_SANITIZE_SPECIAL_CHARS,
            'telefon' => FILTER_SANITIZE_SPECIAL_CHARS
        ];
    }

}
