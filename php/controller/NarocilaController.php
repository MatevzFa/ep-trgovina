<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/NarociloDB.php");


class NarocilaController extends AbstractController {

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
            //ni uporabnik - 404 error?
        }
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

}
