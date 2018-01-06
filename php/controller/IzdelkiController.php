<?php

require_once('AbstractController.php');
require_once('ViewHelper.php');
require_once('model/db/IzdelekDB.php');


class IzdelkiController extends AbstractController {

    public static function izdelki() {
        $rulesDetail = [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $dataDetail = filter_input_array(INPUT_GET, $rulesDetail, TRUE);
        if (self::checkValues($dataDetail)) {
            echo ViewHelper::render('view/izdelki-detail.php', [
                'izdelek' => IzdelekDB::pridobiZOceno($dataDetail),
                'slike' => IzdelekDB::pridobiSlike($dataDetail)
                    ]
            );
        } else {

            $offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT, array('options' => array('default' => 0)));
            $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, array('options' => array('default' => 18)));

            echo ViewHelper::render('view/izdelki-list.php', [
//                'izdelki' => IzdelekDB::pridobiZOstranjevanjem($dataList),
                'izdelki' => IzdelekDB::pridobiZOstranjevanjem($offset, $limit),
                'stIzdelkov' => IzdelekDB::pridobiStIzdelkov(),
                'kosarica' => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
            ]);
        }
    }
    
    
    public static function urejanjeIzdelka() {
        $rules = [
	    "ime" => [
		'filter' => FILTER_SANITIZE_SPECIAL_CHARS
	    ],
            "opis" => [
		'filter' => FILTER_SANITIZE_SPECIAL_CHARS
	    ],
            "cena" => [
		'filter' => FILTER_VALIDATE_FLOAT
	    ],
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];
        // ali je izpolnil form
        // spremeni v float, ker pride kot string

        $_POST['cena'] = (float)$_POST['cena'];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            IzdelekDB::update($data);
            echo "<script>alert('Izdelek je bil spremenjen.');
                     window.location.href='".BASE_URL . "prikaz-izdelkov-cmp"."';</script>";
        } else {
            echo "<script>alert('Napaka pri urejanju.');
                     window.location.href='".BASE_URL . "prikaz-izdelkov-cmp"."';</script>";
        }
    }
    
    
    // funkcija se klice iz nadzorne plosce prodajalca...omogoca urejanje izdelkov
    public static function prikaziVseIzdelke() {
        
        // TODO - ce ni prijavljen prodajalec, naredi nedostopno/prijavite se link
        
        $rulesDetail = [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $dataDetail = filter_input_array(INPUT_GET, $rulesDetail, TRUE);
        // if se bo izvedel v primeru da izberemo ta izdelek za urejanje
        if (self::checkValues($dataDetail)) {
            echo ViewHelper::render('view/urejanje-izdelek.php', [
                'izdelek' => IzdelekDB::pridobiZOceno($dataDetail),
                'slike' => IzdelekDB::pridobiSlike($dataDetail)
                    ]
            );
        } else {

            $offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT, array('options' => array('default' => 0)));
            $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, array('options' => array('default' => 18)));

            echo ViewHelper::render('view/izdelki-cmp-list.php', [
//                'izdelki' => IzdelekDB::pridobiZOstranjevanjem($dataList),
                'izdelki' => IzdelekDB::pridobiZOstranjevanjem($offset, $limit),
                'stIzdelkov' => IzdelekDB::pridobiStIzdelkov()
            ]);
        }
    }
    
    public static function oceniIzdelek() {
        $rules = [
            "ocena" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "uporabnik_id" => [
                'filter' => FILTER_VALIDATE_INT
            ],
            "izdelek_id" => [
                'filter' => FILTER_VALIDATE_INT
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            if (!IzdelekDB::aliJeUporabnikZeOcenilIzdelek($data)) { //se ni ocenil izdelka
        	IzdelekDB::oceniIzdelek($data);
                ViewHelper::redirect(BASE_URL . "izdelki?id=" . $data["izdelek_id"]);
            } else { // je ze ocenil
                echo "<script>alert('Izdelek ste ze ocenili.');
                        window.location.href='".BASE_URL . "izdelki?id=".$data["izdelek_id"]."';</script>";
            }
        } else {
            ViewHelper::redirect(BASE_URL . "izdelki?id=" . $data["izdelek_id"]);
        }
        
    }

    public static function dodajIzdelek() {
        $rules = [
	    "ime" => [
		'filter' => FILTER_SANITIZE_SPECIAL_CHARS
	    ],
            "opis" => [
		'filter' => FILTER_SANITIZE_SPECIAL_CHARS
	    ],
            "cena" => [
		'filter' => FILTER_VALIDATE_FLOAT
	    ]
        ];
        // ali je izpolnil form
        $data = filter_input_array(INPUT_POST, $rules);
        if (self::checkValues($data)) {
            IzdelekDB::insert($data);
            echo "<script>alert('Izdelek je bil dodan.');
                     window.location.href='".BASE_URL . "prodajalec-nadzorna-plosca"."';</script>";
        } else {
            echo ViewHelper::render("view/izdelki-add.php");
        }
    }

    /**
     * Returns an array of filtering rules for manipulation books
     * @return array
     */
    private static function getRules() {
        return [
            //'id' => FILTER_VALIDATE_INT, // id je auto increment
            'ime' => FILTER_SANITIZE_SPECIAL_CHARS,
            'cena' => FILTER_VALIDATE_FLOAT,
            'opis' => FILTER_SANITIZE_SPECIAL_CHARS
        ];
    }

}
