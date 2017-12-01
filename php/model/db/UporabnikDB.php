<?php
    
    
require_once 'AbstractDB.php';
/**
 * 
 */
class UporabnikDB extends AbstractDB
{
    
    const table = 'uporabnik';
    
    public static function get($id) {
        return self::query("SELECT * FROM uporabnik WHERE id = :id", array('id' => $id))[0];
    }

    public static function getAll() {
        return self::query("SELECT * FROM uporabnik");
    }

    public static function insert(array $params) {
        self::modify(
                "INSERT INTO uporabnik (id, vloga, ime, priimek, email, geslo, naslov, telefon) "
                . "VALUES (:id, :vloga, :ime, :priimek, :email, :geslo, :naslov, :telefon)", $params);
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
}