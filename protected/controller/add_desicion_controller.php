<?php

require_once '../../protected/model/desicion_model.php';
require_once '../../conn.php';


$decisionModel = new Decision_Model($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && !empty($_POST['title'])) {
    $title = $_POST['title'];
 
    $decisionModel->add_decision($title);
 
    header('Location: ../../index.php');
    exit;
}


?>