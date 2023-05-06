<?php

require_once '../../protected/model/criteria_model.php';
require_once '../../conn.php';


$criteriaModel = new Criteria_Model($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && !empty($_POST['title'])) {
    $title = $_POST['title'];
    $termID = $_POST['term'];
    $decision_id = $_POST['decisionID'];
 
    $criteriaModel->add_criteria($title,$termID,$decision_id);
 
    header('Location: ../view/criteria_view.php?id='.$decision_id);
    exit;
}


?>