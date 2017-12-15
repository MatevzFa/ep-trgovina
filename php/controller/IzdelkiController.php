<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/IzdelekDB.php");

require_once(FORMS . "DodajanjeIzdelkaForm.php");

class IzdelkiController extends AbstractController {

    public static function izdelki() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
        if (self::checkValues($data)) {
                echo ViewHelper::render("view/izdelki-detail.php", [
                "izdelek" => IzdelekDB::pridobiZOceno($data),
                "slike" => IzdelekDB::pridobiSlike($data)
                ]
            );       
        } else {
            $offsetInLimit = array (
                "offset" => isset($_GET["offset"]) ? (int)$_GET["offset"] : 0,
                "limit" => 18
            );
            echo ViewHelper::render("view/izdelki-list.php", [
                "izdelki" => IzdelekDB::pridobiZOstranjevanjem($offsetInLimit),
                "stIzdelkov" => IzdelekDB::pridobiStIzdelkov()
            ]);
        }
    }
      
    public static function dodajIzdelek() {
        $form = new DodajanjeIzdelkaForm("izdelki-add");
        if ($form->validate()) {
            $novIzdelek = $form->getValue();
            IzdelekDB::insert($novIzdelek);
            ViewHelper::redirect(BASE_URL);
        } else {
            echo ViewHelper::render("view/izdelki-add.php", [
                "form" => $form
            ]);
        }
    }
    
    /**
     * Returns an array of filtering rules for manipulation books
     * @return type
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
