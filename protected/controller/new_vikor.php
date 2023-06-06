<?php 

require_once '../model/desicion_model.php';
require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';

$decisionModel = new Decision_Model($pdo);

$alternativeModel = new Alternative_Model($pdo);
$id = isset($_POST['id']) ? $_POST['id'] : '';

$id = 11;
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

function calculate_distance_closeness_triangular_fuzzy($weighted_matrix, $pis_nis_matrix) {
    $result = [];

    foreach ($weighted_matrix as $alternative_key => $alternative) {
        $criterion_distance = [];

        foreach ($alternative as $criterion => $values) {
            $pis = $pis_nis_matrix[$criterion]['pis'];
   

            $distance_pis = pow($values['L'] - $pis, 2) + pow($values['M'] - $pis, 2) + pow($values['U'] - $pis, 2);
           
            $criterion_distance[$criterion] = [
                'distance_pis' => round( sqrt($distance_pis / 3),2),
     
            ];
        }

        $result[$alternative_key] = $criterion_distance;
    }

    return $result;
}

$distance_matrix =calculate_distance_closeness_triangular_fuzzy($final_fuzzy_matrix,$pis_nis);

function vikor($data, $weights, $v = 0.5) {
    $si = [];
    $ri = [];
    $qi = [];

    // Calculate si and ri values
    foreach ($data as $alternative => $criteria) {
        $weighted_distances = [];
        foreach ($criteria as $criterion => $values) {
            $weighted_distances[] = $values['distance_pis'] * $weights[$criterion];
        }
        $si[$alternative] = array_sum($weighted_distances);
        $ri[$alternative] = max($weighted_distances);
    }

    // Calculate qi values
    $min_si = min($si);
    $max_si = max($si);
    $min_ri = min($ri);
    $max_ri = max($ri);
    foreach ($si as $alternative => $value) {
        $qi[$alternative] = $v * ($value - $min_si) / ($max_si - $min_si) +
                            (1 - $v) * ($ri[$alternative] - $min_ri) / ($max_ri - $min_ri);
    }

    return ['si' => $si, 'ri' => $ri, 'qi' => $qi];
}

$sqr = vikor($distance_matrix, $normalized_criteria);


function get_ranked_alternatives($matrix, $model,$id) {
    $Q_values = $matrix['qi'];

    // Sort alternatives by qi values
    asort($Q_values);

    $result = [];
    $rank = 1;
    foreach ($Q_values as $alternative_id => $q_value) {
        $alternatives = $model->get_alternative($id);
        $numeric_id = substr($alternative_id, 1); // Remove the "A" prefix
        
        foreach ($alternatives as $alternative) {
            if ($numeric_id == $alternative["Alternative_ID"]) {
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
$final_rank = get_ranked_alternatives($sqr, $alternativeModel, $id);

echo json_encode($final_rank, JSON_PRETTY_PRINT);
?>