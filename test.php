<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar and Line Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <canvas id="myChart"></canvas>
        <br>
        <button onclick="toggleChartType()">Toggle Chart Type</button>
    </div>

    <script>
        // Initial data for the bar chart
        var barChartData = {
            labels: ['Label 1', 'Label 2', 'Label 3', 'Label 4', 'Label 5'],
            datasets: [{
                label: 'Bar Chart',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                data: [12, 19, 3, 5, 2]
            }]
        };

        // Configuration for the bar chart
        var barChartOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Initialize the bar chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });

        // Function to toggle between bar and line chart
        function toggleChartType() {
            if (myBarChart.config.type === 'bar') {
                // Switch to line chart
                myBarChart.config.type = 'line';
                myBarChart.update();
            } else {
                // Switch to bar chart
                myBarChart.config.type = 'bar';
                myBarChart.update();
            }
        }
    </script>
</body>
</html>
