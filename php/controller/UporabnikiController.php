<?php

require_once("ViewHelper.php");
require_once("model/db/IzdelekDB.php");

class UporabnikiController {
    

    /**
     * tukaj notri poskrbimo za registracijo, prijavo in update?
     * ali poseben controller za vsako od teh?
     */
    public static function uporabnik() {

    }



    /**
     * Returns TRUE if given $input array contains no FALSE values
     * @param type $input
     * @return type
     */
    private static function checkValues($input) {
        if (empty($input)) {
            return FALSE;
        }

        $result = TRUE;
        foreach ($input as $value) {
            $result = $result && $value != false;
        }

        return $result;
    }


}