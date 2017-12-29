<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/NarociloDB.php");
require_once("model/db/DB.php");

class NarocilaController extends AbstractController {

    /**
     * Vrne vsa narocila stranke z id
     */
    public static function narocila() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/narocila.php", [
                "narocila" => NarociloDB::pridobiVsaNarocilaStranke($data)
                    ]
            );
        } else {
            ViewHelper::redirect(BASE_URL . "prijava");
        }
    }

    //spremeni stanje narocila. Klice se iz narocila-list
    public static function spremeniStanjeNarocila() {
        // TODO - FILTER POST INPUT
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ],
            "staroStanje" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "novoStanje" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);
        //novo stanje ne bo pod 'novoStanje' ampak pod 'stanje'
        $data['stanje'] = $data['novoStanje'];
        unset($data['novoStanje']);
        if (self::checkValues($data)) {
            if ($data['stanje'] == 'stornirano') {
                $narociloKiGaBomoStornirali = NarociloDB::get(array(
                            "id" => $data['id']));

                $stornirano = array(
                    "datum" => $narociloKiGaBomoStornirali['datum'],
                    "uporabnik_id" => $narociloKiGaBomoStornirali['uporabnik_id'],
                    "stanje" => 'negativna-stornirano', //Ne bomo nikjer prikazovali
                    //ce bi dali se temu stanje stornirano, bi bila 2 narocila ko bi prikazovali vsa stornirana
                    "stornirano" => $data['id'],
                    "postavka" => - $narociloKiGaBomoStornirali['postavka']
                );
                /*                 * TODO - v vmesni tabeli med narocilom in izdelkom
                 * je potrebno narediti nove vnose z negativno kolicino
                 */
                NarociloDB::insert($stornirano);
                NarociloDB::spremeniStanjeNarocila($data);
            } else {
                NarociloDB::spremeniStanjeNarocila($data);
            }
        } else {
            ViewHelper::redirect(BASE_URL . "prijava");
        }
        ViewHelper::redirect(BASE_URL . "narocila-list?stanje=" . $data['staroStanje']);
    }

    // vsa narocila, ki so v dolocenem stanju
    public static function vsaNarocilaStanje() {

        $rules = [
            "stanje" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        $data = filter_input_array(INPUT_GET, $rules);
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/narocila-list.php", [
                "narocila" => NarociloDB::vsaNarocilaSStanjem($data)
                    ]
            );
        } else {
            ViewHelper::redirect(BASE_URL . "prodajalec-nadzorna-plosca");
        }
    }

    /**
     * Returns an array of filtering rules for manipulation books
     * @return type
     */
    private static function getRules() {
        return [
            //'id' => FILTER_VALIDATE_INT, // id je auto increment
            'stanje' => FILTER_SANITIZE_SPECIAL_CHARS,
            'postavka' => FILTER_VALIDATE_FLOAT,
            'stanje' => FILTER_SANITIZE_SPECIAL_CHARS,
            'datum' => FILTER_SANITIZE_SPECIAL_CHARS //nisem nasel validate date
        ];
    }
    
    public static function dodajNarocilo(array $izdelki) {
        $datum = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_id'];
        $uspeh = NarociloDB::dodajNarocilo($datum, $user_id, $izdelki);
        var_dump($uspeh);
    }
    
}
