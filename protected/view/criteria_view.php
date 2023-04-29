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

    $decisionModel = new Decision_Model($pdo);
    $decision = $decisionModel->get_decision_byID($cardID);
    require_once('navbar.php');
    ?>
    </section>
    <section id="content-2" class=" bg-white py-4 ">
        <div class="container-xxl">
            <div class='m-0 p-0 sticky-top'>

            
            <p class="h2 fw-bold heading text-main">Criteria</p>
            <button class="add-criteria btn btn-primary"> <i class='bx bx-plus me-1'></i> New Criteria</button>
            <hr>
            </div>
            <div class='append-box'>

            <div class=''>


            </div>

            </div>


        </div>
    </section>
</body>

<script>
    $(document).ready(function() {

        $('.add-criteria').click(function() {

           $('.append-box').append('<div class="row mb-3"><div class="col-md-3"><input type="text" class="form-control" placeholder="Criteria Name"></div><div class="col-md-3"><input type="text" class="form-control" placeholder="Criteria Weight"></div><div>');

        });



    });
</script>

</html>