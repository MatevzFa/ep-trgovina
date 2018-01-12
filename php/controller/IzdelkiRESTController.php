<?php

require_once 'model/db/IzdelekDB.php';

/**
 * Description of APIController
 *
 * @author matevz
 */
class IzdelkiRESTController {

    public static function index() {
        self::headers();

        $izdelki = IzdelekDB::pridobiVseSSlikami();
        echo json_encode($izdelki, JSON_PRETTY_PRINT);
    }
    
    public static function get($id) {
        self::headers();
        
        $izdelek = IzdelekDB::pridobiZOceno(array('id' => $id));
        $slike = IzdelekDB::pridobiSlike(array('id' => $id));
        $izdelek['slike'] = $slike;
        echo json_encode($izdelek, JSON_PRETTY_PRINT);
    }
    
    public static function add() {
        
    }

    public static function headers() {
        header('Content-Type: application/json');
    }

}
