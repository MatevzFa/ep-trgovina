<?php{
        
        
    }

require_once 'model/db/IzdelekDB.php';
require_once 'AbstractRESTController.php';
/**
 * Description of APIController
 *
 * @author matevz
 */
class IzdelkiRESTController extends AbstractRESTController {

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

//    public static function add() {
//
//        $rules = [
//            'ime' => FILTER_SANITIZE_SPECIAL_CHARS,
//            'cena' => FILTER_VALIDATE_
//        ]
//        
//        IzdelekDB::insert()
//    }

    
}
