<?php
if (!isset($dataPoints) || !is_array($dataPoints)) {
    $dataPoints = [];
}
?>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>

<script>
    window.onload = function () {
        const chartData = <?php echo json_encode(array_map(function($key, $value) {
            return ["label" => $key, "y" => $value];
        }, array_keys($dataPoints), $dataPoints), JSON_NUMERIC_CHECK); ?>;

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            title: {
                text: "Répartition des étudiants par Parcours"
            },
            subtitles: [{
                text: "Statistiques des parcours"
            }],
            data: [{
                type: "pie",
                showInLegend: true,
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - {y} étudiants",
                yValueFormatString: "#,##0 étudiants",
                dataPoints: chartData
            }]
        });

        chart.render();
    }
</script>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<style>
    main {
        background-color: white;
    }
</style>