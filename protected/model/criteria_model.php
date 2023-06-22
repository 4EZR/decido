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
    public function add_criteria($title, $term, $type,$decisionID) {
        $stmt = $this->db->prepare("INSERT INTO `criterias`(`Criteria_Title`, `Linguistic_term`,`Criteria_Type`, `Decision_ID`) VALUES (?,?,?,?)");
        $stmt->execute([$title, $term,$type,$decisionID]);
        $stmt->closeCursor();
    }
    
    // Get all decisions
    public function get_criterias($decisionID) {
        $stmt = $this->db->prepare("SELECT * FROM `criterias` c WHERE `Decision_ID` = ? ORDER BY c.criteria_ID");
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
    public function delete_criteria($criteriaID) {

        $stmt = $this->db->prepare("DELETE FROM `alternative_weight` WHERE `Criteria_ID` = ?");
        $stmt->execute([$criteriaID]);
        $stmt->closeCursor();

        $stmt = $this->db->prepare("DELETE FROM `criterias` WHERE `criteria_ID` = ?");
        $result = $stmt->execute([$criteriaID]);
        $stmt->closeCursor();
        return $result;
    }
    public function edit_criteria($criteriaID, $title, $term, $type) {
        $stmt = $this->db->prepare("UPDATE `criterias` SET `Criteria_Title` = ?, `Linguistic_term` = ?, `Criteria_Type` = ? WHERE `criteria_ID` = ?");
        $result = $stmt->execute([$title, $term, $type, $criteriaID]);
        $stmt->closeCursor();
        return $result;
    }
 
    
}?>