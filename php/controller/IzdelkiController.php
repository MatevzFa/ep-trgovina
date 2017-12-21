<?php

require_once('AbstractController.php');
require_once('ViewHelper.php');
require_once('model/db/IzdelekDB.php');

require_once(FORMS . 'DodajanjeIzdelkaForm.php');

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
        $form = new DodajanjeIzdelkaForm('izdelki-add');
        if ($form->validate()) {
            $novIzdelek = $form->getValue();
            IzdelekDB::insert($novIzdelek);
            ViewHelper::redirect(BASE_URL);
        } else {
            echo ViewHelper::render('view/izdelki-add.php', [
                'form' => $form
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
