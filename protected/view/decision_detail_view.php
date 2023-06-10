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
          <div class="card criteria-card pattern-grid-xl  text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
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
          <div class="card alternative-card pattern-grid-xl  text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
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
          <div class="card trade-card pattern-grid-xl text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
             <div class='d-flex justify-content-start text-start'>
               <p class="m-0 p-0 fw-bold heading h3 text-start text-dark"><i class='bx bx-pointer bx-tada'></i></p>
               <p class="m-0 p-0 fw-bold heading h4 text-start text-dark">Trade-Off Analysis</p>
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
          <div class="card result-card pattern-grid-xl text-orange position-relative shadow-sm me-2 p-4 border-0 bg-yellow text-start d-flex justify-content-center align-items-start">
         
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
  <script src="https://cdn.jsdelivr.net/npm/@mojs/core"></script>
</body>
<script>
  
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
    // Replace with the actual decision ID
    var decision_id = <?= $cardID; ?>;

    $.ajax({
    type: "POST",
    url: "../controller/front_Controller_Decision.php?action=checkCriteria",
    data: { decision_id: decision_id },
    dataType: "json",
    async: false,
    success: function(response) {
        criteriaValid = response.status;
    }
});

$.ajax({
    type: "POST",
    url: "../controller/front_Controller_Decision.php?action=checkAlternative",
    data: { decision_id: decision_id },
    dataType: "json",
    async: false,
    success: function(response) {
        alternativeValid = response.status;
    }
});

    if (!criteriaValid) {
        $('.alternative-card').removeClass('bg-yellow text-orange').addClass('bg-danger text-dark');
    }
    if (!alternativeValid || !criteriaValid)  {
        $('.trade-card').removeClass('bg-yellow text-orange').addClass('bg-danger text-dark');
        $('.result-card').removeClass('bg-yellow text-orange').addClass('bg-danger text-dark');
    }
    
});
$('.criteria-card').on('click', function() {

        var cardId = <?= $cardID; ?>;
        var url = 'criteria_view.php?id=' + cardId;
        window.location.href = url;
   
    
});

$('.alternative-card').on('click', function() {
    if (criteriaValid) {
        
            var cardId = <?= $cardID; ?>;
            var url = 'alternative_view.php?id=' + cardId;
            window.location.href = url;
        
    } else {
        showNotification('error', 'Criteria is not valid. Please complete the criteria step first.');
    }
});

$('.trade-card').on('click', function() {
    if (criteriaValid && alternativeValid) {
        var cardId = <?= $cardID; ?>;
        var url = 'tradeoff_view.php?id=' + cardId;
        window.location.href = url;
    } else {
        showNotification('error', 'Please complete the criteria and alternative steps first.');
    }
});

$('.result-card').on('click', function() {
    if (criteriaValid && alternativeValid) {
        var cardId = <?= $cardID; ?>;
        var url = 'result_view.php?id=' + cardId;
        window.location.href = url;
    } else {
        showNotification('error', 'Please complete the criteria and alternative steps first.');
    }
});
</script>

</html>