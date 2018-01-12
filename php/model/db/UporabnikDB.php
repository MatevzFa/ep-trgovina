<?php

require_once 'AbstractDB.php';

/**
 * 
 */
class UporabnikDB extends AbstractDB {

    public static function get(array $params) {
        return self::query("SELECT * FROM uporabnik WHERE id = :id", $params)[0];
    }

    public static function getAll() {
        return self::query("SELECT * FROM uporabnik");
    }

    public static function insert(array $params) {
        self::modify(""
                . "INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon) "
                . "VALUES (:vloga, :ime, :priimek, :email, :geslo, :naslov, :telefon)", $params);
    }

    public static function update(array $params) {
        self::modify(""
                . "UPDATE uporabnik"
                . "SET "
                . "vloga = :vloga, "
                . "ime = :ime, "
                . "priimek = :priimek, "
                . "email = :email, "
                . "geslo = :geslo, "
                . "naslov = :naslov, "
                . "telefon = :telefon "
                . "WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        self::modify("DELETE FROM uporabnik WHERE id = :id", $params);
    }

    // ----------------------- CUT "NON TRIVIAL" QUERIES HERE -----------

    /*
     * Vrne True ce je aktiviran, False sicer
     */
    public static function aliJeAktiviran(array $params) {
        $result = self::query(""
                        . "SELECT aktiven "
                        . "FROM uporabnik "
                        . "WHERE id = :id", $params)[0];
        if ($result['aktiven'] == 1) {
            return True;
        }
        return False;
    }

    /**
     * 
     * @param type $input_email, email ob registraciji
     * @return boolean True ce ze obstaja, False sicer
     */
    public static function aliEmailZeObstaja($input_email) {
        $result = self::query(""
                        . "SELECT * "
                        . "FROM uporabnik "
                        . "WHERE email = :email", array('email' => $input_email));
        if ($result == NULL) {
            return False;
        }
        return True;
    }

    public static function urejanjeZaposlenega(array $params) {
        self::modify(""
                . "UPDATE uporabnik "
                . "SET ime = :ime, priimek = :priimek, "
                . "email = :email "
                . "WHERE id = :id", $params);
    }

    public static function urejanjeStranke(array $params) {
        self::modify(""
                . "UPDATE uporabnik "
                . "SET ime = :ime, priimek = :priimek, "
                . "email = :email, naslov = :naslov, "
                . "telefon = :telefon "
                . "WHERE id = :id", $params);
    }

    public static function ustvariProdajalca(array $params) {
        self::modify(""
                . "INSERT INTO uporabnik (vloga, ime, priimek, email, geslo) "
                . "VALUES (:vloga, :ime, :priimek, :email, :geslo)", $params);
    }

    public static function aktivirajUporabnika(array $params) {
        self::modify(""
                . "UPDATE uporabnik "
                . "SET aktiven = 1 "
                . "WHERE id = :id", $params);
    }

    public static function deaktivirajUporabnika(array $params) {
        self::modify(""
                . "UPDATE uporabnik "
                . "SET aktiven = 0 "
                . "WHERE id = :id", $params);
    }

    /**
     * Vsi uporabniki z doloceno vlogo
     *  @param vloga
     */
    public static function vsiUporabnikiZVlogo(array $params) {
        return self::query(""
                        . "SELECT id, ime, priimek, aktiven "
                        . "FROM uporabnik "
                        . "WHERE vloga = :vloga", $params);
    }

    /**
     * Samo za testiranje. Za produkcijo uporabi $salt ki ga nastavi password_hash(...)
     */
    private static $HASH_OPTIONS = [
        'salt' => 'salt_intensifies123456',
        'cost' => '10'
    ];

    /**
     * Bolj varno, da posljemo samo stvari, ki jih dejansko rabi
     * @param array $params id uporabnika
     * @return 
     */
    public static function podatkiOUporabniku(array $params) {
        return self::query(""
                        . "SELECT ime, priimek, email, telefon "
                        . "FROM uporabnik "
                        . "WHERE id = :id", $params)[0];
    }

    /**
     * Doda uporabnika v PB, z zgoščenim geslom
     * @param array $params uporabnik
     */
    public static function dodajUporabnika(array $params) {

        $geslo = $params['geslo'];
        $params['geslo'] = password_hash($geslo, PASSWORD_DEFAULT, self::$HASH_OPTIONS);

        if ($params['naslov'] != null) { //je uporabnik
            self::insert($params);
        } else { //je prodajalec
            self::ustvariProdajalca($params);
        }
    }

    /**
     * Posodobi geslo za uporabnika z 'id'
     * @param type $id
     * @param type $novoGeslo
     */
    public static function posodobiGeslo($id, $novoGeslo) {

        self::modify(""
                . "UPDATE uporabnik "
                . "SET geslo = :gesloHash "
                . "WHERE id = :id", array(
            'id' => $id,
            'gesloHash' => password_hash($novoGeslo, PASSWORD_DEFAULT, self::$HASH_OPTIONS)
                )
        );
    }

    /**
     * Vrne TRUE če se gesla ujemajo, FALSE sicer
     * @param type $id id uporabnika
     * @param type $geslo geslo ki potrebuje preverjanje
     * @return boolean ujemanje gesel
     */
    public static function preveriGeslo($id, $geslo) {

        $gesloHash = self::get(array('id' => $id))['geslo'];
        return password_verify($geslo, $gesloHash);
    }

    /**
     * Pridobi id uporabnika glede na email
     * @param type $email email uporabnika
     * @return number Id uporabnika
     */
    public static function pridobiIdInVlogo(array $params) {
        return self::query(""
                        . "SELECT id, vloga "
                        . "FROM uporabnik "
                        . "WHERE email = :email", $params)[0];
    }

    public static function mobileLogin($id, $geslo) {

        if (self::preveriGeslo($id, $geslo)) {

            $mobileinfo = self::query("SELECT * FROM mobile_info WHERE user_id = :id", array('id' => $id));

            if ($mobileinfo != NULL) {
                return $mobileinfo[0];
            } else {

                $dbconn = DB::getInstance();

                $stmtIzdelek = $dbconn->prepare(""
                        . "INSERT INTO mobile_info "
                        . "     (user_id, date, token) "
                        . "VALUES ("
                        . "     :id, "
                        . "     NOW(), "
                        . "     UUID()"
                        . ")"
                        . "");

                $stmtIzdelek->bindValue(":id", $id, PDO::PARAM_INT);

                $stmtIzdelek->execute();

                return self::query("SELECT * FROM mobile_info WHERE user_id = :id", array('id' => $id))[0];
            }
        } else {
            return null;
        }
    }
    
    public static function mobileLogout($token) {
        self::modify("DELETE FROM mobile_info WHERE token = :token", array('token' => $token));
    }
    
    public static function mobileVerify($token) {
        if ($token == NULL) {
            return NULL;
        }
        $id = self::query("SELECT user_id FROM mobile_info WHERE token = :token", array('token' => $token));
        if ($token != NULL) {
            return $id[0]['user_id'];
        } else {
            return NULL;
        }
    }

}
