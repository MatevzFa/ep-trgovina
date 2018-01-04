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
        echo ViewHelper::render("view/registracija-nov-uporabnik.php");
    }
    public static function registrirajUporabnikaCaptcha() {
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
	    ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            if (UporabnikDB::aliEmailZeObstaja($data['email'])) {
                echo "<script>alert('Uporabnik s taksnim emailom ze obstaja');
                        window.location.href='".BASE_URL . "registracija-nov-uporabnik"."';</script>";
            } else {
                $data['vloga'] = 'stranka';
                UporabnikDB::dodajUporabnika($data);
                echo "<script>alert('Registracija uspesna');
                            window.location.href='".BASE_URL . "izdelki"."';</script>";
            }
        } else {
            echo "<script>alert('Napaka');
                        window.location.href='".BASE_URL . "registracija-nov-uporabnik"."';</script>";
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
                if (UporabnikDB::aliJeAktiviran($idInVlogaUporabnika)) {
                    if ($pravilnoGeslo) {
                        session_regenerate_id();
                        $_SESSION['user_id'] = $idInVlogaUporabnika['id'];
                        $_SESSION['user_vloga'] = $idInVlogaUporabnika['vloga'];

                        if (isset($_SESSION['post_login_redirect'])) {
                            $redirectUrl = $_SESSION['post_login_redirect'];
                            unset($_SESSION['post_login_redirect']);
                            ViewHelper::redirect(BASE_URL . $redirectUrl);
                        } else {
                            ViewHelper::redirect(BASE_URL);
                        }

                    } else {
                        echo "<script>alert('Napacno geslo.');
                            window.location.href='".BASE_URL . "prijava"."';</script>";
                    }
                } else {
                    echo "<script>alert('Uporabnik je deaktiviran.');
                        window.location.href='".BASE_URL . "prijava"."';</script>";
                }
            } else { 
                echo "<script>alert('Napacen e-mail naslov.');
                        window.location.href='".BASE_URL . "prijava"."';</script>";
                //ViewHelper::redirect(BASE_URL . "prijava");
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
                        window.location.href='".BASE_URL . "profil"."';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                echo "<script>alert('Osebni podatki stranke so bili uspesno spremenjeni.');
                        window.location.href='".BASE_URL . "urejanje-zaposleni-control-panel?id=".$data['id']."';</script>";
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
                        window.location.href='".BASE_URL . "profil"."';</script>";
            } else { //edit bil storjen iz nadzorne plosce
                echo "<script>alert('Osebni podatki stranke so bili uspesno spremenjeni.');
                        window.location.href='".BASE_URL . "urejanje-stranka-control-panel?id=".$data['id']."';</script>";
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
                    window.location.href='".BASE_URL . "profil"."';</script>";  
            } else { //sprememba gesla storjena iz nadzorne plosce
                echo "<script>alert('Geslo uporabnika spremenjeno.');
                        window.location.href='".BASE_URL . "urejanje-zaposleni-control-panel?id=".$data['id']."';</script>";
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
                    window.location.href='".BASE_URL . "profil"."';</script>";  
            } else { //sprememba gesla storjena iz nadzorne plosce
                echo "<script>alert('Geslo uporabnika spremenjeno.');
                        window.location.href='".BASE_URL . "urejanje-stranka-control-panel?id=".$data['id']."';</script>";
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
        if ($_SESSION['user_vloga'] == 'prodajalec') {
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
        } else {
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
            } elseif($data['oseba'] == 'stranka') {
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
            } elseif($data['oseba'] == 'stranka') {
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
