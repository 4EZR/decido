<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    require_once('../../js/library.php');


    ?>
    <link rel="stylesheet" href="../../styles/style.css" />
    <title>Decido</title>
</head>

<body class=" ">

    <?php
    $cardID = $_GET['id'];

    require_once '../../conn.php';
    require_once '../model/desicion_model.php';
    require_once '../model/criteria_model.php';
    require_once '../model/linguistic_terms_model.php';


    $termModel = new Term_Model($pdo);

    $decisionModel = new Decision_Model($pdo);
    $decision = $decisionModel->get_decision_byID($cardID);


    $criteriaModel = new Criteria_Model($pdo);

    $terms = $termModel->get_linguistic_term();
    $criterias = $criteriaModel->get_criterias($cardID);

    require_once('navbar.php');
    ?>

    <section id="content-2" class="container-criteria bg-dbrown py-4 ">
        <div class="container-xxl">
            <nav style="--bs-breadcrumb-divider: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%228%22 height=%228%22%3E%3Cpath d=%22M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z%22 fill=%22white%22/%3E%3C/svg%3E');" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-decoration-none text-white "><a class='text-decoration-none text-white text-orange' href="../../index.php">Home</a></li>
                    <li class="breadcrumb-item text-decoration-none text-white"><a class='text-decoration-none text-white text-orange' href="decision_detail_view.php?id=<?= $cardID ?>">Decision Detail</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Library</li>
                </ol>
            </nav>
            <div class='m-0 p-0  '>
                <p class="h2 fw-bold heading text-white">Criteria</p>
                <p class="h5 text-white fw-normal"><?= $decision['decision_Title']; ?></p>
                <button class="add-criteria btn btn-warning" data-bs-toggle="modal" data-bs-target="#AddCriteria-Modal"> <i class='bx bx-plus me-1'></i> New Criteria</button>
            </div>
        </div>
    </section>
    <section>
        <div class="container-xxl py-5">


            <p class="text-secondary">List of Criteria</p>


            <div id='criteria-list' class="d-flex align-items-stretch flex-wrap">
                <?php
                $importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
                $counter = 0;
                foreach ($criterias as $criteria) {
                    $counter++;
                    echo "<div class='criteria-item  me-5 mb-5' data-id='" . $criteria['criteria_ID'] . "'>";
                    echo '<div class="card p-3 bg-brown  border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $criteria['criteria_ID'] . '">';

                    echo '<div class="d-flex">';
                    //echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-primary text-white fw-normal">' . $decision['decision_Title'] . '</span>';

                    echo '</div>';
                    echo '<div class="d-flex w-100 justify-content-between align-items-center">';

                    echo  '<p class="p-0 m-0 fs-1 text-orange heading">C' . $counter . '</p>';
                    echo '<div class="btn-group dropstart">
          <button type="button" class="btn bg-brown  p-0 m-0 rounded-1  menu-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-dots-vertical-rounded h3 m-0 p-0"></i>
          </button>
          <ul class="dropdown-menu m-0 p-0">
             <li class="m-0 p-0"><a class="dropdown-item edit-btn fw-bold p-2" data-id="' . $criteria['criteria_ID'] . '" href="#">Edit Decision</a></li>
            <li><hr class="dropdown-divider m-0 p-0"></li>
            <li class="m-0 p-0"><a class="dropdown-item del-btn fw-bold p-2 text-danger" data-id="' . $decision['decision_ID'] . '" href="#"><i class="bx bxs-trash-alt"></i> Delete Decision</a></li>
          </ul>
        </div>';
                    echo '</div>';
                    echo  '<p class="p-0 mt-2 fs-3 heading">' . $criteria['Criteria_Title'] . '</p>';
                    echo "<select class='form-select-sm form-select importance-criteria-select border border-primary border-3' data-id='" . $criteria['criteria_ID'] . "'>";

                    echo "<p class='text-secondary fw-normal  mb-3 p-0'><small> " . $criteria['TermLevel_1'] . ", " . $criteria['TermLevel_2'] . ", " . $criteria['TermLevel_3'] . ", " . $criteria['TermLevel_4'] . ", " . $criteria['TermLevel_5'] .  "</small></p>";

                    echo "<option value='0'>Please select one</option>"; // Add this line for the 0 value option
                    foreach ($importanceLevels as $index => $level) {
                        $optionValue = $index + 1; // Use 1-based indexing
                        $selected = ($criteria['Criteria_Importance'] == $optionValue) ? 'selected' : '';
                        echo "<option value='$optionValue' $selected>$level</option>";
                    }
                    echo "</select>";
                    echo '</div>';

                    echo '</div>';
                }

                ?>

            </div>



        </div>
    </section>

</body>
<div class="modal fade" id="AddCriteria-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Criteria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../controller/add_criteria_controller.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
                        <input type="text" name="title" class="form-control" id="#input-desicion-title" aria-describedby="emailHelp">
                        <input type="hidden" name='decisionID' class="form-control" value="<?= $cardID ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
                        <select class="form-select" name='type' aria-label="Default select example">
                            <option selected>Select Type</option>
                            <option value="0">Benefit Type</option>
                            <option value="1">Cost Type</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="EditCriteria-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Criteria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../controller/add_criteria_controller.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Criteria</label>
                        <input type="text" name="title" class="form-control" id="#input-desicion-title" aria-describedby="emailHelp">

                        <input type="hidden" name='criteriaID' class="form-control" id="hidden-criteria">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Criteria Type</label>
                        <select class="form-select" name='type' aria-label="Default select example">
                            <option selected>Select Type</option>
                            <option value="0">Benefit Type</option>
                            <option value="1">Cost Type</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.edit-btn', function(event) {
        var cardId = $(this).attr('data-id');

        $('#hidden-criteria').val(cardId);
        // $('#EditDesicion-Modal').find('input#Input-Edit-DecisionTitle').val(decisionTitle);
        $('#EditCriteria-Modal').modal('show');
    });
    $(document).ready(function() {
        const criteriaItems = document.querySelectorAll('.criteria-item');

        // Find the maximum height of the elements
        let maxHeight = 0;
        criteriaItems.forEach(item => {
            const itemHeight = item.offsetHeight;
            if (itemHeight > maxHeight) {
                maxHeight = itemHeight;
            }
        });

        // Apply the maximum height to all elements
        criteriaItems.forEach(item => {
            item.style.height = maxHeight + 'px';
        });

        $('.importance-criteria-select').change(function() {
            var criteriaId = $(this).attr("data-id");
            var importance_level = $(this).val();
            $.ajax({
                url: '../controller/update_importance.php',
                method: 'POST',
                data: {
                    criteria_id: criteriaId,
                    importance_level: importance_level
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    console.log(response);
                    alert(res.status);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

    });
</script>

</html>