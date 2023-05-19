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
    public function get_decision_matrix_data($id){
    
        $stmt = $this->db->prepare("SELECT c.Criteria_Title, c.Criteria_Importance, lt.TermLevel_1, lt.TermLevel_2, lt.TermLevel_3, lt.TermLevel_4, lt.TermLevel_5, aw.Weight,c.Criteria_Importance,c.Criteria_ID, aw.Alternative_ID,a.Alternative_Title FROM criterias c JOIN linguistic_terms lt ON c.Linguistic_Term = lt.Term_ID JOIN alternative_weight aw ON c.criteria_ID = aw.Criteria_ID inner join alternatives a on aw.Alternative_ID = a.Alternative_ID WHERE c.Decision_ID = ? ORDER BY aw.Alternative_ID ASC, c.Criteria_Importance ASC");
        $stmt->execute([$id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
    }
    public function search_decisions($search) {
        $stmt = $this->db->prepare("SELECT * FROM `decisions` WHERE `decision_Title` LIKE :search");
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();
        $decisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $decisions;
    }
    
}?>