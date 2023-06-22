<?php
require_once '../../conn.php';
require_once '../model/desicion_model.php';

$decisionModel = new Decision_Model($pdo);
$decisions = $decisionModel->get_decisions();

$report = [];
foreach ($decisions as $decision) {
    $execution_times = [];
    
    for ($i = 0; $i < 10; $i++) {
        $start_time = microtime(true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/decido/protected/controller/topsis_rank.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['id' => $decision['decision_ID']]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $end_time = microtime(true);

        $execution_time = $end_time - $start_time;
        $execution_times[] = $execution_time;
    }
    
    $average_execution_time = round(array_sum($execution_times) / count($execution_times) * 1000, 4);
    
    $report[] = [
        'decision_ID' => $decision['decision_ID'],
        'decision_Title' => $decision['decision_Title'],
        'average_execution_time' => $average_execution_time
    ];
}

echo json_encode($report);
?>