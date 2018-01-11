<?php

abstract class AbstractController {
    
    /**
     * Returns TRUE if given $input array contains no FALSE values
     * @param type $input
     * @return type
     */
    protected static function checkValues($input) {
        if (empty($input)) {
            return FALSE;
        }

        $result = TRUE;
        foreach ($input as $value) {
            $result = $result && $value != false;
        }

        return $result;
    }
    
    static $VLOGE = [
        'stranka' => 3,
        'prodajalec' => 2,
        'administrator' => 0
    ];

    /**
     * V primeru da je podana vloga nadrejena prijavljeni vlogi preusmeri na /
     * @param string $vloga vloga
     * @return NULL
     */
    protected static function preveriVlogo($vloga) {
        if (isset($_SESSION['vloga']) && self::$VLOGE[$_SESSION['vloga']] <= self::$VLOGE[$vloga]) {
            return;
        } else {
            ViewHelper::alert('Prepovedan dostop', '/');
            exit();
        }
    }

}
