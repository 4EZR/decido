<?php 

require_once '../model/criteria_model.php';
require_once '../../conn.php';
require_once 'criteria_controller.php';
$model = new Criteria_Model($pdo);

$controller = new Criteria_Controller($model);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller->processRequest($action);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No action provided']);
    exit;
}
?>