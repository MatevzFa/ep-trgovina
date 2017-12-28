<?php

require_once("AbstractController.php");
require_once("ViewHelper.php");
require_once("model/db/NarociloDB.php");

class KosaricaController extends AbstractController {

    public static function kosarica() {
        $rules = [
            'do' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' => "/^(dodaj|izprazni|posodobi)$/"
                ]
            ],
            'id' => FILTER_VALIDATE_INT,
            'kolicina' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 0]
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);

        if (METHOD == 'POST') {
            switch ($data['do']) {
                case 'dodaj':
                    self::dodajIzdelek($data['id']);
                    ViewHelper::redirect(BASE_URL . 'izdelki?id=' . $data['id']);
                    break;
                case 'izprazni':
                    self::izprazniKosarico();
                    break;
                case 'posodobi':
                    self::urediKolicino($data['id'], $data['kolicina']);
                    ViewHelper::redirect(BASE_URL . 'kosarica');
                    break;
            }
        } else if (METHOD == 'GET') {
            $vars = [
                'kosarica' => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
            ];

            echo ViewHelper::render('view/kosarica.php', $vars);
        }
    }
    
    public static function zakljuci() {
        
        if (METHOD == 'GET') {
            $vars = [
                'kosarica' => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
            ];
            echo ViewHelper::render('view/kosarica-zakljuci.php', $vars);
        } else if (METHOD == 'POST') {
            $rules = [
                'user_id' => FILTER_VALIDATE_INT,
                'postavka' => FILTER_VALIDATE_FLOAT

            ];
            $nakupTotal = 0;
            foreach($_SESSION['cart'] as $izdelek) {
                $nakupTotal += $izdelek['kolicina'] * floatval($izdelek['cena']);
            }
            $nakup = [
                'uporabnik_id' => $_SESSION['user_id'],
                'postavka' => $nakupTotal
            ];
            
            $filtriranNakup = filter_var_array($nakup, $rules);
            NarociloDB::insert($nakup);
            
            $idNarocila = NarociloDB::pridobiIDZadnjegaNarocila()['id'];
            //treba zapisati se delcke narocila v 'dodajVNarociloVsebuje'
            $rules = [
                    'kolicina' => [
                        'filter' => FILTER_VALIDATE_INT,
                        'min_range' => 0
                    ],
                    'narocilo_id' => FILTER_VALIDATE_INT,
                    'izdelek_id' => FILTER_VALIDATE_INT
                ];
            foreach($_SESSION['cart'] as $izdelek) {
                //:kolicina, :izdelek_id, :narocilo_id
                $newInsert = [
                    'narocilo_id' => $idNarocila,
                    'kolicina' => $izdelek['kolicina'],
                    'izdelek_id' => $izdelek['id']
                ];
               
                $filtriranInsert = filter_var_array($newInsert, $rules);
                NarociloDB::dodajVNarociloVsebuje($filtriranInsert);
            }
            $_SESSION['cart'] = []; // empty the basket
            //alert uspesno oddano
            echo "<script>alert('Narocilo je bilo uspesno oddano.');
                        window.location.href='".BASE_URL . "izdelki"."';</script>";
        }
    }

    private static function dodajIzdelek($izdelekId) {

        $izdelek = IzdelekDB::get(array('id' => $izdelekId));

        if ($izdelek['aktiven'] > 0) {
            if (isset($_SESSION["cart"][$izdelekId])) {
                $_SESSION["cart"][$izdelekId]["kolicina"] ++;
            } else {
                $_SESSION["cart"][$izdelekId]["kolicina"] = 1;
                $_SESSION["cart"][$izdelekId]["ime"] = $izdelek['ime'];
                $_SESSION["cart"][$izdelekId]["cena"] = $izdelek['cena'];
                $_SESSION["cart"][$izdelekId]["id"] = $izdelek['id'];
            }
        }
    }

    private static function odstraniIzdelek($izdelekId) {

        if (isset($_SESSION["cart"][$izdelekId])) {
            unset($_SESSION["cart"][$izdelekId]);
        }
    }

    private static function urediKolicino($izdelekId, $novakolicina) {
        if ($novakolicina > 0) {
            $_SESSION["cart"][$izdelekId]["kolicina"] = $novakolicina;
        } else {
            self::odstraniIzdelek($izdelekId);
        }
    }

    private static function izprazniKosarico() {
        unset($_SESSION["cart"]);
        ViewHelper::redirect(BASE_URL . 'kosarica');
    }

}
