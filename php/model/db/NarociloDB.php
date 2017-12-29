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
     * ce ne storniras, das na mesto :stornirano = null
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
     * 
     * @param array $params id narocila
     * spremeni stanje narocila
     */
    public static function spremeniStanjeNarocila(array $params) {
        self::modify(""
                . "UPDATE narocilo "
                . "SET "
                . "stanje = :stanje "
                . "WHERE id = :id", $params);
    }

    /**
     * Vrne vse narocila ene stranke
     * @param id_uporabnika
     */
    public static function pridobiVsaNarocilaStranke(array $params) {
        return self::query(""
                        . "SELECT * FROM narocilo "
                        . "WHERE uporabnik_id = :id", $params);
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

    /**
     * Transakcijsko dodajanje naročila v bazo
     * @param type $datum datum narocila
     * @param type $user_id uporabnik ki naroca
     * @param array $izdelki array [id => izdelek(id, kolicina, cena)]
     * @return boolean TRUE če uspe; FALSE sicer
     */
    public static function dodajNarocilo($datum, $user_id, array $izdelki) {

        $dbconn = DB::getInstance();

        $dbconn->beginTransaction();

        $stmtNarocilo = $dbconn->prepare(""
                . "INSERT INTO narocilo "
                . "     (datum, uporabnik_id, stanje, postavka) "
                . "VALUES ("
                . "     :datum, "
                . "     :uporabnik_id, "
                . "     :stanje, "
                . "     :postavka"
                . ")"
                . "");
        $stmtNarocilo->bindValue(':datum', $datum);
        $stmtNarocilo->bindValue(':uporabnik_id', $user_id);
        $stmtNarocilo->bindValue(':stanje', "oddano");
        $stmtNarocilo->bindValue(':postavka', 0, PDO::PARAM_INT);

        if (!$stmtNarocilo->execute()) {
            $dbconn->rollBack();
            return FALSE;
        }

        $stmtIzdelek = $dbconn->prepare(""
                . "INSERT INTO narocilo_vsebuje "
                . "     (narocilo_id, izdelek_id, kolicina, cena) "
                . "VALUES ("
                . "     LAST_INSERT_ID(), "
                . "     :izdelek_id, "
                . "     :kolicina, "
                . "     :cena"
                . ")"
                . "");

        $izdelekId = NULL;
        $kolicina = NULL;
        $cena = NULL;

        $stmtIzdelek->bindParam(":izdelek_id", $izdelekId);
        $stmtIzdelek->bindParam(":kolicina", $kolicina);
        $stmtIzdelek->bindParam(":cena", $cena);

        foreach ($izdelki as $izdelek) {
            $izdelekId = $izdelek['id'];
            $kolicina = $izdelek['kolicina'];
            $cena = $izdelek['cena'];
            if (!$stmtIzdelek->execute()) {
                $dbconn->rollBack();
                return FALSE;
            }
        }

        return $dbconn->commit();
    }

}
