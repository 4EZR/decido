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


  <section id="content-2" class="container-criteria bg-brown py-4 ">
    <div class="container-xxl">
      <div class='m-0 p-0 sticky-top '>
        <p class="h2 fw-bold heading text-main">Alternative</p>
        <p class="h5 text-main fw-normal"><?= $decision['decision_Title']; ?></p>
        <button class="add-alternative btn btn-warning" data-bs-toggle="modal" data-bs-target="#AddAlternative-Modal"> <i class='bx bx-plus me-1'></i> New Alternative</button>
      </div>
    </div>
  </section>

  <section>
    <div class="container-xxl py-5">
      <p class="text-secondary">List of Alternative</p>

      <table id="alternatives-table" class="table align-middle table-hover">
        <thead>
          <tr>
            <th scope='col' class='border-0'></th>
            <!-- colspan based ontotal criteria -->
            <th colspan="2" class='align-middle text-center border-bottom border-0'>Criteria</th>

          </tr>
        </thead>
        <thead class='table-light border-0'>
          <tr class='append-th'>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div>
  </section>

</body>

<div class="modal fade" id="AddAlternative-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Criteria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../controller/add_alternative_controller.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Input Alternative</label>
            <input type="text" name="title" class="form-control" id="#input-desicion-title" aria-describedby="emailHelp">
            <input type="hidden" name='decisionID' class="form-control" value="<?= $cardID ?>">
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
    // Fetch the data from your PHP script

    var decisionID = <?= $cardID; ?>;
    $.ajax({
      url: '../controller/alternative_data_controller.php',
      type: 'POST',
      data: {
        decision_id: decisionID
      },
      dataType: 'json',
      success: function(data) {
        var criteria = data[0].criteria;

        var $colspanTh = $('th[colspan="2"]');
        var tableHeaders = '<th>Alternative Title</th>';

        for (var i = 0; i < criteria.length; i++) {
          tableHeaders += '<th>' + criteria[i].title + '</th>';
        }
        $colspanTh.attr('colspan', criteria.length);
        $('.append-th').append(tableHeaders);

        $.each(data, function(i, alternative) {
          var row = $('<tr></tr>');
          var titleCell = $('<td></td>').text(alternative.title);
          row.append(titleCell);
          $.each(alternative.criteria, function(j, criterion) {

            var criterionCell = $('<td></td>');
            var select = $('<select class="update-weight form-select"></select>');
            select.attr('data-criteria-id', criterion.id);
            select.attr('data-alternative-id', alternative.id);
            select.append($('<option value="">Please select...</option>'));
            $.each(criterion.terms, function(k, term) {
              var option = $('<option></option>').text(term.txt).attr('value', term.id);
              select.append(option);
            });
            criterionCell.append(select);
            row.append(criterionCell);
          });
          $('#alternatives-table tbody').append(row);
        });

      },
      error: function(xhr, status, error) {}
    });

  });
  $(document).on('change', '.update-weight', function() {
    var $select = $(this);
    var alternativeId = $select.data('alternative-id');
    var criteriaId = $select.data('criteria-id');
    var termId = $select.val();


   

    console.log('Alternative ID:', alternativeId, 'Criteria ID:', criteriaId, 'Term ID:', termId);
   
  });

  $('')
</script>

</html>