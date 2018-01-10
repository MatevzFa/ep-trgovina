<?php

require_once 'model/db/IzdelekDB.php';

/**
 * Description of APIController
 *
 * @author matevz
 */
class APIController {
    
    public static function izdelki() {
        self::headers();
        
        $rules = [
            'id' => FILTER_SANITIZE_NUMBER_INT
        ];
        
        $data = filter_input_array(INPUT_GET, $rules);
        
        if (!$data) {
            $izdelki = IzdelekDB::pridobiVseSSlikami();
            echo json_encode($izdelki, JSON_PRETTY_PRINT);
        } else {
            $izdelek = IzdelekDB::pridobiZOceno($data);
            unset($izdelek['aktiven']);
            echo json_encode($izdelek, JSON_PRETTY_PRINT);
        }
        
    }
    
    
    public static function headers() {
        header('Content-Type: application/json');
    }
}
