<?php

require_once 'AbstractDB.php';

/**
 * 
 */
class IzdelekDB extends AbstractDB {

	/**
	 * Podatki o dolocenem izdelku
     * @param type $id id izdelka
     * @return vse o izdelku, povprecna ocena in slike
     */
	public static function get($id) {
		return self::query("SELECT s.path, i.*, povprecnaOcena(i.id) AS povprecnaOcena FROM izdelek i, slika s WHERE i.id = :id", array('id' => $id));
		/** TODO - ali se da spremeniti, da bi nekako dobil 
		seznam ali pa nekaj z vsemi slikami? Zdaj vrne dejansko 
		vec tabel, kjer je vse isto razen razlicne poti do razlicnih slik

		*/
	}

	public static function getAll() {
		return self::query("SELECT id, ime, cena FROM izdelek");
	}

	public static function insert(array $params) {
		self::modify(
                "INSERT INTO izdelek (ime, cena, opis)"
                . "VALUES (:ime, :cena, :opis)", $params);

	}

	public static function update(array $params) {

	}

	public static function delete($id) {
		self::modify("DELETE FROM izdelek WHERE id = :id",
				array('id' => $id));

	}


}