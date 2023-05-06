<?php 

require_once '../../protected/model/criteria_model.php';
require_once '../../conn.php';


$criteriaModel = new Criteria_Model($pdo);

$criteriaId =  (isset($_POST['criteria_id'])) ? htmlspecialchars($_POST['criteria_id']) : '';
$importanceLevel = (isset($_POST['importance_level'])) ? htmlspecialchars($_POST['importance_level']) : '';

$result = $criteriaModel->update_importance($importanceLevel, $criteriaId);

if ($result) {
    echo json_encode(array('status' => 'success'));
  } else {
    echo json_encode(array('status' => 'error'));
  }

?>