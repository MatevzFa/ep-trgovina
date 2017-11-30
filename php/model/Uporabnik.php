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
    
    public function getAllItems() {
        $db = DBInit::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM izdelek");
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // potrebno dobiti informacije o izdlku, path slike in ocena
    public function getItemInformation($id) {
        
    }
}