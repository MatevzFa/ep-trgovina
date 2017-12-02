<?php

require_once 'AbstractDB.php';

/**
 * 
 */
class IzdelekDB extends AbstractDB {

    public static function get(array $params) {
        return self::query(""
                        . "SELECT * FROM izdelek "
                        . "WHERE id = :id", $params)[0];
    }

    public static function getAll() {
        return self::query("SELECT id, ime, cena FROM izdelek");
    }

    public static function insert(array $params) {
        self::modify(""
                . "INSERT INTO izdelek (ime, cena, opis) "
                . "VALUES (:ime, :cena, :opis)", $params);
    }

    public static function update(array $params) {
        
    }

    public static function delete(array $params) {
        self::modify(""
                . "DELETE FROM izdelek "
                . "WHERE id = :id", $params);
    }

    /**
     * Pridobivanje izdelkov z ostranjevanjem
     * @param type $offset offset
     * @param type $limit limit
     * @return type array(izdelek)
     */
    public static function getAllPagination($offset = 0, $limit = 25) {
        return self::query(""
                        . "SELECT * FROM izdelek "
                        . "LIMIT :limit OFFSET :offset", array(
                    'limit' => $limit,
                    'offset' => $offset
        ));
    }

    /**
     * Podatki o dolocenem izdelku
     * @param type $id id izdelka
     * @return vse o izdelku, povprecna ocena in slike
     */
    public static function pridobiZOceno($id) {
        return self::query(""
                        . "SELECT s.path, i.*, povprecnaOcena(i.id) AS povprecnaOcena "
                        . "FROM izdelek i, slika s WHERE i.id = :id", array('id' => $id));
        /**
         * TODO
         * Ali se da spremeniti, da bi nekako dobil seznam ali pa nekaj 
         * z vsemi slikami? Zdaj vrne dejansko vec tabel, kjer je vse isto razen 
         * razlicne poti do razlicnih slik.
         */
    }

    /**
     * Iz PB pridobi url-je slik za nek izdelek.
     * @param type $id id izdelka
     * @return array slike
     */
    public static function pridobiSlike(array $params) {
        return self::query(""
                        . "SELECT path FROM slika"
                        . "WHERE id_izdelka = :id", $params);
    }

}
