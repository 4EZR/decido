<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
  require_once('js/library.php');


  ?>
  <link rel="stylesheet" href="styles/style.css" />
  <title>Decido</title>
</head>

<body class=" ">

  <?php
  require_once 'conn.php';
  require_once 'protected/model/desicion_model.php';

  $decisionModel = new Decision_Model($pdo);
  $decisions = $decisionModel->get_decisions();

  require_once 'protected/view/navbar.php';
  ?>

  <section id="content-1" class="bg-brown py-4">
    <div class="container-xxl py-5 text-center">
      <p class="display-5 fw-bold heading text-main">Home</p>
      <p class="text-secondary fw-normal">Decido Your Ultimate Decision Companion</p>

      <button class="btn   btn-primary shadow-sm text-white p-2 rounded-pill mb-2 " data-bs-toggle="modal" data-bs-target="#AddDesicion-Modal">add new desicion</button>

    </div>
  </section>
  <section id="content-2" class=" bg-white py-4 ">
    <div class="container-xxl">
      <p class="h2 fw-bold heading text-main">My Decisions</p>
      <p class="text-secondary">Overview of my desicions</p>

      <div class="row">



        <?php
        foreach ($decisions as $decision) {

          echo "<div class='col-md-3 my-3'>";
          echo '<div class="card p-2 bg-yellow decision-card border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $decision['decision_ID'] . '">';

          echo '<div class="d-flex">';
          echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-primary text-white fw-normal">decision</span>';

          if ($decision['decision_Status'] == '0') {
            echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-orange border-0  mx-2 text-white fw-normal">incomplete</span>';
          }
          echo '</div>';
          echo  '<p class="p-0 mt-2 fs-3">' . $decision['decision_Title'] . '</p>';

          echo '<p class="text-secondary fw-normal  mb-3 p-0"><small>' . $decision['decision_Date'] . '</small></p>';
          echo '</div>';
          echo '</div>';
        }

        ?>

      </div>

    </div>
  </section>
</body>

<script>
  $('.decision-card').on('click', function() {
    // Construct the URL with the ID as a parameter

    var cardId = $(this).attr('data-id');
    var url = 'protected/view/decision_detail_view.php?id=' + cardId;

    // Redirect to the next page
    window.location.href = url;
  });
</script>

<div class="modal fade" id="AddDesicion-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Desicion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="protected/controller/add_desicion_controller.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
            <input type="text" name="title" class="form-control" id="#input-desicion-title" aria-describedby="emailHelp">
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

</html>