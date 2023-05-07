<?php

require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';


$alternativeModel = new Alternative_Model($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && !empty($_POST['title'])) {
    $title = $_POST['title'];

    $decision_id = $_POST['decisionID'];
 
    $alternativeModel->add_alternative($title,$decision_id);
 
    header('Location: ../view/alternative_view.php?id='.$decision_id);
    exit;
}


?>