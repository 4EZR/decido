<?php 

require_once '../model/desicion_model.php';
require_once '../../protected/model/alternative_model.php';
require_once '../../conn.php';
require_once 'decision_controller.php';
$model = new Decision_Model($pdo);

$controller = new DecisionController($model);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller->processRequest($action);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No action provided']);
    exit;
}
?>