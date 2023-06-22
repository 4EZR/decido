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
      <div class='m-0 p-0  '>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item text-decoration-none text-white "><a class='text-decoration-none ' href="../../index.php">Home</a></li>
            <li class="breadcrumb-item text-decoration-none text-white"><a class='text-decoration-none' href="decision_detail_view.php?id=<?= $cardID ?>">Decision Detail</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Alternative</li>
          </ol>
        </nav>
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
  <script src="https://cdn.jsdelivr.net/npm/@mojs/core"></script>
</body>

<div class="modal fade" id="AddAlternative-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Alternative</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../controller/add_alternative_controller.php" id='form-add-alternative' method="POST">
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
<div class="modal fade" id="EditAlternative-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Alternative</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../controller/add_alternative_controller.php" id='form-edit-alternative' method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Input Alternative</label>
            <input type="text" name="Alternative_Title" class="form-control" id="AlternativeTitleEdit" aria-describedby="emailHelp">
            <input type="hidden" name='Alternative_ID' id='hidden_Alternativ_ID' class="form-control">
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
  function showNotification(e, t) {
    new Noty({
      type: e,
      layout: "topRight",
      text: t,
      timeout: 2e3,
      animation: {
        open: function(e) {
          var t = this,
            i = new mojs.Timeline,
            o = new mojs.Html({
              el: t.barDom,
              x: {
                500: 0,
                delay: 0,
                duration: 500,
                easing: "elastic.out"
              },
              isForce3d: !0,
              onComplete: function() {
                e((function(e) {
                  e()
                }))
              }
            }),
            n = new mojs.Shape({
              parent: t.barDom,
              width: 200,
              height: t.barDom.getBoundingClientRect().height,
              radius: 0,
              x: {
                150: -150
              },
              duration: 600,
              isShowStart: !0
            });
          t.barDom.style.overflow = "visible", n.el.style.overflow = "hidden";
          var a = new mojs.Burst({
              parent: n.el,
              count: 10,
              top: t.barDom.getBoundingClientRect().height + 75,
              degree: 90,
              radius: 75,
              angle: {
                [-90]: 40
              },
              children: {
                fill: "#EBD761",
                delay: "stagger(500, -50)",
                radius: "rand(8, 25)",
                direction: -1,
                isSwirl: !0
              }
            }),
            l = new mojs.Burst({
              parent: n.el,
              count: 2,
              degree: 0,
              angle: 75,
              radius: {
                0: 100
              },
              top: "90%",
              children: {
                fill: "#EBD761",
                pathScale: [.65, 1],
                radius: "rand(12, 15)",
                direction: [-1, 1],
                delay: 400,
                isSwirl: !0
              }
            });
          i.add(o, a, l, n), i.play()
        },
        close: function(e) {
          new mojs.Html({
            el: this.barDom,
            x: {
              0: 500,
              delay: 10,
              duration: 500,
              easing: "cubic.out"
            },
            skewY: {
              0: 10,
              delay: 10,
              duration: 500,
              easing: "cubic.out"
            },
            isForce3d: !0,
            onComplete: function() {
              e((function(e) {
                e()
              }))
            }
          }).play()
        }
      }
    }).show()
  }

  function createDropdown(alternativeId) {
    return `<div class='btn-group dropstart'>
    <button type="button" class="btn bg-brown p-0 m-0 rounded-1 menu-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded h3 m-0 p-0"></i>
          </button>
          <ul class="dropdown-menu m-0 p-0">
            <li class="m-0 p-0"><a class="dropdown-item edit-btn fw-bold p-2" data-id="${alternativeId}" href="#">Edit Alternative</a></li>
            <li><hr class="dropdown-divider m-0 p-0"></li>
            <li class="m-0 p-0"><a class="dropdown-item del-btn fw-bold p-2 text-danger" data-id="${alternativeId}" href="#"><i class="bx bxs-trash-alt"></i> Delete Alternative</a></li>
          </ul></div>`;
  }

  function refreshTableData(decisionID) {

    $.ajax({
      url: '../controller/front_Controller_Alternative.php?action=get_alternative_data',
      type: 'POST',
      data: {
        decision_id: decisionID
      },
      dataType: 'json',
      success: function(data) {
        var criteria = data[0].criteria;
        $('#alternatives-table tbody').empty();
        $('.append-th').empty();
        var $colspanTh = $('th[colspan="2"]');
        var tableHeaders = '<th>Alternative Title</th>';

        for (var i = 0; i < criteria.length; i++) {
          tableHeaders += '<th>' + criteria[i].title + '</th>';
        }
        tableHeaders += '<th></th>';

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

            $.ajax({
              url: '../controller/front_Controller_Alternative.php?action=checkAlternative',
              method: 'POST',
              data: {
                Alternative_ID: alternative.id,
                Criteria_ID: criterion.id
              },
              dataType: 'json',
              success: function(response) {
                // Set the selected option based on the weight value
                if (response.status === 'success') {
                  select.val(response.weight);
                }

              },
              error: function(xhr, status, error) {
                // Handle the error
              }
            });
            criterionCell.append(select);
            row.append(criterionCell);
          });
          var dropdownCell = $('<td class="text-end"></td>').html(createDropdown(alternative.id));
          row.append(dropdownCell);
          $('#alternatives-table tbody').append(row);
        });

      },
      error: function(xhr, status, error) {}
    });

  }
  $(document).on('click', '.edit-btn', function(event) {
    var cardId = $(this).attr('data-id');
    var currentRow = $(this).closest("tr");

    // Find the title cell and get its text
    var cardTitle = currentRow.find("td:first").text();
    $('#hidden_Alternativ_ID').val(cardId);
    $('#AlternativeTitleEdit').val(cardTitle);

    $('#EditAlternative-Modal').modal('show');
  });
  $(document).ready(function() {

    var decisionID = <?= $cardID; ?>;
    refreshTableData(decisionID)
    $("#form-add-alternative").submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "../controller/front_Controller_Alternative.php?action=add_alternative",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
          var result = JSON.parse(response);
          if (result.success) {
            showNotification("warning", "add Alternative Success");
            $('#AddAlternative-Modal').modal('hide');
            $("#form-add-alternative").trigger("reset");
            refreshTableData(decisionID)
          }
        },
      });
    });

    $("#form-edit-alternative").submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "../controller/front_Controller_Alternative.php?action=edit_alternative",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
          var result = JSON.parse(response);
          if (result.success) {
            showNotification("warning", "edit Alternative Success");
        
            $('#EditAlternative-Modal').modal('hide');
            $("#edit-form-criteria").trigger("reset");
           
            refreshTableData(decisionID)
          }
        },
      });
    });
    $(document).on("click", ".del-btn", function(event) {
      var alternativeId = $(this).attr("data-id");

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this Alternative!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, keep it",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "../controller/front_Controller_Alternative.php?action=delete_alternative",
            method: "POST",
            data: {
              Alternative_ID: alternativeId
            },
            success: function(response) {
              var result = JSON.parse(response);
              if (result.success) {
                // Show Noty notification for success
                showNotification("error", "Delete Alternative Success");
                refreshTableData(decisionID)

              }
            },
          });
        } else {
          // Show Noty notification for cancel
          showNotification("error", "Delete Alternative Success");
        }
      });
    });

  });
  
  $(document).on('change', '.update-weight', function() {
    var $select = $(this);
    var alternativeId = $select.data('alternative-id');
    var criteriaId = $select.data('criteria-id');
    var weight = $select.val();

    $.ajax({
      url: '../controller/front_Controller_Alternative.php?action=checkAlternative',
      method: 'POST',
      data: {
        Criteria_ID: criteriaId,
        Alternative_ID: alternativeId
      },
      dataType: 'json',
      success: function(response) {
        console.log(response);
        if (response.status == 'success') {
          // Run update function
          $.ajax({
            url: '../controller/front_Controller_Alternative.php?action=update_weight',
            method: 'POST',
            data: {
              Criteria_ID: criteriaId,
              Alternative_ID: alternativeId,
              weight: weight
            },
            dataType: 'json',
            success: function(response) {
              console.log(response);
              alert('success');
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
          });
        } else {
          // Run insert function
          $.ajax({
            url: '../controller/front_Controller_Alternative.php?action=add_weight',
            method: 'POST',
            data: {
              Criteria_ID: criteriaId,
              Alternative_ID: alternativeId,
              weight: weight
            },
            dataType: 'json',
            success: function(response) {
              console.log(response);
              alert('success');
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  }).trigger('change');
</script>

</html>