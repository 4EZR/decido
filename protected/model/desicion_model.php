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
    public function update_decision($id, $title) {
        $stmt = $this->db->prepare("UPDATE `decisions` SET `decision_Title` = ? WHERE `decision_ID` = ?");
        $stmt->execute([$title, $id]);
        $stmt->closeCursor();
    }

    // Delete decision
    public function delete_decision($id) {
        // Delete alternative weights
        $stmt = $this->db->prepare("DELETE FROM `alternative_weight` WHERE `Alternative_ID` IN (SELECT `Alternative_ID` FROM `alternatives` WHERE `Decision_ID` = ?)");
        $stmt->execute([$id]);
        $stmt->closeCursor();
    
        // Delete alternatives
        $stmt = $this->db->prepare("DELETE FROM `alternatives` WHERE `Decision_ID` = ?");
        $stmt->execute([$id]);
        $stmt->closeCursor();
    
        // Delete criteria
        $stmt = $this->db->prepare("DELETE FROM `criterias` WHERE `Decision_ID` = ?");
        $stmt->execute([$id]);
        $stmt->closeCursor();
    
        // Delete the decision
        $stmt = $this->db->prepare("DELETE FROM `decisions` WHERE `decision_ID` = ?");
        $stmt->execute([$id]);
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
    public function search_decisions($search)
    {
        $stmt = $this->db->prepare("SELECT * FROM `decisions` WHERE `decision_Title` LIKE :search");
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();
        $decisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $decisions;
    }
    public function decisionHasValidCriteria($decision_id) {
        $response = ['status' => false, 'message' => ''];

        // Check if the decision_id exists in the `decisions` table
        $sql = "SELECT * FROM `decisions` WHERE `decision_ID` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$decision_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($result === false) {
            $response['message'] = 'The decision does not exist.';
            return $response;
        }

        // Query the `criterias` table to find criteria with the given decision_id
        $sql = "SELECT * FROM `criterias` WHERE `Decision_ID` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$decision_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (empty($result)) {
            $response['message'] = 'The decision does not have any criteria.';
            return $response;
        }

        // Check if all criteria have importance greater than 0
        $all_importance_greater_than_zero = true;
        foreach ($result as $row) {
            if ($row['Criteria_Importance'] <= 0) {
                $all_importance_greater_than_zero = false;
                break;
            }
        }

        if (!$all_importance_greater_than_zero) {
            $response['message'] = 'Some criteria have an importance of 0.';
            return $response;
        }

        $response['status'] = true;
        $response['message'] = 'The decision has valid criteria.';
        return $response;
    }

    public function decisionHasValidAlternatives($decision_id) {
        $response = ['status' => false, 'message' => ''];

        // Query the `alternatives` table to find alternatives with the given decision_id
        $sql = "SELECT * FROM `alternatives` WHERE `Decision_ID` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$decision_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (empty($result)) {
            $response['message'] = 'The decision does not have any alternatives.';
            return $response;
        }

        // Check if all alternatives have valid weights in the `alternative_weight` table
        $all_alternatives_have_weights = true;
        foreach ($result as $row) {
            $sql = "SELECT * FROM `alternative_weight` WHERE `Alternative_ID` = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$row['Alternative_ID']]);
            $weight_result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($weight_result === false || empty($weight_result['Weight'])) {
                $all_alternatives_have_weights = false;
                break;
            }
        }

        if (!$all_alternatives_have_weights) {
            $response['message'] = 'Some alternatives do not have valid weights.';
            return $response;
        }

        $response['status'] = true;
        $response['message'] = 'The decision has valid alternatives.';
        return $response;
    }
    
}?>