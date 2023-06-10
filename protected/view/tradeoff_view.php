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



    <section id="content-2" class="container-criteria bg-dbrown py-4 ">
        <div class="container-xxl">
            <nav style="--bs-breadcrumb-divider: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%228%22 height=%228%22%3E%3Cpath d=%22M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z%22 fill=%22white%22/%3E%3C/svg%3E');" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-decoration-none text-white "><a class='text-decoration-none text-white text-orange' href="../../index.php">Home</a></li>
                    <li class="breadcrumb-item text-decoration-none text-white"><a class='text-decoration-none text-white text-orange' href="decision_detail_view.php?id=<?= $cardID ?>">Decision Detail</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Tradeoff</li>
                </ol>
            </nav>
            <div class='m-0 p-0  '>
                <p class="h2 fw-bold heading text-white">TradeOff</p>
                <p class="h5 text-white fw-normal">Have an indept look at Fuzzy Vikor and Fuzzy TOPSIS rank Comparision</p>
            </div>
        </div>
    </section>
    <section id="content-2" class=" bg-white py-4 ">

        <div class='container-xxl'>
            <p class="h2  fw-bold  text-orange mb-5">Analytics</p>

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
                                <button class='btn btn-primary' id="showMatrixBtn" data-id="<?= $cardID ?>" data-type='Topsis'>Show Matrix</button>
                            </div>
                            <canvas id="myChart"></canvas>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                        <div class="col-12 card shadow-sm border-0 p-5">
                            <p class='h4 heading text-muted'>
                                Fuzzy VIKOR
                                <button type="button" class="btn btn-warning p-1 m-0" data-toggle="tooltip" title="In Fuzzy VIKOR, the Q value is used for ranking. The smaller the Q value, the higher the rank.">
                                    <i class='bx bx-info-circle h4 p-0 m-0 align-middle'></i>
                                </button>
                            </p>
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="container-xxl py-5">


                    <p class="text-main heading h3 my-4 mx-0 p-0 ">Comparision Chart</p>

                    <div class='row  border-0 shadow-sm'>
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


                    <div id='criteria-list' class="d-flex align-items-stretch flex-wrap">
                        <?php
                        $importanceLevels = ['Very Not Important', 'Not Important', 'Neutral', 'Important', 'Very Important'];
                        $counter = 0;
                        foreach ($criterias as $criteria) {
                            $counter++;
                            echo "<div class='criteria-item  flex-fill me-5 mb-5' data-id='" . $criteria['criteria_ID'] . "'>";
                            echo '<div class="card p-3 bg-brown  border-0 shadow-sm d-flex align-items-start justify-content-start text-start" data-id="' . $criteria['criteria_ID'] . '">';

                            echo '<div class="d-flex">';
                            //echo  '<span class="rounded-1 p-1 fs-6 shadow-sm text-secondary bg-primary text-white fw-normal">' . $decision['decision_Title'] . '</span>';

                            echo '</div>';
                            echo '<div class="d-flex w-100 justify-content-between align-items-center">';

                            echo  '<p class="p-0 m-0 fs-1 text-orange heading">C' . $counter . '</p>';

                            echo '</div>';
                            echo  '<p class="p-0 mt-2  mb-0 lh-0 pb-0 fs-3 heading criteria-title">' . $criteria['Criteria_Title'] . '</p>';
                            echo "<p class='text-secondary fw-normal mt-0 mb-0 mb-3 p-0 criteria-type'><small>" . ($criteria['Criteria_Type'] == 1 ? "Benefit Type" : "Cost Type") . "</small></p>";
                            echo "<select class='form-select-sm form-select importance-criteria-select border border-primary border-3' data-id='" . $criteria['criteria_ID'] . "'>";

                            echo "<option value='0'>Please select one</option>"; // Add this line for the 0 value option
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

<div class="modal fade" id="modal-matrix" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Criteria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>

        </div>
    </div>
</div>
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

    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('.importance-criteria-select').change(function() {
        var criteriaId = $(this).attr("data-id");
        var importance_level = $(this).val();
        let topsisData, vikorData;
        $.ajax({
            url: "../controller/front_Controller_Criteria.php?action=updateWeight",
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
                },
                responsive: true,

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
                responsive: true,
                aspectRatio: 3,

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
                responsive: true,
                aspectRatio: 3,

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
                responsive: true,

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

    function generateMatrixHTML(jsonData) {
        let tabPillsHTML = '<ul class="nav nav-pills">';
        let tabContentHTML = '<div class="tab-content">';

        // Loop through the JSON data and generate the HTML for each section
        for (const key in jsonData) {
            const isActive = (key === "kriteria_tfn");
            tabPillsHTML += `<li class="nav-item"><a class="nav-link${isActive ? ' active' : ''}" data-bs-toggle="pill" href="#${key}">${key}</a></li>`;
            tabContentHTML += `<div id="${key}" class="tab-pane fade${isActive ? ' show active' : ''}"><pre>${JSON.stringify(jsonData[key], null, 2)}</pre></div>`;
        }
        tabPillsHTML += '</ul>';
        tabContentHTML += '</div>';

        return {
            tabPillsHTML,
            tabContentHTML
        };
    }

    $(document).on("click", "#showMatrixBtn", function(event) {
        var alternativeId = $(this).attr("data-id");
        var type = $(this).attr("data-type");

        $.ajax({
            url: '../controller/topsis_matrix.php',
            type: 'POST',
            data: {
                id: alternativeId
            },
            dataType: 'json',
            success: function(jsonData) {
                // Call the generateMatrixHTML function and append the returned HTML to the modal body
                const matrixHTML = generateMatrixHTML(jsonData);
                $('#modal-matrix .modal-body').html(matrixHTML.tabPillsHTML + matrixHTML.tabContentHTML);

                // Show the modal
                $('#modal-matrix').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching JSON data:", error);
            }
        });
        $('#modal-matrix').modal('show');


    });
</script>

</html>