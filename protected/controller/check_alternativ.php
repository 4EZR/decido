<?php


// Get the criteria ID and alternative ID from the POST request
require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';

$criteriaID = $_POST['criteriaID'];
$alternativeID = $_POST['alternativeID'];

// Create a new instance of your DB class




$criteriaModel = new Criteria_Model($pdo);


// Call the get_weight function to check if the alternative exists
$weight = $criteriaModel->($criteriaID, $alternativeID);

// Check if a weight was returned
if ($weight !== null) {
  // Alternative exists, return its weight
  echo json_encode(array('status' => 'success', 'weight' => $weight));
} else {
  // Alternative does not exist
  echo json_encode(array('status' => 'error', 'message' => 'Alternative not found'));
}
?>
