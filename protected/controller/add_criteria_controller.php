<?php

require_once '../../protected/model/criteria_model.php';
require_once '../../conn.php';


$criteriaModel = new Criteria_Model($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && !empty($_POST['title'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $termID = "1";
    $decision_id = $_POST['decisionID'];
 
    $criteriaModel->add_criteria($title,$termID,$type,$decision_id);
 
    header('Location: ../view/criteria_view.php?id='.$decision_id);
    exit;
}


?>