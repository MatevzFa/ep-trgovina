<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractRESTController
 *
 * @author matevz
 */
class AbstractRESTController {
    //put your code here
    public static function headers() {
        header('Content-Type: application/json');
    }

}
