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
    require_once '../model/criteria_model.php';


    $decisionModel = new Decision_Model($pdo);
    $decision = $decisionModel->get_decision_byID($cardID);

    $criteriaModel = new Criteria_Model($pdo);

    $criterias = $criteriaModel->get_criterias($cardID);
    $importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
    require_once('navbar.php');
    ?>
    <section id="content-2" class="container-criteria bg-dbrown py-4 ">
        <div class="container-xxl">
            <nav style="--bs-breadcrumb-divider: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%228%22 height=%228%22%3E%3Cpath d=%22M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z%22 fill=%22white%22/%3E%3C/svg%3E');" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-decoration-none text-white "><a class='text-decoration-none text-white text-orange' href="../../index.php">Home</a></li>
                    <li class="breadcrumb-item text-decoration-none text-white"><a class='text-decoration-none text-white text-orange' href="decision_detail_view.php?id=<?= $cardID ?>">Decision Detail</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Result</li>
                </ol>
            </nav>
            <div class='m-0 p-0  '>
                <p class="h2 fw-bold heading text-white">Result</p>
                <p class="h5 text-white fw-normal">Best Decision</p>
            </div>
        </div>


    </section>
    <section id="content-2" class=" bg-white py-4 ">

        <div class="container-xxl">
            <p class="h2  fw-bold  text-orange mb-5">Overview</p>

            <div class="row ">


                <div class='d-flex align-items-center mt-3'>

                    <ul class="nav nav-pills mb-3 shadow-sm " id="pills-tab" role="tablist">

                        <div class='d-flex p-2 bg-light rounded'>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Fuzzy TOPSIS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Fuzzy VIKOR</button>
                            </li>
                        </div>

                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                        <div class="col-12 card shadow-sm border-0 p-5">
                            <div class='d-flex justify-content-between'>
                                <p class='h4 heading text-muted'>Fuzzy Topsis <button type="button" class="btn btn-warning p-1 m-0 " data-toggle="tooltip" title="Topsis is counted from the CC value (coefficient correlation). The larger the CC value, the higher the rank.">
                                        <i class='bx bx-info-circle h4 p-0 m-0 align-middle'></i>
                                    </button></p>

                            </div>
                            <div class='row'>
                                <div class='col-md-7'>
                                    <div class='chart-wrapper'>
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <h5 class='text-orange'>Best Decision</h5>
                                    <h3 class='text-main heading topsis-best'>Best Decision</h3>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                        <div class="col-12 card shadow-sm border-0 p-5">
                            <p class='h4 heading text-muted'>
                                Fuzzy VIKOR
                                <button type="button" class="btn btn-warning p-1 m-0" data-toggle="tooltip" title="In Fuzzy VIKOR, the Q value is used for ranking. The smaller the Q value, the higher the rank.">
                                    <i class='bx bx-info-circle h4 p-0 m-0 align-middle'></i>
                                </button>

                            <div class='row'>
                                <div class='col-md-7'>
                                    <div class='chart-wrapper'>
                                        <canvas id="myChart2"></canvas>
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <h5 class='text-orange'>Best Decision</h5>
                                    <h3 class='text-main heading vikor-best'>Best Decision</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class='container-xxl'>

            <p class="text-main heading h3 my-5">Result Table</p>
            <div id="decisionTable"></div>

        </div>

        <div class='container-xxl my-5'>
            <label for="exampleFormControlInput1" class="form-label">Give Feedback</label>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                <label for="floatingTextarea2">Feedback</label>
            </div>
            <button class='btn btn-primary my-2'>submit</button>
        </div>


    </section>
</body>
<script>
    const colors = [
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)'
    ]
    let myChart, myChart2;
    $(document).ready(function() {
        let topsisBest, vikorBest; // Initialize variables to store best decisions

        document.getElementById('pills-profile-tab').addEventListener('click', function() {
            setTimeout(function() {
                myChart2.resize(); // Call the resize method on the vikorChart instance

            }, 100);
        });

        document.getElementById('pills-home-tab').addEventListener('click', function() {
            setTimeout(function() {
                myChart2.resize(); // Call the resize method on the vikorChart instance

            }, 100);
        });
        $.ajax({
            url: '../controller/topsis_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                const matrixData = JSON.parse(response);
                createTopsisChart(matrixData);
                topsisBest = matrixData.reduce((best, current) => current.cc > best.cc ? current : best);

                $(".topsis-best").html(topsisBest.alternative_name)
                updateTable();
            }
        });

        $.ajax({
            url: '../controller/vikor_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                let matrixData = JSON.parse(response);
                matrixData = Object.values(matrixData); // Convert VIKOR data object into an array
                createVikorChart(matrixData);
                vikorBest = matrixData.reduce((best, current) => current.q < best.q ? current : best);

                $(".vikor-best").html(vikorBest.alternative_name)
                updateTable();
            }
        });

        function updateTable() {
            if (topsisBest && vikorBest) {
                // Compare TOPSIS and VIKOR best decisions to determine the overall best decision
                const overallBest = topsisBest.cc >= vikorBest.cc ? topsisBest : vikorBest;

                // Create a table to display the best decisions
                const table = `<table class="table table-striped-columns table-hoverable" >
                <tr>
                    <th>Method</th>
                    <th>Best Decision</th>
                </tr>
                <tr>
                    <td>TOPSIS</td>
                    <td>${topsisBest.alternative_name}</td>
                </tr>
                <tr>
                    <td>VIKOR</td>
                    <td>${vikorBest.alternative_name}</td>
                </tr>
                <tr>
                    <td>Overall Best</td>
                    <td>${overallBest.alternative_name}</td>
                </tr>
            </table>`;

                // Add the table to the HTML
                document.getElementById('decisionTable').innerHTML = table;
            }
        }
    });

    function createTopsisChart(matrixData) {
        const labels = matrixData.map(data => data.alternative_name);
        const ccValues = matrixData.map(data => data.cc);

        const sumCcValues = ccValues.reduce((acc, val) => acc + val, 0);
        const percentages = ccValues.map(ccValue => (ccValue / sumCcValues) * 100);

        const backgroundColors = colors.map(color => chroma(color).alpha(0.2).css());
        const borderColors = colors.map(color => chroma(color).darken().css());
        if (myChart) {
            myChart.destroy();
        }
        const ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Topsis Final Ranking',
                    data: percentages,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                    },
                    title: {
                        display: true,
                        text: 'Fuzzy Topsis Final Rank for <?php echo $decision['decision_Title'] ?>'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                   
                }
            }
        });
    }

    function createVikorChart(matrixData) {
        const sortedData = Object.values(matrixData).sort((a, b) => a.rank - b.rank);
        const labels = sortedData.map(data => data.alternative_name);
        const ranks = sortedData.map(data => data.rank);

        const maxRank = Math.max(...ranks);
        const percentages = ranks.map(rank => ((maxRank - rank + 1) / maxRank) * 100);

        const backgroundColors = colors.map(color => chroma(color).alpha(0.2).css());
        const borderColors = colors.map(color => chroma(color).darken().css());
        if (myChart2) {
            myChart2.destroy();
        }
        const ctx = document.getElementById('myChart2').getContext('2d');
        myChart2 = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'VIKOR Final Ranking',
                    data: percentages,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                    },
                    title: {
                        display: true,
                        text: 'Fuzzy VIKOR Final Rank for <?php echo $decision['decision_Title'] ?>'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
               
                }
            }
        });
    }
</script>

</html>