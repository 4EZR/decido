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
                    <li class="breadcrumb-item active text-white" aria-current="page">Criteria</li>
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
                    echo "<div class='criteria-item  flex-fill me-5 mb-5' data-id='" . $criteria['criteria_ID'] . "'>";
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
                                <li class="m-0 p-0"><a class="dropdown-item del-btn fw-bold p-2 text-danger" data-id="' . $criteria['criteria_ID'] . '" href="#"><i class="bx bxs-trash-alt"></i> Delete Decision</a></li>
                            </ul>
                        </div>';
                    echo '</div>';
                    echo  '<p class="p-0 mt-2  mb-0 lh-0 pb-0 fs-3 heading criteria-title">' . $criteria['Criteria_Title'] . '</p>';
                    echo "<p class='text-secondary fw-normal mt-0 mb-0 mb-3 p-0 criteria-type'><small>" . ($criteria['Criteria_Type'] == 1 ? "Benefit Type" : "Cost Type") . "</small></p>";
                    echo "<select class='form-select-sm form-select importance-criteria-select border border-primary border-3' data-id='" . $criteria['criteria_ID'] . "'>";

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
    <script src="https://cdn.jsdelivr.net/npm/@mojs/core"></script>
</body>
<div class="modal fade" id="AddCriteria-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Criteria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../controller/add_criteria_controller.php" id="add-form-criteria" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
                        <input type="text" name="title" class="form-control" id="#input-desicion-title" aria-describedby="emailHelp" required>
                        <input type="hidden" name='decision_id' class="form-control" value="<?= $cardID ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
                        <select class="form-select" name='type' aria-label="Default select example" required>
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
            <form action="../controller/add_criteria_controller.php" id="edit-form-criteria" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Criteria</label>
                        <input type="text" name="title" class="form-control" id="edit-criteria-title" aria-describedby="emailHelp" required>

                        <input type="hidden" name='criteria_id' class="form-control" id="hidden-criteria">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Input Criteria Type</label>
                        <select class="form-select" id='edit-type-select' name='type' aria-label="Default select example" required>
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
    function showNotification(e,t){new Noty({type:e,layout:"topRight",text:t,timeout:2e3,animation:{open:function(e){var t=this,i=new mojs.Timeline,o=new mojs.Html({el:t.barDom,x:{500:0,delay:0,duration:500,easing:"elastic.out"},isForce3d:!0,onComplete:function(){e((function(e){e()}))}}),n=new mojs.Shape({parent:t.barDom,width:200,height:t.barDom.getBoundingClientRect().height,radius:0,x:{150:-150},duration:600,isShowStart:!0});t.barDom.style.overflow="visible",n.el.style.overflow="hidden";var a=new mojs.Burst({parent:n.el,count:10,top:t.barDom.getBoundingClientRect().height+75,degree:90,radius:75,angle:{[-90]:40},children:{fill:"#EBD761",delay:"stagger(500, -50)",radius:"rand(8, 25)",direction:-1,isSwirl:!0}}),l=new mojs.Burst({parent:n.el,count:2,degree:0,angle:75,radius:{0:100},top:"90%",children:{fill:"#EBD761",pathScale:[.65,1],radius:"rand(12, 15)",direction:[-1,1],delay:400,isSwirl:!0}});i.add(o,a,l,n),i.play()},close:function(e){new mojs.Html({el:this.barDom,x:{0:500,delay:10,duration:500,easing:"cubic.out"},skewY:{0:10,delay:10,duration:500,easing:"cubic.out"},isForce3d:!0,onComplete:function(){e((function(e){e()}))}}).play()}}}).show()}

    function refreshCriteriaList(num) {
        $.ajax({
            url: "../controller/front_Controller_Criteria.php?action=getCriteria",
            method: "POST",
            data: {
                decision_id: <?= $cardID; ?>,

            },
            success: function(response) {
                var criterias = JSON.parse(response);

                var criteriaHtml = generateCriteriaHtml(criterias);

                $('#criteria-list').html(criteriaHtml);

                $('#criteria-list').hide().fadeIn(400);;
            },
        });
    }

    function generateCriteriaHtml(criterias) {
        var importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
        var counter = 0;
        var criteriaHtml = '';

        criterias.forEach(function(criteria) {
            counter++;
            criteriaHtml += "<div class='criteria-item me-5 mb-5 flex-fill' data-id='" + criteria['criteria_ID'] + "'>";
            criteriaHtml += '<div class="card p-3 bg-brown border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' + criteria['criteria_ID'] + '">';

            criteriaHtml += '<div class="d-flex"></div>';
            criteriaHtml += '<div class="d-flex w-100 justify-content-between align-items-center">';

            criteriaHtml += '<p class="p-0 m-0 fs-1 text-orange heading">C' + counter + '</p>';
            criteriaHtml += '<div class="btn-group dropstart">';
            criteriaHtml += '<button type="button" class="btn bg-brown p-0 m-0 rounded-1 menu-dropdown" data-bs-toggle="dropdown" aria-expanded="false">';
            criteriaHtml += '<i class="bx bx-dots-vertical-rounded h3 m-0 p-0"></i>';
            criteriaHtml += '</button>';
            criteriaHtml += '<ul class="dropdown-menu m-0 p-0">';
            criteriaHtml += '<li class="m-0 p-0"><a class="dropdown-item edit-btn fw-bold p-2" data-id="' + criteria['criteria_ID'] + '" href="#">Edit Decision</a></li>';
            criteriaHtml += '<li><hr class="dropdown-divider m-0 p-0"></li>';
            criteriaHtml += '<li class="m-0 p-0"><a class="dropdown-item del-btn fw-bold p-2 text-danger" data-id="' + criteria['criteria_ID'] + '" href="#"><i class="bx bxs-trash-alt"></i> Delete Decision</a></li>';
            criteriaHtml += '</ul>';
            criteriaHtml += '</div>';
            criteriaHtml += '</div>';
            criteriaHtml += '<p class="p-0 mt-2 mb-0 lh-0 pb-0 fs-3 heading criteria-title">' + criteria['Criteria_Title'] + '</p>';
            criteriaHtml += "<p class='text-secondary fw-normal mt-0 mb-0 mb-3 p-0 criteria-type' data-type='" + criteria['type'] + "'><small>" + (criteria['Criteria_Type'] == 1 ? "Benefit Type" : "Cost Type") + "</small></p>";
            criteriaHtml += "<select class='form-select-sm form-select importance-criteria-select border border-primary border-3' data-id='" + criteria['criteria_ID'] + "'>";

            criteriaHtml += "<option value='0'>Please select one</option>";
            importanceLevels.forEach(function(level, index) {
                var optionValue = index + 1;
                var selected = (criteria['Criteria_Importance'] == optionValue) ? 'selected' : '';
                criteriaHtml += "<option value='" + optionValue + "' " + selected + ">" + level + "</option>";
            });

            criteriaHtml += "</select>";
            criteriaHtml += '</div>';
            criteriaHtml += '</div>';
        });

        return criteriaHtml;
    }
    $(document).on('click', '.edit-btn', function(event) {
        var cardId = $(this).attr('data-id');
        var cardTitle = $(this).closest('.criteria-item').find('.criteria-title').text();
        var cardType = $(this).closest('.criteria-item').find('.criteria-type').text() === "Benefit Type" ? 0 : 1;

        $('#hidden-criteria').val(cardId);
        $('#edit-criteria-title').val(cardTitle);
        $('#edit-type-select').val(cardType);
        $('#EditCriteria-Modal').modal('show');
    });
    $(document).ready(function() {


        $('.importance-criteria-select').change(function() {
            var criteriaId = $(this).attr("data-id");
            var importance_level = $(this).val();
            $.ajax({
                url: "../controller/front_Controller_Criteria.php?action=updateWeight",
                method: 'POST',
                data: {
                    criteria_id: criteriaId,
                    importance_level: importance_level
                },
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.success) {
                        showNotification("warning", "Weight Criteria Success");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
        $("#add-form-criteria").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "../controller/front_Controller_Criteria.php?action=addCriteria",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        // Show Noty notification for success
                        showNotification("warning", "add Criteria Success");
                        // Close the modal and reset the form
                        $('#AddCriteria-Modal').modal('hide');
                        $("#add-form-criteria").trigger("reset");
                        refreshCriteriaList();

                    }
                },
            });
        });

        // Edit decision form submission
        $("#edit-form-criteria").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "../controller/front_Controller_Criteria.php?action=editCriteria",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        // Show Noty notification for success
                        showNotification("warning", "edit Decision Success");

                        // Close the modal and reset the form
                        $('#EditCriteria-Modal').modal('hide');
                   
                        $("#edit-form-criteria").trigger("reset");
                        refreshCriteriaList();
                    }
                },
            });
        });
        $(document).on("click", ".del-btn", function(event) {
            var criteriaId = $(this).attr("data-id");

            Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Criteria!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, keep it",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "../controller/front_Controller_Criteria.php?action=deleteCriteria",
                        method: "POST",
                        data: {
                            id: criteriaId
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.success) {
                                // Show Noty notification for success
                                showNotification("error", "Delete Decision Success");
                                refreshCriteriaList();
                            }
                        },
                    });
                } else {
                    // Show Noty notification for cancel
                    showNotification("error", "Delete Decision Success");
                }
            });
        });

    });
</script>

</html>