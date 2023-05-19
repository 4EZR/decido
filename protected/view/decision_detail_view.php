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

<body class="bg-dgray">

  <?php
  $cardID = $_GET['id'];

  require_once '../../conn.php';
  require_once '../model/desicion_model.php';

  $decisionModel = new Decision_Model($pdo);
  $decision = $decisionModel->get_decision_byID($cardID);
  require_once('navbar.php');
  ?>

  <section id="content-2" class="py-4 ">
    <div class="container-xxl">
      <p class="h2 fw-bold heading text-white">Wellcome to your <span class='fw-bold text-orange'>decision</span></p>
      <span class='badge bg-ddgray p-2 shadow-sm my-3'><small><p class='text-orange text-start m-0 p-0 fs-6'><i class='bx bxs-label me-1'></i>title</p></small><p class="text-white h4 text-start fw-bold m-0 p-0"><?php echo $decision['decision_Title'] ?></p></span>

      <div class="row d-flex justify-content-between my-3">

        <div class="col-md-6">
          <div class="card criteria-card pattern-zigzag-lg text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
             <div class='d-flex justify-content-start text-start'>
               <p class="m-0 p-0 fw-bold heading h3 text-start text-dark"><i class='bx bx-search-alt bx-tada'></i></p>
               <p class="m-0 p-0 fw-bold heading h4 text-start text-dark">criteria</p>
             </div>
            <div class="note-div p-3 bg-white rounded shadow-sm">
              <small><p class='text-muted m-0 p-0'>note</p></small>
              <p class='opacity-100 m-0 p-0 text-dark'>Add,Edit,Delete And Weight Criteria</p>
              <p class='p-0 m-0 text-orange h4 icon-note'><i class='bx bxs-right-arrow-circle'></i></p>
            </div>

            <span class="position-absolute start-25 top-0  bg-primary fw-bold p-2 rounded text-white translate-middle">Step 1</span>
          </div>
        </div>

        <div class="col-md-6 my-md-0 my-5">
          <div class="card alternative-card pattern-horizontal-stripes-lg text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
             <div class='d-flex justify-content-start text-start'>
               <p class="m-0 p-0 fw-bold heading h3 text-start text-dark"><i class='bx bx-pointer bx-tada'></i></p>
               <p class="m-0 p-0 fw-bold heading h4 text-start text-dark">Alternative</p>
             </div>
            <div class="note-div p-3 bg-white rounded shadow-sm">
              <small><p class='text-muted m-0 p-0'>note</p></small>
              <p class='opacity-100 m-0 p-0 text-dark'>Add,Edit,Delete And Weight Alternative</p>
              <p class='p-0 m-0 text-orange h4 icon-note'><i class='bx bxs-right-arrow-circle'></i></p>
            </div>

            <span class="position-absolute start-25 top-0  bg-primary fw-bold p-2 rounded text-white translate-middle">Step 2</span>
          </div>
        </div>
      

      </div>

      <div class="row d-flex justify-content-between my-md-5 my-0">

      <div class="col-md-6">
          <div class="card trade-card pattern-grid-md text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
             <div class='d-flex justify-content-start text-start'>
               <p class="m-0 p-0 fw-bold heading h3 text-start text-dark"><i class='bx bx-pointer bx-tada'></i></p>
               <p class="m-0 p-0 fw-bold heading h4 text-start text-dark">Trade Off</p>
             </div>
            <div class="note-div p-3 bg-white rounded shadow-sm">
              <small><p class='text-muted m-0 p-0'>note</p></small>
              <p class='opacity-100 m-0 p-0 text-dark'>Perform Sensitive Analysis</p>
              <p class='p-0 m-0 text-orange h4 icon-note'><i class='bx bxs-right-arrow-circle'></i></p>
            </div>

            <span class="position-absolute start-25 top-0  bg-primary fw-bold p-2 rounded text-white translate-middle">Step 3</span>
          </div>
        </div>
       

        <div class="col-md-6 my-5 my-md-0">
          <div class="card result-card pattern-triangles-lg text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
             <div class='d-flex justify-content-start text-start'>
               <p class="m-0 p-0 fw-bold heading h3 text-start text-dark"><i class='bx bx-line-chart bx-tada'></i></p>
               <p class="m-0 p-0 fw-bold heading h4 text-start text-dark">Result</p>
             </div>
            <div class="note-div p-3 bg-white rounded shadow-sm">
              <small><p class='text-muted m-0 p-0'>note</p></small>
              <p class='opacity-100 m-0 p-0 text-dark'>Final Decision</p>
              <p class='p-0 m-0 text-orange h4 icon-note'><i class='bx bxs-right-arrow-circle'></i></p>
            </div>

            <span class="position-absolute start-25 top-0  bg-primary fw-bold p-2 rounded text-white translate-middle">Step 4</span>
          </div>
        </div>
      </div>

    </div>
  </section>
</body>
<script>
  $('.criteria-card').on('click', function() {

    var cardId = <?= $cardID; ?>;
    var url = 'criteria_view.php?id=' + cardId;
    window.location.href = url;
  });

  $('.alternative-card').on('click', function() {

    var cardId = <?= $cardID; ?>;
    var url = 'alternative_view.php?id=' + cardId;
    window.location.href = url;
  });

  $('.trade-card').on('click', function() {

    var cardId = <?= $cardID; ?>;
    var url = 'tradeoff_view.php?id=' + cardId;
    window.location.href = url;
  });


  $('.alternative-card').on('click', function() {

    var cardId = <?= $cardID; ?>;
    var url = 'alternative_view.php?id=' + cardId;
    window.location.href = url;
  });

  $('.result-card').on('click', function() {

    var cardId = <?= $cardID; ?>;
    var url = 'result_view.php?id=' + cardId;
    window.location.href = url;
  });
</script>

</html>