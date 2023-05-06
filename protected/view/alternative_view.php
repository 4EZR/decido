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
  </div>
</section>
</body>

</html>