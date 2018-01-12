<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/UporabnikDB.php");
require_once(FORMS . "RegistracijaForm.php");
require_once(FORMS . "PrijavaForm.php");
require_once(FORMS . "DodajProdajalcaForm.php");

class UporabnikiController extends AbstractController {

    public static function registracija() {
        if (isset($_SESSION['user_id'])) {
            $form = new RegistracijaForm("registracija");

            if ($form->validate()) {
                $novUporabnik = $form->getValue();
                $novUporabnik['vloga'] = 'stranka';
                // preveri ali email ze obstaja - error
                self::preveriVlogo('prodajalec');
                UporabnikDB::dodajUporabnika($novUporabnik);
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " je dodal novo stranko z email naslovom " . $novUporabnik['email']);
                ViewHelper::redirect(BASE_URL);
            } else {
                echo ViewHelper::render("view/registracija.php", [
                    "form" => $form
                ]);
            }
        } else { // uporabniki se registrirajo prek captche
            echo ViewHelper::render("view/registracija-nov-uporabnik.php");
        }
    }

    public static function registracijaCaptcha() {
        $rules = [
            "ime" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "priimek" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "email" => [
                'filter' => FILTER_SANITIZE_EMAIL
            ],
            "naslov" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "telefon" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "geslo" => [
                'filter' => FILTER_DEFAULT
            ]
        ];
        // ali je izpolnil form
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            // Captcha verification
            $secretCaptcha = '6LeIVT8UAAAAAAU0jQn2I3e9q5ABxuM--ZR-y0Am';

            // send a POST request for google to verify it
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $captchaRequest = array('secret' => $secretCaptcha, 'response' => $_POST['g-recaptcha-response']);

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($captchaRequest)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            // vrne json objekt, kjer je eden izmed atributov 'success'
            $decodedJson = json_decode($result);
            if ($decodedJson->success == 'true') { // uspesno opravjena captcha
                // ali email ze obstaja v bazi
                if (UporabnikDB::aliEmailZeObstaja($data['email'])) {
                    echo "<script>alert('Uporabnik s taksnim emailom ze obstaja');
                            window.location.href='" . BASE_URL . "registracija-nov-uporabnik" . "';</script>";
                } else { // vse ok, dodaj uporabnika
                    $data['vloga'] = 'stranka';
                    UporabnikDB::dodajUporabnika($data);
                    ViewHelper::redirect(BASE_URL . "prijava");
                }
            } else { //recaptcha se ni uspesno resila
                echo "<script>alert('Niste resili reCaptche.');
                     window.location.href='" . BASE_URL . "registracija-nov-uporabnik" . "';</script>";
            }
        } else { // sele prisel na link
            echo ViewHelper::render("view/registracija-nov-uporabnik.php");
        }
    }

    // administrator lahko 'registrira' novega prodajalca
    public static function registracijaProdajalca() {

        self::preveriVlogo('administrator');

        $form = new DodajProdajalcaForm("registracija-prodajalec");

        if ($form->validate()) {
            $novProdajalec = $form->getValue();
            $novProdajalec['vloga'] = 'prodajalec';
            UporabnikDB::dodajUporabnika($novProdajalec);
            self::administrator_log("Administrator " . $_SESSION['user_id'] .
                    " je dodal novega prodajalca z email naslovom " . $novUporabnik['email']);
            ViewHelper::redirect(BASE_URL);
        } else {
            echo ViewHelper::render("view/registracija-prodajalec.php", [
                "form" => $form
            ]);
        }
    }

    public static function secureCookie() {

        session_regenerate_id();
    }

    public static function prijava() {

        $form = new PrijavaForm("prijava");

        if ($form->validate()) {
            // prijavi
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
                if (UporabnikDB::aliJeAktiviran($idInVlogaUporabnika)) {
                    if ($pravilnoGeslo) {
                        self::secureCookie();
                        $_SESSION['user_id'] = $idInVlogaUporabnika['id'];

                        // ker se ni prijavil z x509 nastavi vlogo na 'stranka'
                        $_SESSION['user_vloga'] = 'stranka';

                        if (isset($_SESSION['post_login_redirect'])) {
                            $redirectUrl = $_SESSION['post_login_redirect'];
                            unset($_SESSION['post_login_redirect']);
                            ViewHelper::redirect(BASE_URL . $redirectUrl);
                        } else {
                            ViewHelper::redirect(BASE_URL);
                        }
                    } else {
                        echo ViewHelper::alert('Napačen e-mail naslov ali geslo.', 'prijava');
                    }
                } else {
                    echo ViewHelper::alert('Uporabnik je deaktiviran.', 'prijava');
                }
            } else {
                echo ViewHelper::alert('Napačen e-mail naslov ali geslo.', 'prijava');
            }
        } else {
            // izriši login form
            echo ViewHelper::render("view/prijava.php", ["form" => $form]);
        }
    }

    public static function x509Prijava() {
        $client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");
        $cert_data = openssl_x509_parse($client_cert);

        $email = $cert_data['subject']['emailAddress'];
        $fingerprint = openssl_x509_fingerprint($client_cert);

        $form = new PrijavaForm("prijava");
        $form->email->setValue($email);
        $form->email->toggleFrozen(true);


        if ($form->validate()) {
            $uporabnik = $form->getValue();

            $email = $uporabnik['email'];
            $geslo = $uporabnik['geslo'];

            echo $email;
            echo $geslo;

            $email = array(
                "email" => $uporabnik['email']
            );
            $idInVlogaUporabnika = UporabnikDB::pridobiIdInVlogo($email);

            // najprej preveri ali uporabnik sploh obstaja
            if ($idInVlogaUporabnika != null) {


                // tukaj lahko preveriva ali je uporabnik deaktiviran in ga ne prijaviva?
                if (UporabnikDB::aliJeAktiviran($idInVlogaUporabnika)) {

                    $pravilnoGeslo = UporabnikDB::preveriGeslo($idInVlogaUporabnika['id'], $geslo);

                    if ($pravilnoGeslo) {
                        self::secureCookie();
                        $_SESSION['user_id'] = $idInVlogaUporabnika['id'];
                        $_SESSION['user_vloga'] = $idInVlogaUporabnika['vloga'];

                        if ($_SESSION['user_vloga'] == 'administrator') {
                            self::administrator_log("Administrator " . $_SESSION['user_id'] .
                                    " se je prijavil v sistem.");
                        } else {
                            self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                                    " se je prijavil v sistem.");
                        }

                        if (isset($_SESSION['post_login_redirect'])) {

                            $redirectUrl = $_SESSION['post_login_redirect'];
                            unset($_SESSION['post_login_redirect']);
                            ViewHelper::redirect(BASE_URL . $redirectUrl);
                        } else {
                            ViewHelper::redirect(BASE_URL . $_SESSION['user_vloga'] . '-nadzorna-plosca');
                        }
                    } else {
                        echo ViewHelper::alert('Napačen e-mail naslov ali geslo.', 'x509login');
                    }
                } else {
                    echo ViewHelper::alert('Uporabnik je deaktiviran.', 'x509login');
                }
            }
        } else {
            echo ViewHelper::render("view/prijava.php", [
                "form" => $form
            ]);
        }
    }

    public static function odjava() {

        if ($_SESSION['user_vloga'] == 'administrator') {
            self::administrator_log("Administrator " . $_SESSION['user_id'] .
                    " se je odjavil iz sistema.");
        } else {
            self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                    " se je odjavil iz sistema.");
        }

        session_regenerate_id();
        session_destroy();
        ViewHelper::redirect(BASE_URL);
    }

    public static function urejanjeZaposlenih() {

        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "ime" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "priimek" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "email" => [
                'filter' => FILTER_SANITIZE_EMAIL
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {

            if ($data['id'] == $_SESSION['user_id']) {
                self::preveriVlogo('prodajalec');
                UporabnikDB::urejanjeZaposlenega($data);
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " si je uredil profil.");
                echo "<script>alert('Osebni podatki so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                self::preveriVlogo('administrator');
                UporabnikDB::urejanjeZaposlenega($data);
                self::administrator_log("Administrator " . $_SESSION['user_id'] .
                        " je uredil prodajalca " . $data['id']);
                echo "<script>alert('Osebni podatki prodajalca so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "urejanje-zaposleni-control-panel?id=" . $data['id'] . "';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "profil");
        }
    }

    public static function urejanjeStranke() {

        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "ime" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "priimek" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "email" => [
                'filter' => FILTER_SANITIZE_EMAIL
            ],
            "naslov" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "telefon" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {

            // edit je bil storjen iz uporabnikovega profila
            if ($data['id'] == $_SESSION['user_id']) {
                UporabnikDB::urejanjeStranke($data);
                echo "<script>alert('Osebni podatki so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                self::preveriVlogo('prodajalec');
                UporabnikDB::urejanjeStranke($data);
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " je uredil profil uporabnika " . $data['id']);
                echo "<script>alert('Osebni podatki stranke so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "urejanje-stranka-control-panel?id=" . $data['id'] . "';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "profil");
        }
    }

    // Lahko spremeni geslo brez da pozna prejsnje geslo
    public static function spremeniGesloZaposleni() {

        self::preveriVlogo('prodajalec');

        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "geslo" => [
                'filter' => FILTER_DEFAULT
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            UporabnikDB::posodobiGeslo($data['id'], $data['geslo']);
            if ($data['id'] == $_SESSION['user_id']) {
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " si je spremenil geslo.");
                echo "<script>alert('Geslo je bilo spremenjeno.');
                    window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //sprememba gesla storjena iz nadzorne plosce
                self::administrator_log("Administrator " . $_SESSION['user_id'] .
                        " je spremenil geslo prodajalcu " . $data['id']);
                echo "<script>alert('Geslo uporabnika spremenjeno.');
                        window.location.href='" . BASE_URL . "urejanje-zaposleni-control-panel?id=" . $data['id'] . "';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "profil");
        }
    }

    public static function spremeniGeslo() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "geslo" => [
                'filter' => FILTER_DEFAULT
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            if ($data['id'] == $_SESSION['user_id']) { // stranka sama sebi spremeni
                UporabnikDB::posodobiGeslo($data['id'], $data['geslo']);
                echo "<script>alert('Geslo je bilo spremenjeno.');
                    window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //sprememba gesla storjena iz nadzorne plosce - iz strani prodajalca
                self::preveriVlogo('prodajalec');
                UporabnikDB::posodobiGeslo($data['id'], $data['geslo']);
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " je spremenil geslo stranki " . $data['id']);
                echo "<script>alert('Geslo uporabnika spremenjeno.');
                        window.location.href='" . BASE_URL . "urejanje-stranka-control-panel?id=" . $data['id'] . "';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "profil");
        }
    }

    public static function urejanjeIzCMPstranka() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ]
        ];
        $data = filter_input_array(INPUT_GET, $rules);
        $uporabnik = UporabnikDB::get($data);
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/urejanje-stranka.php", [
                "podatki" => $uporabnik
                    ]
            );
        } else {
            echo ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    public static function urejanjeIzCMPzaposleni() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ]
        ];
        $data = filter_input_array(INPUT_GET, $rules);
        $uporabnik = UporabnikDB::get($data);
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/urejanje-zaposleni.php", [
                "podatki" => $uporabnik
                    ]
            );
        } else {
            echo ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    public static function profil() {

        if (isset($_SESSION['user_id'])) {
            $uporabnik = UporabnikDB::get(array('id' => $_SESSION['user_id']));
            if (isset($uporabnik)) {
                //prikaz profila uporabika
                if ($uporabnik['vloga'] == 'stranka') {
                    echo ViewHelper::render("view/urejanje-stranka.php", [
                        "podatki" => $uporabnik
                            ]
                    );
                } else {
                    echo ViewHelper::render("view/urejanje-zaposleni.php", [
                        "podatki" => $uporabnik
                            ]
                    );
                }
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
        if ($_SESSION['user_vloga'] == 'prodajalec') { // prodajalec lahko ureja stranke
            $rules = [
                "vloga" => [
                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS
                ]
            ];
            $data = filter_input_array(INPUT_GET, $rules);

            $regexCheck = preg_grep("/^stranka$/", $data);
            if (self::checkValues($data) && $regexCheck != null) {
                echo ViewHelper::render("view/uporabniki-list.php", [
                    "uporabniki" => UporabnikDB::vsiUporabnikiZVlogo($data)
                        ]
                );
            } else {
                ViewHelper::redirect(BASE_URL . "izdelki");
            }
        } elseif ($_SESSION['user_vloga'] == 'administrator') { //administrator lahko ureja prodajalce (in stranke)
            $rules = [
                "vloga" => [
                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS
                ]
            ];
            $data = filter_input_array(INPUT_GET, $rules);

            $regexCheck = preg_grep("/^prodajalec$/", $data);
            if (self::checkValues($data) && $regexCheck != null) {
                echo ViewHelper::render("view/uporabniki-list.php", [
                    "uporabniki" => UporabnikDB::vsiUporabnikiZVlogo($data)
                        ]
                );
            } else {
                ViewHelper::redirect(BASE_URL . "izdelki");
            }
        } else { // stranka mogoce
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
    }

    public static function aktivirajUporabnika() {
        self::preveriVlogo('prodajalec');
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "oseba" => [
                'filer' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            UporabnikDB::aktivirajUporabnika($data);
            if ($data['oseba'] == 'prodajalec') {
                self::administrator_log("Administrator " . $_SESSION['user_id'] .
                        " je aktiviral prodajalca " . $data['id']);
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=prodajalec");
            } elseif ($data['oseba'] == 'stranka') {
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " je aktiviral stranko " . $data['id']);
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=stranka");
            } else {
                ViewHelper::redirect(BASE_URL . "izdelki");
            }
        } else {
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
    }

    //   izbrisi = deaktiviraj uporabnika
    public static function deaktivirajUporabnika() {
        self::preveriVlogo('prodajalec');
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "oseba" => [
                'filer' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            UporabnikDB::deaktivirajUporabnika($data);
            if ($data['oseba'] == 'prodajalec') {
                self::administrator_log("Administrator " . $_SESSION['user_id'] .
                        " je deaktiviral prodajalca " . $data['id']);
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=prodajalec");
            } elseif ($data['oseba'] == 'stranka') {
                self::prodajalec_log("Prodajalec " . $_SESSION['user_id'] .
                        " je deaktiviral stranko " . $data['id']);
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=stranka");
            } else {
                ViewHelper::redirect(BASE_URL . "izdelki");
            }
        } else {
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
    }

    public static function prodajalecNadzornaPlosca() {
        self::preveriVlogo('prodajalec');

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
        self::preveriVlogo('administrator');
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
