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

        self::insert($params);
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
    public static function pridobiId($email) {        
        return self::query(""
                . "SELECT id "
                . "FROM uporabnik "
                . "WHERE email = :email", array('email' => $email))[0]['id'];
    }

}
