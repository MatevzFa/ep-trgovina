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
    protected static function preveriVlogo($vloga = 'prodajalec') {
        if (isset($_SESSION['vloga']) && self::$VLOGE[$_SESSION['vloga']] <= self::$VLOGE[$vloga]) {
            return;
        } else {
            ViewHelper::alert('Prepovedan dostop', '/');
        }
    }
    
    /**
     * 
     * @param type $message sporocilo v log
     * zapis v log za prodajalce
     */
    protected static function prodajalec_log($message) {
        $pot_do_prodajalec_log = "/home/ep/NetBeansProjects/ep-trgovina/php/logs/prodajalec.log";
        $final_message = date("Y-m-d H:i:s") . "\t\t" . $message . "\n";
            
        error_log($final_message, 3, $pot_do_prodajalec_log);
    }
    
    /**
     * 
     * @param type $message sporocilo v log
     * zapis v log za administratorje
     */
    protected static function administrator_log($message) {
        $pot_do_prodajalec_log = "/home/ep/NetBeansProjects/ep-trgovina/php/logs/administrator.log";
        $final_message = date("Y-m-d H:i:s") . "\t\t" . $message . "\n";
            
        error_log($final_message, 3, $pot_do_prodajalec_log);
    }
    
}
