<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'model/db/UporabnikDB.php';

/**
 * Description of UporabnikRESTController
 *
 * @author matevz
 */
class UporabnikRESTController {

    public static function prijava() {

        $rules = [
            'email' => FILTER_VALIDATE_EMAIL,
            'geslo' => FILTER_DEFAULT
        ];

//        echo json_encode($_POST);

        $uporabnik = filter_input_array(INPUT_POST, $rules);

        $email = $uporabnik['email'];
        $geslo = $uporabnik['geslo'];

        $email = array(
            "email" => $uporabnik['email']
        );

        $idInVlogaUporabnika = UporabnikDB::pridobiIdInVlogo($email);
        
        $token = UporabnikDB::mobileLogin($idInVlogaUporabnika['id'], $geslo);
        
        $user = UporabnikDB::get(array('id' => $token['user_id']));
        echo json_encode([
            'ime' => $user['ime'],
            'priimek' => $user['priimek'],
            'loggedIn' => TRUE,
            'token' => $token['token'],
        ]);
    }
    
    public static function odjava($token) {
        if ($token == NULL) {
            echo json_encode([
                'loggedIn' => FALSE,
            ]);
            exit();
        }
        UporabnikDB::mobileLogout($token);
        self::userdata(NULL);
    }
    
    public static function userdata($token) {
        if ($token == NULL) {
            echo json_encode([
                'loggedIn' => FALSE,
            ]);
            exit();
        }
        $id = UporabnikDB::mobileVerify($token);
        if ($id != NULL) {
            $user = UporabnikDB::get(array('id' => $id));
            echo json_encode([
                'ime' => $user['ime'],
                'priimek' => $user['priimek'],
                'loggedIn' => TRUE,
            ]);
        } else {
            echo json_encode([
                'loggedIn' => FALSE,
            ]);
        }
    }

}
