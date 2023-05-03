<?php
// require_once '../../conn.php';
class Term_Model {
    private $db;
    
    // Constructor
    public function __construct($pdo) {
        // Connect to the database
        $this->db = $pdo;
    }
    
   
    
    // Get all decisions
    public function get_linguistic_term() {
        $stmt = $this->db->query("SELECT * FROM `linguistic_terms`");
        $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $terms;
    }


    
}?>