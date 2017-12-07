<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/UporabnikDB.php");
require_once("view/forms/RegistracijaForm.php");

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
