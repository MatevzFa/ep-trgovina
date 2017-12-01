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
        $db = DB::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM " . self::table);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . self::table . " WHERE id = :id");
        $stmt->bindValue("id", $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }  
}