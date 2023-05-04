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
    <p class="h2 fw-bold heading text-main">Wellcome to your decision</p>
    <p class="text-secondary"><?php echo $decision['decision_Title']?></p>
  
    <div class="row d-flex justify-content-between my-3">

    <div class="col-md-6">
        <div class="card criteria-card shadow-sm me-2 p-2 border-0 bg-yellow text-center d-flex justify-content-center align-items-center">
        <p class="m-0 p-0 heading fw-bold h2"><i class='bx bx-search-alt bx-tada' ></i></p> 
        <p class="m-0 p-0 heading fw-bold h2">criteria</p>
      </div>
    </div>
      <div class="col-md-6">
          <div class="card shadow-sm me-2 p-2 border-0 bg-yellow text-center d-flex justify-content-center align-items-center">

          <p class="m-0 p-0 heading fw-bold h2"><i class='bx bx-pointer bx-tada' ></i></p>
            <p class="m-0 p-0 heading fw-bold h2">Alternative</p>
          </div>
      </div>
     
    </div>

    <div class="row d-flex justify-content-between my-3">
      <div class="col-md-6">
          <div class="card shadow-sm me-2 p-2 border-0 bg-yellow text-center d-flex justify-content-center align-items-center">
            <p class="m-0 p-0 heading fw-bold h2"><i class='bx bx-move-horizontal bx-tada bx-flip-vertical' ></i></p>
            <p class="m-0 p-0 heading fw-bold h2">Trade Off</p>
          </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm me-2 p-2 border-0 bg-yellow text-center d-flex justify-content-center align-items-center">
        <p class="m-0 p-0 heading fw-bold h2"><i class='bx bx-line-chart bx-tada' ></i></p>
          <p class="m-0 p-0 heading fw-bold h2">Result</p>
        </div>
    </div>
    </div>

  </div>
</section>
</body>
<script>
   $('.criteria-card').on('click', function() {
    // Construct the URL with the ID as a parameter

    var cardId = <?= $cardID; ?>;
    var url = 'criteria_view.php?id=' + cardId;

    // Redirect to the next page
    window.location.href = url;
  });
</script>

</html>