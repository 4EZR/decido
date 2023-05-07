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

    // Get all decisions

    public function get_alternative($decisionID) {
        $stmt = $this->db->prepare("SELECT a.Alternative_ID, a.Alternative_Title, c.Criteria_ID, c.Criteria_Title, l.TermLevel_1, l.TermLevel_2, l.TermLevel_3, l.TermLevel_4, l.TermLevel_5 FROM alternatives a JOIN criterias c ON c.Decision_ID = a.Decision_ID JOIN linguistic_terms l ON l.Term_ID = c.Linguistic_Term WHERE a.Decision_ID = :decision_id ORDER BY a.Alternative_ID, c.Criteria_ID");
        $stmt->execute([$decisionID]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
    }


    public function update_alternative($alternativeID,$criteriaID,$weight){
        
    }
}
?>