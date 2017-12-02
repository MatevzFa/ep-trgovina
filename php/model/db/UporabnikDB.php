<?php

require_once 'AbstractDB.php';

/**
 * 
 */
class UporabnikDB extends AbstractDB {

    public static function get($id) {
        return self::query("SELECT * FROM uporabnik WHERE id = :id", array('id' => $id))[0];
    }

    public static function getAll() {
        return self::query("SELECT * FROM uporabnik");
    }

    public static function insert(array $params) {
        self::modify(
                "INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon) "
                . "VALUES (:vloga, :ime, :priimek, :email, :geslo, :naslov, :telefon)", $params);
    }

    public static function update(array $params) {
        self::modify("UPDATE uporabnik\n"
                . "SET \n"
                . "vloga = :vloga, \n"
                . "ime = :ime, \n"
                . "priimek = :priimek, \n"
                . "email = :email, \n"
                . "geslo = :geslo, \n"
                . "naslov = :naslov, \n"
                . "telefon = :telefon \n"
                . "WHERE id = :id", $params);
    }

    public static function delete($id) {
        self::modify("DELETE FROM uporabnik WHERE id = :id", array('id' => $id));
    }

    /**
     * Samo za testiranje. Za produkcijo uporabi $salt ki ga nastavi password_hash(...)
     */
    private static $SALT = 'salt_intensifies123456';

    /**
     * Doda uporabnika v PB, z zgoščenim geslom
     * @param array $params uporabnik
     */
    public static function dodajUporabnika(array $params) {

        $geslo = $params['geslo'];
        $params['geslo'] = password_hash($geslo, PASSWORD_DEFAULT, array('salt' => self::$SALT));

        self::insert($params);
    }

    /**
     * Posodobi geslo za uporabnika z 'id'
     * @param type $id
     * @param type $novoGeslo
     */
    public static function posodobiGeslo($id, $novoGeslo) {

        self::modify("UPDATE uporabnik "
                . "SET geslo = :gesloHash "
                . "WHERE id = :id", array(
            'id' => $id,
            'gesloHash' => password_hash($novoGeslo, PASSWORD_DEFAULT, array('salt' => self::$SALT))
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

        $gesloHash = self::get($id)['geslo'];

        return password_verify($geslo, $gesloHash);
    }

}
