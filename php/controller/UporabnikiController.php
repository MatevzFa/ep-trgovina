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
                UporabnikDB::dodajUporabnika($novUporabnik);
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
            // prijavi
            $uporabnik = $form->getValue();

            $email = $uporabnik['email'];
            $geslo = $uporabnik['geslo'];

            $email = array(
                "email" => $uporabnik['email']
            );

            // preveri ali obstaja email, ali se bo treba se registrirat
            if (UporabnikDB::aliEmailZeObstaja($email["email"])) {
                $idInVlogaUporabnika = UporabnikDB::pridobiIdInVlogo($email);
                // najprej preveri ali uporabnik sploh obstaja
                if ($idInVlogaUporabnika != null) {

                    $pravilnoGeslo = UporabnikDB::preveriGeslo($idInVlogaUporabnika['id'], $geslo);
                    // tukaj lahko preveriva ali je uporabnik deaktiviran in ga ne prijaviva?
                    if (UporabnikDB::aliJeAktiviran($idInVlogaUporabnika)) {
                        if ($pravilnoGeslo) {
                            session_regenerate_id();
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
    //                        echo ViewHelper::alert('Napačno geslo', BASE_URL . 'prijava');
                            echo "<script>alert('Napačno geslo.');</script>";
                        }
                    } else {
                        echo "<script>alert('Uporabnik je deaktiviran.');</script>";
                    }
                } else {
                    echo "<script>alert('Napacen e-mail naslov.');</script>";
                }
            } else { // email ne obstaja...treba se registirat
                echo "<script>alert('Email ne obstaja. Registrirajte se.');
                        window.location.href='" . BASE_URL . "registracija" . "';</script>"; 
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
                        session_regenerate_id();
                        $_SESSION['user_id'] = $idInVlogaUporabnika['id'];
                        $_SESSION['user_vloga'] = $idInVlogaUporabnika['vloga'];
                        if (isset($_SESSION['post_login_redirect'])) {

                            $redirectUrl = $_SESSION['post_login_redirect'];
                            unset($_SESSION['post_login_redirect']);
                            ViewHelper::redirect(BASE_URL . $redirectUrl);
                        } else {
                            ViewHelper::redirect(BASE_URL . $_SESSION['user_vloga'] . '-nadzorna-plosca');
                        }
                    } else {
                        echo "<script>alert('Napacno geslo.');</script>";
                    }
                } else {
                    echo "<script>alert('Uporabnik je deaktiviran.');</script>";
                }
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
            UporabnikDB::urejanjeZaposlenega($data);
            if ($data['id'] == $_SESSION['user_id']) {
                echo "<script>alert('Osebni podatki so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                echo "<script>alert('Osebni podatki stranke so bili uspesno spremenjeni.');
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
            UporabnikDB::urejanjeStranke($data);
            // edit je bil storjen iz uporabnikovega profila
            if ($data['id'] == $_SESSION['user_id']) {
                echo "<script>alert('Osebni podatki so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                echo "<script>alert('Osebni podatki stranke so bili uspesno spremenjeni.');
                        window.location.href='" . BASE_URL . "urejanje-stranka-control-panel?id=" . $data['id'] . "';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "profil");
        }
    }

    // Lahko spremeni geslo brez da pozna prejsnje geslo
    public static function spremeniGesloZaposleni() {
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
                echo "<script>alert('Geslo je bilo spremenjeno.');
                    window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //sprememba gesla storjena iz nadzorne plosce
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
            UporabnikDB::posodobiGeslo($data['id'], $data['geslo']);
            if ($data['id'] == $_SESSION['user_id']) {
                echo "<script>alert('Geslo je bilo spremenjeno.');
                    window.location.href='" . BASE_URL . "profil" . "';</script>";
            } else { //sprememba gesla storjena iz nadzorne plosce
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
                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                    "options"=>array("regexp"=>"^stranka$")
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
        }  elseif ($_SESSION['user_vloga'] == 'administrator') { //administrator lahko ureja prodajalce (in stranke)
            $rules = [
                "vloga" => [
                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                    "options"=>array("regexp"=>"^(prodajalec|stranka)$")
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
        } else { // stranka mogoce
            ViewHelper::redirect(BASE_URL . "izdelki");
        }
    }

    public static function aktivirajUporabnika() {
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
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=prodajalec");
            } elseif ($data['oseba'] == 'stranka') {
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
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=prodajalec");
            } elseif ($data['oseba'] == 'stranka') {
                ViewHelper::redirect(BASE_URL . "prikaz-uporabnikov?vloga=stranka");
            } else {
                ViewHelper::redirect(BASE_URL . "izdelki");
            }
        } else {
            var_dump($_POST);
            //ViewHelper::redirect(BASE_URL . "izdelki");
        }
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
