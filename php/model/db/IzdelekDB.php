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
     * @return vse o izdelku, povprecna ocena 
     */
    public static function pridobiZOceno(array $params) {
        return self::query(""
                        . "SELECT i.*, povprecnaOcena(i.id) AS povprecnaOcena "
                        . "FROM izdelek i WHERE i.id = :id", $params)[0];
    }

    /**
     * Iz PB pridobi url-je slik za nek izdelek.
     * @param type $id id izdelka
     * @return array slike
     */
    public static function pridobiSlike(array $params) {
        return self::query(""
        				. "SELECT path "
        				. "FROM slika WHERE izdelek_id = :id", $params);
    }

}
