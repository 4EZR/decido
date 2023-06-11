<?php 

require_once '../model/alternative_model.php';
require_once '../../conn.php';
require_once 'alternative_controller.php';
$model = new Alternative_Model($pdo);

$controller = new Alternative_Controller($model);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller->processRequest($action);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No action provided true']);
    exit;
}
?>