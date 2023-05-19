<?php 

require_once '../../protected/model/desicion_model.php';
require_once '../../conn.php';


$decisionModel = new Decision_Model($pdo);
 $search_keyword = $_POST['search'];

$decisions = $decisionModel->search_decisions($search_keyword);


echo json_encode($decisions);

 ?>