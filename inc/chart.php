<!-- https://canvasjs.com/php-charts/spline-area-chart/ -->

<?php
	require_once("config/config.php");
	$dataPoints = $libraryService->getLoanTotalByMonth();
 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<script>
			window.onload = function () {
				var chart = new CanvasJS.Chart("chartContainer", {
					animationEnabled: true,
					theme: "light2",
					title:{
						text: ""
					},
					axisX: {
						valueFormatString: "MMM YYYY",
						minimum: new Date(2026, 0, 1),
						maximum: new Date(2026, 11, 31)
					},
					axisY: {
						title: "Number of Book Loans",
						includeZero: true,
						maximum: 10,
						minimum: 0
					},
					data: [{
						type: "splineArea",
						color: "#6599FF",
						xValueType: "dateTime",
						xValueFormatString: "MMM YYYY",
						yValueFormatString: "#,##0 Loans",
						dataPoints: <?php echo json_encode($dataPoints); ?>
					}]
				});
				
				chart.render();
			}
		</script>
	</head>
	
	<body>
		<div id="chartContainer" style="height: 500px; width: 1000px;"></div>
		<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
	</body>
</html>