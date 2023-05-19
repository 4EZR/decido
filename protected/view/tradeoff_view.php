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

<body >

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
    let myChart, myChart2;
    $(document).ready(function() {
        $.ajax({
            url: '../controller/topsis_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                const matrixData = JSON.parse(response);
                createTopsisChart(matrixData);
            }
        });
        $.ajax({
            url: '../controller/vikor_rank.php',
            type: 'POST',
            data: {
                id: <?= $cardID ?>
            },
            success: function(response) {
                const matrixData = JSON.parse(response);
                createVikorChart(matrixData);
            }
        });

    });
    $('.importance-criteria-select').change(function() {
        var criteriaId = $(this).attr("data-id");
        var importance_level = $(this).val();
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
                        const matrixData = JSON.parse(response);
                        createTopsisChart(matrixData);
                    }
                });
                $.ajax({
                    url: '../controller/vikor_rank.php',
                    type: 'POST',
                    data: {
                        id: <?= $cardID ?>
                    },
                    success: function(response) {
                        const matrixData = JSON.parse(response);
                        createVikorChart(matrixData);
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });


    });

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
</script>

</html>