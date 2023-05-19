<?php 

require_once '../model/desicion_model.php';
require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';

$decisionModel = new Decision_Model($pdo);

$alternativeModel = new Alternative_Model($pdo);

$id = isset($_POST['id']) ? $_POST['id'] : '';


$Matrix = $decisionModel->get_decision_matrix_data($id);

$fuzzy_decision_matrix = [];

$criteria_matrix = [];
foreach ($Matrix as $row ) {

    
    $criteriaImportance = $row['Criteria_Importance'];
    $criteria_id = $row['Criteria_ID'];
   

        $lmu = [
            "L" => get_lower_bound(get_alpha($criteriaImportance)),
            "M" => get_alpha($criteriaImportance),
            "U" => get_upper_bound(get_alpha($criteriaImportance))
        ];

        $criteria_matrix["C".$criteria_id] = $lmu;

  

}


foreach ($Matrix as $row ) {

    $alternative_id = $row['Alternative_ID'];
    $criteriaImportance = $row['Criteria_Importance'];
    $criteria_id = $row['Criteria_ID'];
    $criteria_title = $row['Criteria_Title'];
    $weight = $row['Weight'];

        $lmu = [
            "L" => get_lower_bound_tfn(get_alpha_tfn($weight)),
            "M" => get_alpha_tfn($weight),
            "U" => get_upper_bound_tfn(get_alpha_tfn($weight))
        ];

        $fuzzy_decision_matrix["A".$alternative_id]["C".$criteria_id] = $lmu;

  

}

function create_tfn($lower_bound, $upper_bound) {
    return array($lower_bound, ($lower_bound + $upper_bound) / 2, $upper_bound);
}

function get_alpha($criterion_value) {
    $criterion_mapping = array(
        '1' => 0.1,
        '2' => 0.3,
        '3' => 0.5,
        '4' => 0.7,
        '5' => 0.9
    );
    return $criterion_mapping[$criterion_value];
}

function get_alpha_tfn($criterion_value) {
    $criterion_mapping = array(
        '1' => 1,
        '2' => 3,
        '3' => 5,
        '4' => 7,
        '5' => 9
    );
    return $criterion_mapping[$criterion_value];
}


$importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];

$matrix_term = [];
foreach ($importanceLevels as $level =>&$value ) {

    $matrix_term[$level][$value]["L"] = get_lower_bound(get_alpha($level + 1));
    $matrix_term[$level][$value]["M"]= get_alpha($level+1);
    $matrix_term[$level][$value]["U"] = get_upper_bound(get_alpha($level + 1));
}



function get_lower_bound($mid) {
    return ($mid - 0.15 < 0) ? 0 : ($mid - 0.15);
}


function get_upper_bound($mid) {
    return ($mid + 0.15 > 1) ? 1 : ($mid + 0.15);
}


function get_lower_bound_tfn($mid) {
    return ($mid - 2 < 1) ? 1 : ($mid - 2);
}


function get_upper_bound_tfn($mid) {
    return ($mid + 2 > 9) ? 9 : ($mid + 2);
}




function findMaxValues($inputArray) {
    $result = [];

    foreach ($inputArray as $subArray) {
        foreach ($subArray as $key => $values) {
            if (!isset($result[$key])) {
                $result[$key] = 0;
            }

            $maxValue = max($values['L'], $values['M'], $values['U']);
            $result[$key] = max($result[$key], $maxValue);
        }
    }

    return $result;
}

$normalized_criteria = findMaxValues($fuzzy_decision_matrix);


foreach ($fuzzy_decision_matrix as $alternative_id => $criteria_values)
{
    foreach ($criteria_values as $criteria_title => $lmu)
    {
        $l_value = round(($lmu['L'] / $normalized_criteria [$criteria_title]),2);
        $m_value = round(($lmu['M'] / $normalized_criteria [$criteria_title]),2);
        $u_value = round(($lmu['U'] / $normalized_criteria [$criteria_title]),2);
        $weighted_lmu = ['L' => $l_value, 'M' => $m_value, 'U' => $u_value];

        // Fill in the weighted matrix
        $weighted_matrix[$alternative_id][$criteria_title] = $weighted_lmu;
    }
}



$final_fuzzy_matrix = [];
foreach ($weighted_matrix as $alternative_id => $criteria_values)
{
    foreach ($criteria_values as $criteria_title => $lmu)
    {
        $l_value = round(($lmu['L'] * $criteria_matrix[$criteria_title]['L']),2);
        $m_value = round(($lmu['M'] * $criteria_matrix[$criteria_title]['M']),2);
        $u_value =round(($lmu['U'] * $criteria_matrix[$criteria_title]['U']),2);
        $weighted_lmu = ['L' => $l_value, 'M' => $m_value, 'U' => $u_value];

        // Fill in the weighted matrix
        $final_fuzzy_matrix[$alternative_id][$criteria_title] = $weighted_lmu;
    }
}


    function calculate_pis_nis($matrix) {
        $pis = [];
        $nis = [];
        foreach ($matrix as $alternative) {
            foreach ($alternative as $criterion => $values) {
                if (!isset($pis[$criterion])) {
                    $pis[$criterion] = $values;
                    $nis[$criterion] = $values;
                } else {
                    $pis[$criterion] = [
                        'L' => max($pis[$criterion]['L'], $values['L']),
                        'M' => max($pis[$criterion]['M'], $values['M']),
                        'U' => max($pis[$criterion]['U'], $values['U'])
                    ];
                    $nis[$criterion] = [
                        'L' => min($nis[$criterion]['L'], $values['L']),
                        'M' => min($nis[$criterion]['M'], $values['M']),
                        'U' => min($nis[$criterion]['U'], $values['U'])
                    ];
                }
            }
        }
    
        $result = [];
        foreach ($pis as $criterion => $values) {
            $result[$criterion] = [
                'pis' => $values['U'],
                'nis' => $nis[$criterion]['L']
            ];
        }
    
        return $result;
    }
$pis_nis = calculate_pis_nis($final_fuzzy_matrix);



function calculate_vikor_ranking($fuzzy_decision_matrix, $pis_nis, $v = 0.5,$model,$id)
{
    // Calculate the separation measures
    $separation_measures = [];
    foreach ($fuzzy_decision_matrix as $alternative => $criteria_values) {
        $S = 0;
        $R = 0;
        foreach ($criteria_values as $criterion => $values) {
            $weight = ($values['M'] - $pis_nis[$criterion]['nis']) / ($pis_nis[$criterion]['pis'] - $pis_nis[$criterion]['nis']);
            $S += $weight;
            $R = max($R, $weight);
        }
        $separation_measures[$alternative] = ["S" => $S, "R" => $R];
    }

    // Calculate the VIKOR index Q
    $Q_values = [];
    $min_S = min(array_column($separation_measures, 'S'));
    $max_S = max(array_column($separation_measures, 'S'));
    $min_R = min(array_column($separation_measures, 'R'));
    $max_R = max(array_column($separation_measures, 'R'));

    foreach ($separation_measures as $alternative => $values) {
        $Q = $v * (($values['S'] - $min_S) / ($max_S - $min_S)) + (1 - $v) * (($values['R'] - $min_R) / ($max_R - $min_R));
        $Q_values[$alternative] = round($Q,2);
    }

    // Sort the alternatives by their Q values
    asort($Q_values);
    $Q_values = array_combine(array_map(function($key) {
        return (int)substr($key, 1);
    }, array_keys($Q_values)), $Q_values);
   
    $rank = 1;
    $result = [];
    foreach ($Q_values as $alternative_id => $q_value) {
        $alternatives = $model->get_alternative($id);
        foreach ($alternatives as $alternative) {
            if ($alternative_id == $alternative["Alternative_ID"]) {
                $result[$alternative_id] = [
                    'alternative_name' => $alternative["Alternative_Title"],
                    'q_value' => $q_value,
                    'rank' => $rank,
                ];
                break;
            }
        }
        $rank++;
    }

    return $result;
 
}

$vikor_score = calculate_vikor_ranking($final_fuzzy_matrix, $pis_nis, $v = 0.5,$alternativeModel,$id);



echo json_encode($vikor_score, JSON_PRETTY_PRINT);
?>