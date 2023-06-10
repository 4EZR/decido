<?php


class Criteria_Controller
{
    private $model;


    public function __construct($model)
    {
        $this->model = $model;
    }
    public function processRequest($action)
    {
        switch ($action) {
            case 'addCriteria':
                $this->add_criteria();
                break;
            case 'editCriteria':
                $this->edit_criteria();
                break;
            case 'deleteCriteria':
                $this->delete_criteria();
                break;

            case 'getCriteria':
                $this->listCriteria();
                break;
            case 'updateWeight':
                $this->update_importance();
                break;
           
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                exit;
        }
    }
    public function add_criteria()
    {
        $title = $_POST['title'];
        $term = "1";
        $type = $_POST['type'];
        $decisionID = $_POST['decision_id'];

        $this->model->add_criteria($title, $term, $type, $decisionID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function delete_criteria()
    {
        $criteriaID = $_POST['id'];
        $result = $this->model->delete_criteria($criteriaID);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function edit_criteria()
    {
        $criteriaID = $_POST['criteria_id'];
        $title = $_POST['title'];
        $term = "1";
        $type = $_POST['type'];

        $result = $this->model->edit_criteria($criteriaID, $title, $term, $type);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function update_importance()
    {
        $importanceLevel = $_POST['importance_level'];
        $criteriaID = $_POST['criteria_id'];

        $result = $this->model->update_importance($importanceLevel, $criteriaID);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    public function listCriteria()
    {
        $decisionID = $_POST['decision_id'];
        $criterias = $this->model->get_criterias($decisionID);

        echo json_encode($criterias);
        exit;
    }


}
