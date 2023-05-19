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

function calculate_distance_closeness_triangular_fuzzy($weighted_matrix, $pis_nis_matrix) {
    $result = [];

    foreach ($weighted_matrix as $alternative_key => $alternative) {
        $criterion_distance = [];

        foreach ($alternative as $criterion => $values) {
            $pis = $pis_nis_matrix[$criterion]['pis'];
            $nis = $pis_nis_matrix[$criterion]['nis'];

            $distance_pis = pow($values['L'] - $pis, 2) + pow($values['M'] - $pis, 2) + pow($values['U'] - $pis, 2);
            $distance_nis = pow($values['L'] - $nis, 2) + pow($values['M'] - $nis, 2) + pow($values['U'] - $nis, 2);

            $criterion_distance[$criterion] = [
                'distance_pis' => round( sqrt($distance_pis / 3),2),
                'distance_nis' => round(sqrt($distance_nis / 3),2),
            ];
        }

        $result[$alternative_key] = $criterion_distance;
    }

    return $result;
}

$distance_matrix =calculate_distance_closeness_triangular_fuzzy($final_fuzzy_matrix,$pis_nis);


function calculate_fuzzy_closeness_coefficient($distance_matrix) {
    $result = [];

    foreach ($distance_matrix as $alternative_key => $criterion_distance) {
        $distance_pis_sum = 0;
        $distance_nis_sum = 0;

        foreach ($criterion_distance as $criterion => $values) {
            $distance_pis = $values['distance_pis'];
            $distance_nis = $values['distance_nis'];

            $distance_pis_sum += $distance_pis;
            $distance_nis_sum += $distance_nis;
        }

        $cc = round( $distance_nis_sum / ($distance_pis_sum + $distance_nis_sum),2);

        $result[$alternative_key] = [
            'd_plus' => $distance_pis_sum,
            'd_minus' => $distance_nis_sum,
            'cc' => $cc
        ];
    }

    return $result;
}

$fuzzy_closeness=calculate_fuzzy_closeness_coefficient($distance_matrix);



function sort_alternatives_by_cc($fuzzy_closeness_coefficient) {
    uasort($fuzzy_closeness_coefficient, function($a, $b) {
        return $b['cc'] <=> $a['cc'];
    });

    $ranked_alternatives = [];
    $rank = 1;

    foreach ($fuzzy_closeness_coefficient as $alternative => $values) {
        $ranked_alternatives[] = [
            'alternative' => $alternative,
            'rank' => $rank,
            'cc' => $values['cc']
        ];
        $rank++;
    }
    foreach ($ranked_alternatives as &$alternative) {
        $alternative['alternative_id'] = substr($alternative['alternative'], 1);
        unset($alternative['alternative']);
    }

    return $ranked_alternatives;
}
$final_rank=sort_alternatives_by_cc($fuzzy_closeness);

foreach ($final_rank as &$item) {
    $alternatives = $alternativeModel->get_alternative($id);
    foreach ($alternatives as $alternative) {
        if ($item["alternative_id"] == $alternative["Alternative_ID"]) {
            $item["alternative_name"] = $alternative["Alternative_Title"];
            break;
        }
    }
}
echo json_encode($final_rank, JSON_PRETTY_PRINT);
?>