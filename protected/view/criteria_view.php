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

    require_once '../model/linguistic_terms_model.php';


    $termModel = new Term_Model($pdo);

    $decisionModel = new Decision_Model($pdo);
    $decision = $decisionModel->get_decision_byID($cardID);

    $terms = $termModel->get_linguistic_term();
    require_once('navbar.php');
    ?>
   
    <section id="content-2" class="container-criteria bg-dbrown py-4 ">
        <div class="container-xxl">
            <div class='m-0 p-0 sticky-top '>
                <p class="h2 fw-bold heading text-white">Criteria</p>
                <button class="add-criteria btn btn-warning"> <i class='bx bx-plus me-1'></i> New Criteria</button>
             
            </div>

           


        </div>
    </section>
    <section>
        <div class="container-xxl">
        <div class='append-box'>


</div>
        </div>
    </section>
  
</body>

<script>
    const html = `
            <div class="form my-2">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Criteria Name</label>
                </div>
                <div class="col-auto">
                <input type="text" id="inputPassword6" class="form-control" aria-labelledby="passwordHelpInline">
                </div>
                <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Linguistic Term</label>
                </div>
                <div class="col-auto">
                <select class="form-select" aria-label="Default select example">
                    <?php
                    foreach ($terms as $term) {
                        echo "<option value='" . $term['Term_ID'] . "'>" . $term['TermLevel_1'] . ", " . $term['TermLevel_2'] . ", " . $term['TermLevel_3'] . ", " . $term['TermLevel_4'] . ", " . $term['TermLevel_5'] . "</option>";
                    }
                    ?>
                </select>
                </div>
                <div class="col-auto">
                <button class='submit-criteria btn btn-primary'>Add</button>
                </div>
            </div>
            </div>
        `;


    $('.add-criteria').click(function() {
        $('.append-box').append(html);
        $('.add-criteria').prop('disabled', true);

    });

    $(document).on('click', '.submit-criteria', function() {
        $('.append-box').append(html);
        $('.add-criteria').prop('disabled', false);
    });
</script>

</html>