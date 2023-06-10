<?php


class Alternative_Controller
{
    private $model;
    public function __construct($model)
    {
        $this->model = $model;
    }


    public function processRequest($action)
    {
        switch ($action) {
            case 'add_weight':
                $this->add_alternative_weight();
                break;
            case 'add_alternative':
                $this->add_alternative();
                break;
            case 'update_weight':
                $this->update_alternative_weight();
                break;
            case 'edit_alternative':
                $this->edit_alternative();
                break;
            case 'delete_alternative':
                $this->delete_alternative();
                break;
            case 'get_alternative_data':

                $this->get_alternative_data();
                break;
            case 'checkAlternative':
                $this->checkAlternative();
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                exit;
        }
    }
    public function add_alternative()
    {
        // Your existing add_alternative_weight code
        $title = $_POST['title'];
        $decision_id = $_POST['decisionID'];
        $this->model->add_alternative($title, $decision_id);
        echo json_encode(['success' => true]);
        exit;
    }
    public function add_alternative_weight()
    {
        $criteriaID =   (isset($_POST['Criteria_ID'])) ? htmlspecialchars($_POST['Criteria_ID']) : '';
        $alternativeID = (isset($_POST['Alternative_ID'])) ? htmlspecialchars($_POST['Alternative_ID']) : '';
        $weight = (isset($_POST['weight'])) ? htmlspecialchars($_POST['weight']) : '';

        $result = $this->model->insert_alternative_wieght($criteriaID, $alternativeID, $weight);

        if ($result) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }
    public function update_alternative_weight()
    {

        $criteriaId =   (isset($_POST['Criteria_ID'])) ? htmlspecialchars($_POST['Criteria_ID']) : '';
        $alternativeID = (isset($_POST['Alternative_ID'])) ? htmlspecialchars($_POST['Alternative_ID']) : '';
        $weight = (isset($_POST['weight'])) ? htmlspecialchars($_POST['weight']) : '';

        $result = $this->model->update_alternative_weight($alternativeID, $criteriaId, $weight);

        if ($result) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    function edit_alternative()
    {
        $alternativeID = (isset($_POST['Alternative_ID'])) ? htmlspecialchars($_POST['Alternative_ID']) : '';
        $title = (isset($_POST['Alternative_Title'])) ? htmlspecialchars($_POST['Alternative_Title']) : '';

        if ($alternativeID && $title) {
            $result = $this->model->edit_alternative($alternativeID, $title);



            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(array(['success' => false], 'message' => 'Missing Alternative_ID or Alternative_Title'));
        }
    }


    public function delete_alternative()
    {
        $alternativeID = (isset($_POST['Alternative_ID'])) ? htmlspecialchars($_POST['Alternative_ID']) : '';

        if ($alternativeID) {
            $result = $this->model->delete_alternative($alternativeID);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(array(['success' => false], 'message' => 'Missing Alternative_ID'));
        }
    }

    public function get_alternative_data()
    {

        $decisionID = (isset($_POST['decision_id'])) ? htmlspecialchars($_POST['decision_id']) : '';
        $data =  $this->model->get_alternative($decisionID);
        $current_alt_id = null;
        $current_alt = null;
        $restructured_data = array();
        foreach ($data as $row) {
            // Check if we are processing a new alternative
            if ($row['Alternative_ID'] != $current_alt_id) {
                // If yes, store the current alternative in the restructured_data array
                if (!is_null($current_alt)) {
                    $restructured_data[] = $current_alt;
                }
                // Initialize a new alternative object
                $current_alt_id = $row['Alternative_ID'];
                $current_alt = array(
                    'id' => $current_alt_id,
                    'title' => $row['Alternative_Title'],
                    'criteria' => array()
                );
            }
            // Add the current criteria to the current alternative
            $current_criteria = array(
                'id' => $row['Criteria_ID'],
                'title' => $row['Criteria_Title'],
                'terms' => array(
                    array('id' => '1', 'txt' => $row['TermLevel_1']),
                    array('id' => '2', 'txt' => $row['TermLevel_2']),
                    array('id' => '3', 'txt' => $row['TermLevel_3']),
                    array('id' => '4', 'txt' => $row['TermLevel_4']),
                    array('id' => '5', 'txt' => $row['TermLevel_5'])
                )
            );
            $current_alt['criteria'][] = $current_criteria;
        }
        // Store the last alternative in the restructured_data array
        if (!is_null($current_alt)) {
            $restructured_data[] = $current_alt;
        }

        // Return the restructured data as a JSON object
        header('Content-Type: application/json');
        echo json_encode($restructured_data);
    }
    public function checkAlternative()
    {

        $criteriaID = $_POST['Criteria_ID'];
        $alternativeID = $_POST['Alternative_ID'];

        $weight = $this->model->get_alternative_weight($alternativeID, $criteriaID);


        if ($weight !== null) {

            echo json_encode(array('status' => 'success', 'weight' => $weight));
        } else {

            echo json_encode(array('status' => 'error', 'message' => 'Alternative not found'));
        }
    }
}
