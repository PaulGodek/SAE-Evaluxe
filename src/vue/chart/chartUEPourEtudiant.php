<?php
if (!isset($dataPoints) || !is_array($dataPoints)) {
    $dataPoints = [];
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Évolution des compétences sur les semestres"
                },
                axisY: {
                    title: "Note (/20)",
                    maximum: 20,
                    minimum: 0,
                    interval: 2
                },
                axisX: {
                    title: "Semestres"
                },
                data: [
                    <?php foreach ($dataPoints as $competence => $points) { ?>
                    {
                        type: "line",
                        showInLegend: true,
                        name: "<?php echo $competence; ?>",
                        dataPoints: <?php echo json_encode($points, JSON_NUMERIC_CHECK); ?>
                    },
                    <?php } ?>
                ]
            });

            chart.render();

        }
    </script>
    <style>
        #chartContainer {
            height: 70vh;
            width: 100%;
        }
    </style>
</head>
<body>
<div id="chartContainer"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>