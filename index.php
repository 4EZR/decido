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
      <div class='d-flex justify-content-between align-items-center'>
        <div>
          <p class="h2 fw-bold heading text-main">My Decisions</p>
          <p class="text-secondary">Overview of my desicions</p>
        </div>
        <div>
          <input class="form-control me-2" type="search" placeholder="Search" id='searchBar' aria-label="Search">
        </div>
      </div>

      <div id='search_results' class="row">
        <?php
        foreach ($decisions as $decision) {

          echo "<div class='col-md-3 my-3'>";
          echo '<div class="card p-2 bg-yellow decision-card border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $decision['decision_ID'] . '">';

          echo '<div class="d-flex justify-content-between w-100 my-2">';
          echo '<div class="d-flex">';
          echo  '<span class="rounded-1  fs-6 shadow-sm text-secondary bg-primary text-white fw-normal d-flex justify-content-center align-items-center p-1">decision</span>';

          if ($decision['decision_Status'] == '0') {
            echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-orange border-0  mx-2 text-white fw-normal d-flex justify-content-center align-items-center p-1">incomplete</span>';
          }
          echo '</div>';
          echo '<div class="btn-group">
          <button type="button" class="btn bg-white  p-0 m-0 rounded-1 p-1  menu-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-dots-vertical-rounded h3 m-0 p-0"></i>
          </button>
          <ul class="dropdown-menu m-0 p-0">
             <li class="m-0 p-0"><a class="dropdown-item edit-btn fw-bold p-2" data-id="' . $decision['decision_ID'] . '" href="#">Edit Decision</a></li>
            <li><hr class="dropdown-divider m-0 p-0"></li>
            <li class="m-0 p-0"><a class="dropdown-item del-btn fw-bold p-2 text-danger" data-id="' . $decision['decision_ID'] . '" href="#"><i class="bx bxs-trash-alt"></i> Delete Decision</a></li>
          </ul>
        </div>';
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
  <script src="https://cdn.jsdelivr.net/npm/@mojs/core"></script>
</body>

<script>
  $(document).on('click', '.decision-card', function(event) {
    // Check if the clicked target is not within the dropdown menu or the dropdown toggle with the specific class
    if (!$(event.target).closest('.menu-dropdown, .dropdown-menu').length) {
      var cardId = $(this).attr('data-id');
      var url = 'protected/view/decision_detail_view.php?id=' + cardId;

      window.location.href = url;
    }
  });
  $(document).on('click', '.edit-btn', function(event) {
    var cardId = $(this).attr('data-id');
    var decisionTitle = $(this).closest('.decision-card').find('p.fs-3').text();
    $('#HiddenDecisionID').val(cardId);
    $('#EditDesicion-Modal').find('input#Input-Edit-DecisionTitle').val(decisionTitle);
    $('#EditDesicion-Modal').modal('show');
  });

  $('#searchBar').on('input', function() {
    var search_term = $('#searchBar').val().trim();
    $.ajax({
      url: 'protected/controller/search_decision.php',
      method: 'POST',
      data: {
        search: search_term
      },
      success: function(returnData) {
        var results = JSON.parse(returnData);
        $('#search_results').html('');
        if (results.length === 0) { // Check if the results array is empty
          $('#search_results').append('<div class="col-md-12 my-3"><p>No decision found.</p></div>');
        } else {
          $.each(results, function(index, decision) {
            var decisionHtml = generateDecisionHtml(decision);
            var $decisionDiv = $(decisionHtml);
            $('#search_results').append($decisionDiv);
            $decisionDiv.fadeIn(400);
          });
        }
      }
    });
  });

  function refreshDecisionList() {
    $.ajax({
      url: "protected/controller/front_Controller_Decision.php?action=listDecisions",
      method: "GET",
      success: function(response) {
        var results = JSON.parse(response);
        $("#search_results").html("");
        $.each(results, function(index, decision) {
          var decisionHtml = generateDecisionHtml(decision);
          var $decisionDiv = $(decisionHtml);
          $("#search_results").append($decisionDiv);
          $decisionDiv.fadeIn(400);
        });
      },
    });
  }

  function showNotification(type, text) {
    new Noty({
      type: type,
      layout: "topRight",
      text: text,
      timeout: 2000,
      animation: {
        open: function(promise) {
          var n = this;
          var Timeline = new mojs.Timeline();
          var body = new mojs.Html({
            el: n.barDom,
            x: {
              500: 0,
              delay: 0,
              duration: 500,
              easing: 'elastic.out'
            },
            isForce3d: true,
            onComplete: function() {
              promise(function(resolve) {
                resolve();
              })
            }
          });

          var parent = new mojs.Shape({
            parent: n.barDom,
            width: 200,
            height: n.barDom.getBoundingClientRect().height,
            radius: 0,
            x: {
              [150]: -150
            },
            duration: 1.2 * 500,
            isShowStart: true
          });

          n.barDom.style['overflow'] = 'visible';
          parent.el.style['overflow'] = 'hidden';

          var burst = new mojs.Burst({
            parent: parent.el,
            count: 10,
            top: n.barDom.getBoundingClientRect().height + 75,
            degree: 90,
            radius: 75,
            angle: {
              [-90]: 40
            },
            children: {
              fill: '#EBD761',
              delay: 'stagger(500, -50)',
              radius: 'rand(8, 25)',
              direction: -1,
              isSwirl: true
            }
          });

          var fadeBurst = new mojs.Burst({
            parent: parent.el,
            count: 2,
            degree: 0,
            angle: 75,
            radius: {
              0: 100
            },
            top: '90%',
            children: {
              fill: '#EBD761',
              pathScale: [.65, 1],
              radius: 'rand(12, 15)',
              direction: [-1, 1],
              delay: .8 * 500,
              isSwirl: true
            }
          });

          Timeline.add(body, burst, fadeBurst, parent);
          Timeline.play();
        },
        close: function(promise) {
          var n = this;
          new mojs.Html({
            el: n.barDom,
            x: {
              0: 500,
              delay: 10,
              duration: 500,
              easing: 'cubic.out'
            },
            skewY: {
              0: 10,
              delay: 10,
              duration: 500,
              easing: 'cubic.out'
            },
            isForce3d: true,
            onComplete: function() {
              promise(function(resolve) {
                resolve();
              })
            }
          }).play();
        }
      }
    }).show();
  }
  $(document).ready(function() {
    $("#add-decision-form").submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "protected/controller/front_Controller_Decision.php?action=addDecisionAjax",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
          var result = JSON.parse(response);
          if (result.success) {
            // Show Noty notification for success
            showNotification("warning", "add Decision Success");
            // Close the modal and reset the form
            $("#AddDesicion-Modal").modal("hide");
            $("#add-decision-form").trigger("reset");
            refreshDecisionList();
          }
        },
      });
    });

    // Edit decision form submission
    $("#edit-decision-form").submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "protected/controller/front_Controller_Decision.php?action=updateDecisionAjax",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
          var result = JSON.parse(response);
          if (result.success) {
            // Show Noty notification for success
            showNotification("warning", "edit Decision Success");

            // Close the modal and reset the form
            $("#EditDesicion-Modal").modal("hide");
            $("#edit-decision-form").trigger("reset");
            refreshDecisionList();
          }
        },
      });
    });

    // Delete decision button click
    $(document).on("click", ".del-btn", function(event) {
      var decisionId = $(this).attr("data-id");

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this decision!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, keep it",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "protected/controller/front_Controller_Decision.php?action=deleteDecisionAjax",
            method: "POST",
            data: {
              id: decisionId
            },
            success: function(response) {
              var result = JSON.parse(response);
              if (result.success) {
                // Show Noty notification for success
                showNotification("error", "Delete Decision Success");

                refreshDecisionList();
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

  function generateDecisionHtml(decision) {
    var statusHtml = '';
    if (decision.decision_Status == '0') {
      statusHtml = '<span class="rounded-1 p-1 d-flex justify-content-center align-items-center fs-6 shadow-sm text-secondary bg-orange border-0  mx-2 text-white fw-normal">incomplete</span>';
    }

    var html = `
    <div class='col-md-3 my-3' style='display: none;'>
      <div class="card p-2 bg-yellow decision-card border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="${decision.decision_ID}">
        
      <div class="d-flex justify-content-between w-100">
        <div class='d-flex'>
            <span class="rounded-1 d-flex justify-content-center align-items-center p-1 fs-6 shadow-sm text-secondary bg-primary text-white align-middle fw-normal">decision</span>
            ${statusHtml}
        </div>
          <div class="btn-group">
          <button type="button" class="btn bg-white  p-0 m-0 rounded-1 p-1  menu-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-dots-vertical-rounded h3 m-0 p-0"></i>
          </button>
        
          <ul class="dropdown-menu m-0 p-0">
             <li class="m-0 p-0"><a class="dropdown-item fw-bold p-2 edit-btn" data-id="${decision.decision_ID}" href="#">Edit Decision</a></li>
            <li><hr class="dropdown-divider m-0 p-0"></li>
            <li class="m-0 p-0"><a class="dropdown-item fw-bold p-2 text-danger del-btn" data-id="${decision.decision_ID}" href="#"><i class="bx bxs-trash-alt"></i> Delete Decision</a></li>
          </ul>
        </div>
        </div>
        <p class="p-0 mt-2 fs-3">${decision.decision_Title}</p>
        <p class="text-secondary fw-normal  mb-3 p-0"><small>${decision.decision_Date}</small></p>
       
      </div>
    </div>
  `;
    return html;
  }
</script>

<div class="modal fade" id="AddDesicion-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Desicion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="add-decision-form" method="POST">
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

<div class="modal fade" id="EditDesicion-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Desicion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="edit-decision-form" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Input Desicion title</label>
            <input type="text" name="title" class="form-control" id="Input-Edit-DecisionTitle" aria-describedby="emailHelp">
            <input type="hidden" name="id" class="form-control" id="HiddenDecisionID" aria-describedby="emailHelp">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>

</html>