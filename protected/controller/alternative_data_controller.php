<?php 

require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';


$alternativeModel = new Alternative_Model($pdo);

$decisionID = (isset($_POST['decision_id'])) ? htmlspecialchars($_POST['decision_id']) : '';
$data =  $alternativeModel->get_alternative($decisionID);
$current_alt_id = null;
$current_alt = null;
$restructured_data = array();
foreach ($data as $row) {
    // Check if we are processing a new alternative
    if ($row['Alternative_ID'] != $current_alt_id) {
        // If yes, store the current alternative in the restructured_data array
        if (!is_null($current_alt)) {
            $restructured_data[] = $current_alt;
        }
        // Initialize a new alternative object
        $current_alt_id = $row['Alternative_ID'];
        $current_alt = array(
            'id' => $current_alt_id,
            'title' => $row['Alternative_Title'],
            'criteria' => array()
        );
    }
    // Add the current criteria to the current alternative
    $current_criteria = array(
        'id' => $row['Criteria_ID'],
        'title' => $row['Criteria_Title'],
        'terms' => array(
            array('id' => '1', 'txt' => $row['TermLevel_1']),
            array('id' => '2', 'txt' => $row['TermLevel_2']),
            array('id' => '3', 'txt' => $row['TermLevel_3']),
            array('id' => '4', 'txt' => $row['TermLevel_4']),
            array('id' => '5', 'txt' => $row['TermLevel_5'])
        )
    );
    $current_alt['criteria'][] = $current_criteria;
}
// Store the last alternative in the restructured_data array
if (!is_null($current_alt)) {
    $restructured_data[] = $current_alt;
}

// Return the restructured data as a JSON object
header('Content-Type: application/json');
echo json_encode($restructured_data);
?>