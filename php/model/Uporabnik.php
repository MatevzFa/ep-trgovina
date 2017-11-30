<?php
    
    
require_once 'DBInit.php';
/**
 * 
 */
class Uporabnik
{
    
    const table = 'uporabnik';
    
    /**
     * 
     */
    public function __construct()
    {
        // code...
    }
    
    public function findAll() {
        $db = DBInit::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM " . self::table);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . self::table . " WHERE id = :id");
        $stmt->bindValue("id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    //prikaz vseh izdelkov. Rabimo samo ime, ceno in id
    public function getAllItems() {
        $db = DBInit::getInstance();
        
        $stmt = $db->prepare("SELECT id, ime, cena FROM izdelek");
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // potrebno dobiti se opis, pot do slike in oceno
    public function getItemInformation($id) {
        $db = DBInit::getInstance();
        
        // TODO moram naredit tako, da dobim povprecno oceno, ne pa vseh ocen
        $stmt = $db->prepare("SELECT i.id, i.ime, i.cena, i.opis, s.path, o.ocena"
                . " FROM izdelek i INNER JOIN slika s ON i.id = s.izdelek_id "
                . "INNER JOIN ocena o ON i.id = o.izdelek_id WHERE i.id = :id");
        $stmt->bindValue("id", $id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    
}