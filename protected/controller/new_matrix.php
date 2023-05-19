<?php 

require_once '../model/desicion_model.php';

require_once '../../conn.php';

$decisionModel = new Decision_Model($pdo);

$Matrix = $decisionModel->get_decision_matrix_data(11);

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
echo '<pre>';
print_r($criteria_matrix);
echo '</pre>';

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


echo "<h1>TFN CRITERIA MATRIX</h1>";
echo '<pre>';
print_r($matrix_term);
echo '</pre>';
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



echo "<h1>FUZZY DECISION MATRIX</h1>";
echo '<pre>';
print_r($fuzzy_decision_matrix);
echo '</pre>';



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
echo "<h1>Normalize Criteria</h1>";
echo '<pre>';
$normalized_criteria = findMaxValues($fuzzy_decision_matrix);
print_r($normalized_criteria );
echo '</pre>';

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

echo "<h1>Weighted Criteria</h1>";
echo '<pre>';

print_r( $weighted_matrix );
echo '</pre>';

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

echo "<h1>Final MAtrix</h1>";
echo '<pre>';

print_r( $final_fuzzy_matrix );
echo '</pre>';
$weights = [0.5, 0.5]; // Adjust the weights as per your requirement
$max_closeness = 0;
$rank = [];

// Step 1: Calculate Closeness and R values
foreach ($weighted_matrix as $alternative_id => $criteria_values) {
    $closeness_values = [];
    
    foreach ($criteria_values as $criteria_title => $lmu) {
        $best_value = max($lmu['L'], $lmu['M'], $lmu['U']);
        $worst_value = min($lmu['L'], $lmu['M'], $lmu['U']);
        
        $closeness = ($best_value - $lmu['M']) / ($best_value - $worst_value);
        $closeness_values[] = $closeness;
    }
    
    $max_closeness = max($max_closeness, max($closeness_values));
    
    $rank[$alternative_id] = [
        'closeness' => $closeness_values,
        'r' => null,
    ];
}

// Step 2: Calculate R values and rank alternatives
foreach ($rank as &$alternative) {
    $closeness_values = $alternative['closeness'];
    
    $r = $weights[0] * max($closeness_values) + $weights[1] * ($max_closeness - min($closeness_values));
    $alternative['r'] = $r;
}

// Sort alternatives based on their R values (VIKOR rank)
arsort($rank);

// Display the VIKOR rank
$vikor_rank = array_keys($rank);
print_r($vikor_rank);
function calculate_pis_nis($matrix) {
    $pis = [];
    $nis = [];
    foreach ($matrix as $alternative) {
        foreach ($alternative as $criterion => $values) {
            if (!isset($pis[$criterion])) {
                $pis[$criterion] = $values;
                $nis[$criterion] = $values;
            } else {
                $pis[$criterion]['L'] = max($pis[$criterion]['L'], $values['L']);
                $pis[$criterion]['M'] = max($pis[$criterion]['M'], $values['M']);
                $pis[$criterion]['U'] = max($pis[$criterion]['U'], $values['U']);
                $nis[$criterion]['L'] = min($nis[$criterion]['L'], $values['L']);
                $nis[$criterion]['M'] = min($nis[$criterion]['M'], $values['M']);
                $nis[$criterion]['U'] = min($nis[$criterion]['U'], $values['U']);
            }
        }
    }
    return [$pis, $nis];
}

// Step 3: Calculate separation measures
function calculate_separation_measures($matrix, $pis, $nis) {
    $separation_measures = [];
    foreach ($matrix as $alternative_key => $alternative) {
        $separation_measures[$alternative_key] = [
            'positive_ideal' => 0,
            'negative_ideal' => 0
        ];
        foreach ($alternative as $criterion => $values) {
            $separation_measures[$alternative_key]['positive_ideal'] += pow($pis[$criterion]['M'] - $values['M'], 2);
            $separation_measures[$alternative_key]['negative_ideal'] += pow($values['M'] - $nis[$criterion]['M'], 2);
        }
        $separation_measures[$alternative_key]['positive_ideal'] = sqrt($separation_measures[$alternative_key]['positive_ideal']);
        $separation_measures[$alternative_key]['negative_ideal'] = sqrt($separation_measures[$alternative_key]['negative_ideal']);
    }
    return $separation_measures;
}

// Step 4: Compute relative closeness
function calculate_relative_closeness($separation_measures) {
    $relative_closeness = [];
    foreach ($separation_measures as $alternative_key => $measures) {
        $relative_closeness[$alternative_key] = $measures['negative_ideal'] / ($measures['positive_ideal'] + $measures['negative_ideal']);
    }
    return $relative_closeness;
}

// Step 5: Rank alternatives


// Perform fuzzy VIKOR method steps
list($pis, $nis) = calculate_pis_nis($final_fuzzy_matrix );
echo "<h1>PIS</h1>";
echo '<pre>';

print_r( $pis );
echo '</pre>';
$separation_measures = calculate_separation_measures($final_fuzzy_matrix , $pis, $nis);
$relative_closeness = calculate_relative_closeness($separation_measures);
$ranked_alternatives = rank_alternatives($relative_closeness);

print_r($ranked_alternatives);

function rank_alternatives($relative_closeness) {
    arsort($relative_closeness);
    return $relative_closeness;
}

function calculate_ideal_solutions($weighted_matrix) {
    $positive_ideal = [];
    $negative_ideal = [];

    foreach ($weighted_matrix as $alternative_key => $alternative) {
        foreach ($alternative as $criterion => $values) {
            if (!isset($positive_ideal[$criterion])) {
                $positive_ideal[$criterion] = $values['M'];
                $negative_ideal[$criterion] = $values['M'];
            } else {
                $positive_ideal[$criterion] = max($positive_ideal[$criterion], $values['M']);
                $negative_ideal[$criterion] = min($negative_ideal[$criterion], $values['M']);
            }
        }
    }

    return [$positive_ideal, $negative_ideal];
}

function calculate_distance_closeness($weighted_matrix, $positive_ideal, $negative_ideal) {
    $distances = [];
    $closenesses = [];

    foreach ($weighted_matrix as $alternative_key => $alternative) {
        $positive_distance_squared = 0;
        $negative_distance_squared = 0;

        foreach ($alternative as $criterion => $values) {
            $positive_distance_squared += pow($values['M'] - $positive_ideal[$criterion], 2);
            $negative_distance_squared += pow($values['M'] - $negative_ideal[$criterion], 2);
        }

        $positive_distance = sqrt($positive_distance_squared);
        $negative_distance = sqrt($negative_distance_squared);
        $distances[$alternative_key] = $negative_distance / ($positive_distance + $negative_distance);
        $closenesses[$alternative_key] = 1 / ($distances[$alternative_key] + 1);
    }

    return [$distances, $closenesses];
}


// Step 3: Calculate the positive and negative ideal solutions
list($positive_ideal, $negative_ideal) = calculate_ideal_solutions($final_fuzzy_matrix);

// Step 4: Calculate the distances and closenesses
list($distances, $closenesses) = calculate_distance_closeness($final_fuzzy_matrix , $positive_ideal, $negative_ideal);

// Step 5: Rank alternatives
$ranked_alternatives = rank_alternatives($closenesses);

print_r($ranked_alternatives);