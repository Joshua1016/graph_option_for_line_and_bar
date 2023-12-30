<?php
require_once '../libraries/database.php';
require_once '../models/VarietyModel.php';

$dbObj = new Database();
$dbConnection = $dbObj->openConnection();
$userObj = new Variety($dbConnection);

$userList = $userObj->fetchAll();
$fetchtotal = $userObj->fetchTotal();

require_once('../layout/header.php');
// loader
require_once('../layout/loader.php');
?>

<style>
  .odd td a {
    color: blue;
    padding: 3px;
    text-decoration: underline;
  }

  .even td a {
    color: blue;
    padding: 3px;
    text-decoration: underline;
  }

  #content {
    /* Adjust the container styles as needed */
    width: 100%;
  }

  .active>.page-link,
  .page-link.active {
    color: black;
    background-color: gold;
    border-color: black;
  }

  .page-link {
    color: black;
  }

  .table-container {
    max-height: 800px;
    /* Set your desired maximum height */
    overflow-y: auto;
    /* Enable vertical scrolling */
  }

  #example_wrapper {
    display: none;
  }

  h1 {
    font-weight: bolder;
  }

  h2 {
    font-weight: bolder;
  }
</style>




<div class="container-fluid">
  <h1 style="text-align: center; font-family: cursive">
    Variety Graph Details <br>
  </h1>
  <div class="row">
    <div class="col-md-5">
      <div class="table-responsive table-container">
        <table id="example" class="table table-striped custom-table" width="100%">
          <!-- ... table content ... -->

          <thead class="responsive" style="text-align: center;">
            <th></th>
            <th>Week</th>
            <th>Gross Cane</th>
            <th>Net Cane</th>
          </thead>
          <tbody>
            <?php
            $transactionDates = [];
            $grsCaneDailyTotal = [];
            $netCaneDailyTotal = [];


            foreach ($userList as $item) {
              $transactionDates[] = $item['week'];
              $grsCaneDailyTotal[] = $item['total_grscane'];
              $netCaneDailyTotal[] = $item['netcane'];

              echo "<tr>";
              // Added a "View" link in the first column
              echo '<td><a href="variety_details.php?TargetWeek=' . $item['week'] . '">' . "view" . '</a></td>';
              echo '<td>' . $item['week'] . '</td>';
              echo '<td>' . $item['total_grscane'] . '</td>';
              echo '<td>' . $item['netcane'] . '</td>';
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-12">
      <br>
      <button onclick="toggleChartType()">Toggle Chart Type</button>
      <canvas id="myChart" width="400" height="150"></canvas>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart;

        function updateChart(transactionDates, grsCaneDailyTotal, netCaneDailyTotal, chartType) {
          if (myChart) {
            myChart.destroy();
          }

          var chartConfig = {
            type: chartType,
            data: {
              labels: transactionDates.map(week => "" + week),
              datasets: [{
                label: 'Total grscane',
                data: grsCaneDailyTotal,
                backgroundColor: 'red',
                borderColor: 'red',
                borderWidth: 1,
                fill: false
              }, {
                label: 'Total Netcane',
                data: netCaneDailyTotal,
                backgroundColor: 'green',
                borderColor: 'green',
                borderWidth: 1,
                fill: false
              }]
            },
            options: {
              scales: {
                x: {
                  ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 0
                  }
                },
                y: {
                  beginAtZero: true
                }
              }
            }
          };

          myChart = new Chart(ctx, chartConfig);
        }

        $(document).ready(function() {
          var table = $('#example').DataTable({
            "lengthChange": false,
            "searching": false,
            "pageLength": 16,
            "responsive": true,
            "drawCallback": function(settings) {
              var api = this.api();
              var grsCaneDailyTotal = [];
              var netCaneDailyTotal = [];
              var transactionDates = [];

              api.rows({
                page: 'current'
              }).every(function() {
                var data = this.data();
                transactionDates.push(data[1]); // Assuming the week is in the second column
                grsCaneDailyTotal.push(parseFloat(data[2].replace(',', '')) || 0);
                netCaneDailyTotal.push(parseFloat(data[3].replace(',', '')) || 0);
              });

              // Default to line chart
              updateChart(transactionDates, grsCaneDailyTotal, netCaneDailyTotal, 'bar');
            }
          });
        });

        function toggleChartType() {
          var chartType = (myChart.config.type === 'line') ? 'bar' : 'line';

          myChart.config.type = chartType;
          myChart.update();
        }
      </script>
    </div>

    <h1 style="text-align: center; font-family: cursive; color: darkred;">
      <?php
      echo "<h2 style='text-align: center; font-family: cursive; color: dark;'>GROSS_CANE Total: <span style='color: darkred; font-weight: bolder'>" . $fetchtotal['grscane'] . "</span></h2>";
      echo "<h2 style='text-align: center; font-family: cursive; color: dark;'>NET_CANE Total: <span style='color: darkred; font-weight: bolder'>" . $fetchtotal['netcane'] . "</span></h2>";
      ?>

    </h1>
  </div>

  <?php
  require_once('../layout/footer.php');
  ?>