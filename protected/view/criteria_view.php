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
    $importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
    require_once('navbar.php');
    ?>

    <section id="content-2" class="container-criteria bg-dbrown py-4 ">
        <div class="container-xxl">
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
            <button class='sort-criteria-btn btn btn-primary'><i class='bx bx-sort'></i>Sort</button>

            <div id='criteria-list' class="d-flex">



                <?php
                foreach ($criterias as $criteria) {

                    echo "<div class='criteria-item  m-3' data-id='" . $criteria['criteria_ID'] . "'>";
                    echo '<div class="card p-3 bg-yellow  border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $criteria['criteria_ID'] . '">';

                    echo '<div class="d-flex">';
                    //echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-primary text-white fw-normal">' . $decision['decision_Title'] . '</span>';

                    echo '</div>';
                    echo  '<p class="p-0 mt-2 fs-3">' . $criteria['Criteria_Title'] . '</p>';

                    echo "<p class='text-secondary fw-normal  mb-3 p-0'><small> " . $criteria['TermLevel_1'] . ", " . $criteria['TermLevel_2'] . ", " . $criteria['TermLevel_3'] . ", " . $criteria['TermLevel_4'] . ", " . $criteria['TermLevel_5'] .  "</small></p>";
                    echo "<select class='form-select-sm form-select importance-criteria-select' data-id='" . $criteria['criteria_ID'] . "'>";
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

            <button id='save-criteria-btn' class='btn btn-primary'>Save</button>


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
                        <select class="form-select" name='term' aria-label="Default select example">
                            <?php
                            foreach ($terms as $term) {
                                echo "<option value='" . $term['Term_ID'] . "'>" . $term['TermLevel_1'] . ", " . $term['TermLevel_2'] . ", " . $term['TermLevel_3'] . ", " . $term['TermLevel_4'] . ", " . $term['TermLevel_5'] . "</option>";
                            }
                            ?>
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
    $(document).ready(function() {

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
        $('#save-criteria-btn').hide();
    //     $('.sort-criteria-btn').click(function() {
    //         $('.sort-criteria-btn').prop('disabled', true);
    //         $('#save-criteria-btn').show();
    //         var importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
    //         var levelsContainer = $('<div>').addClass('d-flex ').attr('id', 'levels-container').hide();
    //         importanceLevels.forEach(function(level, index) {
    //             var levelDiv = $('<div>').addClass('m-3 level-div p-5 bg-light rounded-3 w-100').attr('data-level', index + 1).html('<p class="text-muted fw-bold heading-drag">' + level + '</p>');
    //             levelsContainer.append(levelDiv);
    //         });
    //         $('#criteria-list').after(levelsContainer);

    //         // ...
    //         levelsContainer.show();

    //         // Add dragula to the level divs as containers
    //         var levelDivs = Array.from(document.getElementsByClassName('level-div'));
    //         var drake = dragula([document.getElementById('criteria-list'), ...levelDivs], {
    //             moves: function(el, container, handle) {
    //                 return $(handle).closest('.criteria-item').length > 0;
    //             }
    //         });

    //         // ...

    //         // Modify the dragula event to store the updated importance level
    //         drake.on('drag', function(el, source) {
    //             el.style.zIndex = 1000;
    //         });
    //         drake.on('dragend', function(el) {
    //             el.style.zIndex = '';
    //             var container = el.parentElement;
    //             var heading = container.querySelector('.heading-drag');
    //             if (heading) {
    //                 container.insertBefore(heading, container.firstChild);
    //             }
    //             var newImportanceLevel = heading.dataset.level;
    //             $(el).find('.criteria-item').data('new-importance-level', newImportanceLevel);

    //         });
    //         $('#save-criteria-btn').click(function() {

    //             $('.level-div').each(function() {
    //                 $(this).find('.criteria-item').appendTo('#criteria-list');
    //             });

    //             $('#levels-container').remove(); // Remove the #levels-container div
    //             $('#criteria-list').show(); // Show the criteria list

    //             levelsContainer.hide();
    //             drake.destroy();

    //             // Call Ajax for each criteria with updated importance level
    //             $('#save-criteria-btn').on('click', function() {
    //                 $('.criteria-item').each(function() {
    //                     var criteriaId = $(this).data('id');
    //                     var newImportanceLevel = $(this).data('new-importance-level');

    //                     $.ajax({
    //                         url: '../controller/update_importance.php',
    //                         method: 'POST',
    //                         data: {
    //                             criteria_id: criteriaId,
    //                             importance_level: 5
    //                         },
    //                         success: function(response) {
    //                             console.log(response);
    //                         },
    //                         error: function(jqXHR, textStatus, errorThrown) {
    //                             console.log(textStatus, errorThrown);
    //                         }
    //                     });

    //                 });
    //             });
    //             $('#save-criteria-btn').hide();
    //             $('.sort-criteria-btn').prop('disabled', false);
    //         });
    //     });
     });
</script>

</html>