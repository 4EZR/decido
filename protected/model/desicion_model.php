<?php
// require_once '../../conn.php';
class Decision_Model {
    private $db;
    
    // Constructor
    public function __construct($pdo) {
        // Connect to the database
        $this->db = $pdo;
    }
    
    // Add decision
    public function add_decision($title) {
        $stmt = $this->db->prepare("INSERT INTO `decisions` (`decision_Title`) VALUES (?)");
        $stmt->execute([$title]);
        $stmt->closeCursor();
    }
    
    // Get all decisions
    public function get_decisions() {
        $stmt = $this->db->query("SELECT * FROM `decisions`");
        $decisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $decisions;
    }

    public function get_decision_byID($id) {
        $stmt = $this->db->prepare("SELECT * FROM `decisions` WHERE decision_ID = ?");
        $stmt->execute([$id]);
        $decision = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $decision;
    }
    
}?>