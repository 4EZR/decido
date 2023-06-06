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

<body>

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

    <section id="content-2" class=" bg-white py-4 ">
        <div class='container-xxl'>
            <div class="row ">
                <div class="col-6">
                    <h1 class='heading text-muted'>Fuzzy Topsis</h1>
                    <canvas id="myChart"></canvas>
                </div>

                <div class="col-6">
                    <h1 class='heading text-muted'>Fuzzy Vikor</h1>
                    <canvas id="myChart2"></canvas>
                </div>

            </div>
            <div class="row">
                <div class="container-xxl py-5">


                    <p class="text-main heading h3 my-3 text-muted">Comparision Chart</p>

                    <div class='row'>
                        <div class="col-6">
                            <canvas id="myChart3"></canvas>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <canvas id="myChart4"></canvas>
                        </div>
                    </div>




                </div>

            </div>

            <div class="row">
                <div class="container-xxl py-5">


                    <p class="text-main heading h3">Sensitive Analysis</p>


                    <div id='criteria-list' class="d-flex">



                        <?php
                        foreach ($criterias as $criteria) {

                            echo "<div class='criteria-item  m-3' data-id='" . $criteria['criteria_ID'] . "'>";
                            echo '<div class="card p-3 bg-yellow  border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $criteria['criteria_ID'] . '">';

                            echo '<div class="d-flex">';
                            //echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-primary text-white fw-normal">' . $decision['decision_Title'] . '</span>';

                            echo '</div>';
                            echo  '<p class="p-0 mt-2 fs-3">' . $criteria['Criteria_Title'] . '</p>';

                            echo "<p class='text-secondary fw-normal  mb-3 p-0'><small> " . $criteria['TermLevel_1'] . ", " . $criteria['TermLevel_2'] . ", " . $criteria['TermLevel_3'] . ", " . $criteria['TermLevel_4'] . ", " . $criteria['TermLevel_5'] .  "</small></p>";
                            echo "<select class='form-select importance-criteria-select' data-id='" . $criteria['criteria_ID'] . "'>";
                            foreach ($importanceLevels as $index => $level) {
                                $optionValue = $index + 1; // Use 1-based indexing
                                $selected = ($criteria['Criteria_Importance'] == $optionValue) ? 'selected' : '';
                                echo "<option value='$optionValue' $selected>$level</option>";
                            }
                            echo "</select>";
                            echo '</div>';

                            echo '</div>';
                        }

                        ?>

                    </div>




                </div>

            </div>
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
    let myChart, myChart2, myChart3, myChart4;
    $(document).ready(function() {
        let topsisData, vikorData;
        let requestsCompleted = 0;

        function checkRequestsCompleted() {
            if (requestsCompleted === 2) {
                createRadarChart(topsisData, vikorData);
                createSimilarityChart(topsisData, vikorData);
            }
        }

        $.ajax({
            url: '../controller/topsis_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                topsisData = JSON.parse(response);
                createTopsisChart(topsisData);
                requestsCompleted++;
                checkRequestsCompleted();
            }
        });

        $.ajax({
            url: '../controller/vikor_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                vikorData = JSON.parse(response);
                createVikorChart(vikorData);
                requestsCompleted++;
                checkRequestsCompleted();
            }
        });
    });
    $('.importance-criteria-select').change(function() {
        var criteriaId = $(this).attr("data-id");
        var importance_level = $(this).val();
        let topsisData, vikorData;
        $.ajax({
            url: '../controller/update_importance.php',
            method: 'POST',
            data: {
                criteria_id: criteriaId,
                importance_level: importance_level
            },
            success: function(response) {
                var res = JSON.parse(response);
                console.log(response);

                $.ajax({
                    url: '../controller/topsis_rank.php',
                    type: 'POST',
                    data: {
                        id: <?= $cardID ?>
                    },
                    success: function(response) {
                        topsisData = JSON.parse(response);

                        createTopsisChart(topsisData);
                        if (vikorData) {
                            createRadarChart(topsisData, vikorData);
                            createSimilarityChart(topsisData, vikorData);
                        }

                    }
                });
                $.ajax({
                    url: '../controller/vikor_rank.php',
                    type: 'POST',
                    data: {
                        id: <?= $cardID ?>
                    },
                    success: function(response) {
                        vikorData = JSON.parse(response);

                        createVikorChart(vikorData);
                        if (topsisData) {
                            createRadarChart(topsisData, vikorData);
                            createSimilarityChart(topsisData, vikorData);
                        }
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });


    });


    function createSimilarityChart(topsisData, vikorData) {
        const labels = topsisData.map(data => data.alternative_name);
        const topsisRank = topsisData.map(data => data.rank);

        // Sort VIKOR data according to the order of alternatives in TOPSIS data
        const sortedVikorData = topsisData.map(topsisAlternative => {
            return Object.values(vikorData).find(vikorAlternative => vikorAlternative.alternative_name === topsisAlternative.alternative_name);
        });

        const vikorRank = sortedVikorData.map(data => data.rank);

        const diagonalLineData = topsisRank.map(x => ({
            x,
            y: x
        }));

        const data = {
            labels: labels,
            datasets: [{
                    label: 'Alternatives',
                    data: labels.map((label, index) => ({
                        x: topsisRank[index],
                        y: vikorRank[index]
                    })),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    pointRadius: 5,
                    pointHoverRadius: 8
                },
                {
                    label: 'Diagonal Line',
                    data: diagonalLineData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false,
                    showLine: true,
                    pointRadius: 0
                }
            ]
        };

        const config = {
            type: 'scatter',
            data: data,
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'TOPSIS Rank'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'VIKOR Rank'
                        }
                    }
                }
            }
        };

        if (myChart4) {
            myChart4.destroy();
        }

        const ctx = document.getElementById('myChart4').getContext('2d');
        myChart4 = new Chart(ctx, config);
    }

    function createTopsisChart(matrixData) {
        const labels = matrixData.map(data => data.alternative_name);
        const dataPoints = matrixData.map(data => data.cc);

        const backgroundColors = colors.map(color => chroma(color).alpha(0.2).css());
        const borderColors = colors.map(color => chroma(color).darken().css());
        if (myChart) {
            myChart.destroy();
        }
        const ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Topsis Final Ranking',
                    data: dataPoints,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false,

                    },
                    title: {
                        display: true,
                        text: 'Fuzzy Topsis Final Rank for <?php echo $decision['decision_Title'] ?>'
                    }
                }
            }
        });
    }

    function createVikorChart(matrixData) {
        const sortedData = Object.values(matrixData).sort((a, b) => a.rank - b.rank);
        const labels = sortedData.map(data => data.alternative_name);
        const dataPoints = sortedData.map(data => data.q_value);

        const backgroundColors = colors.map(color => chroma(color).alpha(0.2).css());
        const borderColors = colors.map(color => chroma(color).darken().css());
        if (myChart2) {
            myChart2.destroy();
        }
        const ctx = document.getElementById('myChart2').getContext('2d');
        myChart2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'VIKOR Final Ranking',
                    data: dataPoints,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Fuzzy VIKOR Final Rank for <?php echo $decision['decision_Title'] ?>'
                    }
                }
            }
        });

    }

    function calculateNormalizationFactors(topsisData, vikorData) {
        const topsisCCValues = topsisData.map(item => item.cc);
        const vikorQValues = Object.values(vikorData).map(item => item.q_value);

        const minTopsis = Math.min(...topsisCCValues);
        const maxTopsis = Math.max(...topsisCCValues);
        const minVikor = Math.min(...vikorQValues);
        const maxVikor = Math.max(...vikorQValues);

        const normalizeTopsis = value => 2 * (value - minTopsis) / (maxTopsis - minTopsis);
        const normalizeVikor = value => 2 * (value - minVikor) / (maxVikor - minVikor);

        const normalizedTopsisData = topsisData.map(item => ({
            ...item,
            cc: normalizeTopsis(item.cc)
        }));

        const normalizedVikorData = Object.fromEntries(
            Object.entries(vikorData).map(([key, value]) => [key, {
                ...value,
                q_value: normalizeVikor(value.q_value)
            }])
        );

        return {
            normalizedTopsisData,
            normalizedVikorData
        };
    }

    function createRadarChart(topsisData, vikorData) {
        const labels = topsisData.map(data => data.alternative_name);
        const topsisCC = topsisData.map(data => data.cc);
        const maxTopsisCC = Math.max(...topsisCC);
        const normalizedTopsisCC = topsisCC.map(cc => cc / maxTopsisCC * 2);

        const sortedData = Object.values(vikorData).sort((a, b) => a.rank - b.rank);
        const vikorQ = sortedData.map(data => data.q_value);
        const maxVikorQ = Math.max(...vikorQ);
        const normalizedVikorQ = vikorQ.map(q => q / maxVikorQ * 2);

        const data = {
            labels: labels,
            datasets: [{
                    label: `Fuzzy VIKOR (Normalized Q value) - Multiplied by ${(2 / maxVikorQ).toFixed(2)}`,
                    data: normalizedVikorQ,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: `Fuzzy TOPSIS (Normalized CC value) - Multiplied by ${(2 / maxTopsisCC).toFixed(2)}`,
                    data: normalizedTopsisCC,
                    fill: true,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(255, 99, 132, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }
            ]
        };

        const config = {
            type: 'radar',
            data: data,
            options: {
                elements: {
                    line: {
                        borderWidth: 3
                    }
                },
                scales: {
                    r: {
                        min: 0,
                        max: 2,
                        ticks: {
                            stepSize: 0.5
                        }
                    }
                }
            }
        };

        if (myChart3) {
            myChart3.destroy();
        }

        const ctx = document.getElementById('myChart3').getContext('2d');
        myChart3 = new Chart(ctx, config);
    }
</script>

</html>