<?php

require_once 'AbstractDB.php';

/**
 * 
 */
class NarociloDB extends AbstractDB {

	public static function get(array $params) {
        return self::query(""
                        . "SELECT * FROM narocilo "
                        . "WHERE id = :id", $params)[0];
    }


    public static function getAll() {
        return self::query("SELECT * FROM narocilo");
    }

    /**
     * Za stornacijo vneses negativno postavko
     * in kot :stornirano id narocila, ki ga storniras
     */
    public static function insert(array $params) {
        self::modify(""
                . "INSERT INTO narocilo (datum, "
                . " uporabnik_id, stanje, stornirano, postavka) "
                . "VALUES (:datum, :uporabnik_id, :stanje, "
                . ":stornirano, :postavka)", $params);
    }

    public static function update(array $params) {
        self::modify(""
                . "UPDATE narocilo"
                . "SET "
                . "datum = :datum, "
                . "uporabnik_id = :uporabnik_id, "
                . "stanje = :stanje, "
                . "stornirano = :stornirano, "
                . "postavka = :postavka "
                . "WHERE id = :id", $params);
        
    }

    public static function delete(array $params) {
        self::modify(""
                . "DELETE FROM narocilo "
                . "WHERE id = :id", $params);
    }

    //--------------------- CUT 'NON TRIVIAL' QUERIES HERE ------------------

    /**
     * Vrne vse narocila ene stranke
     * @param id_uporabnika
     */
    public static function pridobiVsaNarocilaStranke(array $params) {
    	return self::query(""
    			. "SELECT * FROM narocilo "
    			. "WHERE uporabnik_id = :uporabnik_id", $params);
    }

    /**
     * Vrne vsa narocila z dolocenim stanjem - stornirana, oddana, ...
     * @param stanje narocil, ki jih zelimo prikazati
     * @return vsa narocila s taksnim stanjem
     */
    public static function vsaNarocilaSStanjem(array $params) {
        return self::query(""
                . "SELECT * FROM narocilo "
                . "WHERE stanje = :stanje", $params);
    }

    /**
     * Vrne podrobnosti narocila 
     * @param id narocila
     * @return id_izdelek, kolicina, ime izdelka, cena izdelka(na kos)
     */
    public static function pridobiPodrobnostiONarocilu(array $params) {
        return self::query(""
                . "SELECT nv.izdelek_id, nv.kolicina, i.ime, i.cena "
                . "FROM narocilo_vsebuje nv, izdelek i "
                . "WHERE nv.narocilo_id = :id "
                . "AND nv.izdelek_id = i.id", $params);

    }


    

}