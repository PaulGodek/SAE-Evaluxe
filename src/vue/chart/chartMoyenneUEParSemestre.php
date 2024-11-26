<div id="chartContainer" style="height: 370px; width: 100%;"></div>

<script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1", // Chọn theme
            title:{
                text: "Moyenne des UEs pour tous les semestres"
            },
            axisY:{
                title: "Moyenne",
                includeZero: false
            },
            legend:{
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    }
</script>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<style>
    main {
        background: white;
    }
</style>