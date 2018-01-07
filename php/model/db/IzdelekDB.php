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
    /*
     * @params izdelek_id in uporabnik_id
     * @return TRUE, ce je ze ocenil, FALSE ce se ni
     */
    public static function aliJeUporabnikZeOcenilIzdelek(array $params) {
        if (self::query("SELECT * FROM ocena WHERE izdelek_id = :izdelek_id AND "
                . "uporabnik_id = :uporabnik_id", $params)) {
            return TRUE;
        }
        return FALSE;
    }
    public static function oceniIzdelek(array $params) {
        self::modify(""
                . "INSERT INTO ocena (uporabnik_id, izdelek_id, ocena) "
                . "VALUES (:uporabnik_id, :izdelek_id, :ocena)", $params);
    }

    /**
     * 
     * @param array $params id izdelka
     */
    public static function dodajSlikoIzdelku(array $params) {
        self::modify(""
                . "INSERT INTO slika (path, izdelek_id) "
                . "VALUES (:path, :izdelek_id)", $params);
    }

    public static function pridobiStIzdelkov() {
        return intval(self::query("SELECT COUNT(*) as stIzdelkov FROM izdelek")[0]['stIzdelkov']);
    }

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
                        . "SELECT "
                        . " i.id, "
                        . " i.ime, "
                        . " i.cena, "
                        . " (SELECT path FROM slika WHERE izdelek_id = i.id LIMIT 1) AS slika "
                        . "FROM izdelek i");
    }

    /**
     * Pridobivanje izdelkov z ostranjevanjem
     * @param type array offset=> in limit=>
     * @return type array(izdelek)
     */
    public static function pridobiZOstranjevanjem($offset=0, $limit=18) {
        $stmt = AbstractDB::getConnection()->prepare(""
                . "SELECT "
                . "i.id, "
                . "i.ime, "
                . "i.cena, "
                . "(SELECT path FROM slika WHERE izdelek_id = i.id LIMIT 1) AS slika "
                . "FROM izdelek i "
                . "LIMIT :offset, :limit");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchall();
    }

    /**
     * Podatki o dolocenem izdelku
     * @param array $params array z 'id' => id
     * @return vse o izdelku, povprecna ocena 
     */
    public static function pridobiZOceno(array $params) {
        return self::query(""
                        . "SELECT i.*, ROUND(AVG(o.ocena), 1) as povprecnaOcena "
                        . "FROM izdelek i LEFT JOIN ocena o ON i.id = o.izdelek_id "
                        . "WHERE i.id = :id", $params)[0];
    }

    /**
     * Iz PB pridobi url-je slik za nek izdelek.
     * @param array $params array z 'id' => id
     * @return array slike
     */
    public static function pridobiSlike(array $params) {
        return self::query(""
                        . "SELECT path "
                        . "FROM slika WHERE izdelek_id = :id", $params);
    }

}
