<?php
class DecisionController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
    public function processRequest($action)
    {
        switch ($action) {
            case 'addDecisionAjax':
                $this->addDecisionAjax();
                break;
            case 'updateDecisionAjax':
                $this->updateDecisionAjax();
                break;
            case 'deleteDecisionAjax':
                $this->deleteDecisionAjax();
                break;
            case 'listDecisions':
                $this->listDecisionsAjax();
                break;
            case 'checkCriteria':
                $this->checkCriteriaAjax();
                break;
            case 'checkAlternative':
                $this->checkAlternativeAjax();

                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                exit;
        }
    }

    public function listDecisions()
    {
        return $this->model->get_decisions();
    }

    public function addDecision()
    {
        if (isset($_POST['decision_text'])) {
            $this->model->add_decision($_POST['decision_text']);
            header('Location: index.php');
            exit;
        }
    }

    public function updateDecision()
    {
        if (isset($_POST['id']) && isset($_POST['updated_text'])) {
            $id = $_POST['id'];
            $title = $_POST['updated_text'];
            $this->model->update_decision($id, $title);
            header('Location: index.php');
            exit;
        }
    }

    public function deleteDecision()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->delete_decision($id);
            header('Location: index.php');
            exit;
        }
    }
    public function addDecisionAjax()
    {
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $this->model->add_decision($title);
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function updateDecisionAjax()
    {
        if (isset($_POST['id']) && isset($_POST['title'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $this->model->update_decision($id, $title);
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteDecisionAjax()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->model->delete_decision($id);
            echo json_encode(['success' => true]);
            exit;
        }
    }
    public function listDecisionsAjax()
    {
        $decisions = $this->model->get_decisions();
        echo json_encode($decisions);
        exit;
    }

    public function checkCriteriaAjax() {
      
     
        if(isset($_POST['decision_id'])){
            $id = $_POST['decision_id'];
            $result =$this-> model->decisionHasValidCriteria($id);    
            echo json_encode($result);
        }
      
    }


    public function checkAlternativeAjax(){
        if(isset($_POST['decision_id'])){
            $id = $_POST['decision_id'];
            $result =$this-> model->decisionHasValidAlternatives($id);  
            echo json_encode($result);
        }
    }
}
