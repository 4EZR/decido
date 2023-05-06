<?php
// require_once '../../conn.php';
class Criteria_Model {
    private $db;
    
    // Constructor
    public function __construct($pdo) {
        // Connect to the database
        $this->db = $pdo;
    }
    
    // Add decision
    public function add_criteria($title, $term, $decisionID) {
        $stmt = $this->db->prepare("INSERT INTO `criterias`(`criteria_Title`, `linguistic_term`, `Decision_ID`) VALUES (?,?,?)");
        $stmt->execute([$title, $term, $decisionID]);
        $stmt->closeCursor();
    }
    
    // Get all decisions
    public function get_criterias($decisionID) {
        $stmt = $this->db->prepare("SELECT * FROM `criterias` c INNER JOIN `linguistic_terms` t on t.Term_ID = c.linguistic_term  WHERE `Decision_ID` = ? ORDER BY c.Criteria_Importance");
        $stmt->execute([$decisionID]);
        $criterias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $criterias;
    }


    public function update_importance($importanceLevel, $criteriaId){
        $stmt =  $this->db->prepare('UPDATE `criterias` SET `Criteria_Importance`=? WHERE `criteria_ID` = ?');
        $result = $stmt->execute([$importanceLevel, $criteriaId]);
        $stmt->closeCursor();
        return $result;
    }

    
}?>