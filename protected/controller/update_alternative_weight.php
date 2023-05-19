<?php 

require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';


$alternativeModel = new Alternative_Model($pdo);


$criteriaId =   (isset($_POST['Criteria_ID']))?htmlspecialchars($_POST['Criteria_ID']):'';
$alternativeID = (isset($_POST['Alternative_ID']))?htmlspecialchars($_POST['Alternative_ID']):'';
$weight = (isset($_POST['weight']))?htmlspecialchars($_POST['weight']):'';

$result = $alternativeModel->update_alternative_weight($alternativeID, $criteriaId,$weight);

if ($result) {
    echo json_encode(array('status' => 'success'));
  } else {
    echo json_encode(array('status' => 'error'));
  }

?>