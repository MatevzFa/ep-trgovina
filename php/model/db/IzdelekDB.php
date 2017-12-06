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
    	self::modify(""
    			. "UPDATE izdelek SET "
    			. "ime = :ime, "
    			. "cena = :cena, "
    			. "opis = :opis "
    			. " WHERE id = :id", $params);
        
    }

    public static function delete(array $params) {
        self::modify(""
                . "DELETE FROM izdelek "
                . "WHERE id = :id", $params);
    }


    //----------------------- CUT 'NON TRIVIAL' QUERIES HERE ------------------------

    /**
     * Posodobitev slike izdelka
     * @param type $id id od slike, type $path path do nove slike
     * izdelek_id ostane isti
     */
    public static function posodobiSliko(array $params) {
        self::modify(""
                . "UPDATE slika SET "
                . "path = :path "
                . "WHERE id = :id", $params);

    }

    /**
     * Tako kot getAll, samo da so zraven se slike.
     * ko so prikazani vsi izdelki, je najboljse da so tam tudi slike njih
     * @return izdelek(id, ime, cena, slika)
     */
    public static function pridobiVseSSlikami() {
       return self::query(""
                . "SELECT i.id,i.ime,i.cena,(SELECT path FROM slika WHERE izdelek_id = i.id LIMIT 1) as slika FROM izdelek i");
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
