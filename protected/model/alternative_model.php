<?php


class Alternative_Model
{
    private $db;

    // Constructor
    public function __construct($pdo)
    {
        // Connect to the database
        $this->db = $pdo;
    }

    // Add decision
    public function add_alternative($title, $decisionID)
    {
        $stmt = $this->db->prepare("INSERT INTO `alternatives`(`Alternative_Title`, `Decision_ID`) VALUES (?,?)");
        $stmt->execute([$title, $decisionID]);
        $stmt->closeCursor();
    }
    public function edit_alternative($alternativeID, $title) {
        $sql = "UPDATE alternatives SET Alternative_Title = :title WHERE Alternative_ID = :alternative_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':alternative_id', $alternativeID, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $result = $stmt->execute();
        return $result;
    }

    public function delete_alternative($alternativeID) {
        $sql = "DELETE FROM alternatives WHERE Alternative_ID = :alternative_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':alternative_id', $alternativeID, PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result;
    }
    // Get all decisions

    public function get_alternative($decisionID)
    {
        $stmt = $this->db->prepare("SELECT a.Alternative_ID, a.Alternative_Title, c.Criteria_ID, c.Criteria_Title, l.TermLevel_1, l.TermLevel_2, l.TermLevel_3, l.TermLevel_4, l.TermLevel_5 FROM alternatives a JOIN criterias c ON c.Decision_ID = a.Decision_ID JOIN linguistic_terms l ON l.Term_ID = c.Linguistic_Term WHERE a.Decision_ID = :decision_id ORDER BY a.Alternative_ID, c.Criteria_ID");
        $stmt->execute([$decisionID]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
    }


    public function update_alternative_weight($alternativeID, $criteriaID, $weight)
    {
        $stmt = $this->db->prepare("UPDATE `alternative_weight` SET `Weight` = ? WHERE `Alternative_ID` = ? AND `Criteria_ID` = ?");
      
        $result = $stmt->execute([$weight, $alternativeID, $criteriaID]);
        $stmt->closeCursor();
        return $result;
    }
    public function update_importance($importanceLevel, $criteriaId){
        $stmt =  $this->db->prepare('UPDATE `criterias` SET `Criteria_Importance`=? WHERE `criteria_ID` = ?');
        $result = $stmt->execute([$importanceLevel, $criteriaId]);
        $stmt->closeCursor();
        return $result;
    }

    public function insert_alternative_wieght($criteriaID, $alternativeID, $weight)
    {
        $stmt = $this->db->prepare("INSERT INTO `alternative_weight` (`Criteria_ID`, `Alternative_ID`, `Weight`) VALUES (?, ?, ?)");
        $result = $stmt->execute([$criteriaID, $alternativeID, $weight]);
        $stmt->closeCursor();
        return $result;
    }

    public function get_alternative_weight($alternativeID, $criteriaID)
    {
        $stmt = $this->db->prepare("SELECT `Weight` FROM `alternative_weight` WHERE `Alternative_ID` = ? AND `Criteria_ID` = ?");
        $stmt->execute([$alternativeID, $criteriaID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['Weight'] ?? null;
    }
}
