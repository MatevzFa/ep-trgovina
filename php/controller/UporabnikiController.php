<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/UporabnikDB.php");
require_once(FORMS . "RegistracijaForm.php");
require_once(FORMS . "PrijavaForm.php");
require_once(FORMS . "DodajProdajalcaForm.php");

class UporabnikiController extends AbstractController {

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

    // administrator lahko 'registrira' novega prodajalca
    public static function registracijaProdajalca() {

        $form = new DodajProdajalcaForm("registracija-prodajalec");

        if ($form->validate()) {
            $novProdajalec = $form->getValue();
            $novProdajalec['vloga'] = 'prodajalec';
            UporabnikDB::dodajUporabnika($novProdajalec);
            ViewHelper::redirect(BASE_URL);
        } else {
            echo ViewHelper::render("view/registracija-prodajalec.php", [
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

            $email = array(
                "email" => $uporabnik['email']
            );


            $idInVlogaUporabnika = UporabnikDB::pridobiIdInVlogo($email);
            // najprej preveri ali uporabnik sploh obstaja
            if ($idInVlogaUporabnika != null) {
                $pravilnoGeslo = UporabnikDB::preveriGeslo($idInVlogaUporabnika['id'], $geslo);
                // tukaj lahko preveriva ali je uporabnik deaktiviran in ga ne prijaviva?
                if ($pravilnoGeslo) {
                    session_regenerate_id();
                    $_SESSION['user_id'] = $idInVlogaUporabnika['id'];
                    $_SESSION['user_vloga'] = $idInVlogaUporabnika['vloga'];

                    if (isset($_SESSION['post_login_redirect'])) {
                        ViewHelper::redirect(BASE_URL . $_SESSION['post_login_redirect']);
                    } else {
                        ViewHelper::redirect(BASE_URL);
                    }
                } else {
                    ViewHelper::redirect(BASE_URL . "prijava");
                }
            } else { // uporabnik ne obstaja
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
            $uporabnik = UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']));
            if (isset($uporabnik)) {
                var_dump($uporabnik);
            } else {
                echo ViewHelper::redirect(BASE_URL . "prijava");
            }
        } else {
            $_SESSION['post_login_redirect'] = "profil";
            echo ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    //prikaz vseh uporabnikov z doloceno vlogo. Uporaba pri nadzornih ploscah
    public static function prikaziVseUporabnikeZVlogo() {
        $rules = [
            "vloga" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_GET, $rules);
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/uporabniki-list.php", [
                "uporabniki" => UporabnikDB::vsiUporabnikiZVlogo($data)
                    ]
            );
        } else {
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
    }

    //   izbrisi = deaktiviraj uporabnika
    public static function deaktivirajUporabnika() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            UporabnikDB::deaktivirajUporabnika($data);
        } else {
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
        ViewHelper::redirect(BASE_URL . "izdelki");
    }

    public static function prodajalecNadzornaPlosca() {
        // TODO preveri ce je prodajalec ?
        if (isset($_SESSION['user_id'])) {
            $uporabnik = UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']));
            if (isset($uporabnik)) {
                echo ViewHelper::render("view/prodajalec-nadzorna-plosca.php");
            } else {
                echo ViewHelper::redirect(BASE_URL . "prijava");
            }
        } else {
            $_SESSION['post_login_redirect'] = "profil";
            echo ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    public static function administratorNadzornaPlosca() {
        //TODO preveri ce je administrator/
        if (isset($_SESSION['user_id'])) {
            $uporabnik = UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']));
            if (isset($uporabnik)) {
                echo ViewHelper::render("view/administrator-nadzorna-plosca.php");
            } else {
                echo ViewHelper::redirect(BASE_URL . "prijava");
            }
        } else {
            $_SESSION['post_login_redirect'] = "profil";
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
