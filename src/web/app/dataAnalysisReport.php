<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";

$sql = "SELECT 
    DATE_FORMAT(t.dtmCreatedDate, '%Y-%m') AS month,
    t.intFoodBankDetailId,
    f.strFoodBankName,
    t.intItemId,
    i.strItem,
    SUM(t.intQuantity) AS total_quantity
FROM tbltrackdonation t
JOIN tblitem i ON t.intItemId = i.intItemId
JOIN tblfoodbankdetail f ON t.intFoodBankDetailId = f.intFoodBankDetailId
GROUP BY month, t.intFoodBankDetailId, t.intItemId
ORDER BY t.intItemId, t.intFoodBankDetailId, month";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

$uniqueItems = [];
foreach ($data as $row) {
  if (!isset($uniqueItems[$row['intItemId']])) {
    $uniqueItems[$row['intItemId']] = $row['strItem'];
  }
}

echo "<script>const forecastData = " . json_encode($data) . ";</script>";
echo "<script>const uniqueItems = " . json_encode($uniqueItems) . ";</script>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Sagip Pagkain - Demand Forecast</title>
  <?php include '../global/stylesheet.php'; ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link href="../../../app/css/dataAnalysisReport.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="services-details-page">
  <?php include '../global/header.php'; ?>

<main class="main">

  <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        
      </div>
      <nav class="breadcrumbs">
        <div class="container-fluid">
          <ol>
          <li><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Admin'; ?></li>
          <li><a href="dashboard.php">Data Analysis And Reporting</a></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

  <section id="service-details" class="service-details section">
    <div class="container-fluid">
      <div class="row gy-5">
        <!-- Sidebar -->
        <div class="col-lg-3 mt-0" data-aos="fade-up" data-aos-delay="100">
          <div class="service-box">
            <h4>Services List</h4>
            <div class="services-list">
             <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <?php if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnAdmin"]) && $_SESSION["ysnAdmin"] == 1)  { ?>
                  <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <?php } ?>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <!--<a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>-->
                <!-- <a href="volunteerManagement.php" class=""><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php" class="active"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <!--<a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>-->
                <!--<a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>-->
                <!--<a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>-->
            </div>
          </div>

          <div class="help-box d-flex flex-column justify-content-center align-items-center">
            <i class="bi bi-headset help-icon"></i>
            <h4>Have a Question?</h4>
            <p class="d-flex align-items-center mt-2 mb-0">
              <i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span>
            </p>
            <p class="d-flex align-items-center mt-1 mb-0">
              <i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a>
            </p>
          </div>
        </div>

        <!-- Content -->
        <div class="col-lg-9" data-aos="fade-up" data-aos-delay="200">
          <!-- Tabs -->
          <ul class="nav nav-tabs custom-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button style="color: black;" class="nav-link active" id="demand-tab" data-bs-toggle="tab" data-bs-target="#demand" type="button" role="tab" aria-controls="demand" aria-selected="true">
                <i class="fa-solid fa-list" style="color: black;"></i> Food Demand
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button style="color: black;" class="nav-link" id="surplus-tab" data-bs-toggle="tab" data-bs-target="#surplus" type="button" role="tab" aria-controls="surplus" aria-selected="false">
                <i class="fa-solid fa-check-circle" style="color: black;"></i> Food Surplus
              </button>
            </li>
          </ul>

          <!-- Tab Contents -->
          <div class="tab-content" id="myTabContent">
            <!-- Food Demand Tab -->
            <div class="tab-pane fade show active" id="demand" role="tabpanel" aria-labelledby="demand-tab">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Food Demand Forecast</h5>

                  <div class="row align-items-center mb-3">
                    <div class="col-md-10">
                      <label for="itemFilter"><b>Filter by Item:</b></label>
                      <select id="itemFilter" class="form-select mt-1">
                        <option value="all">All Items</option>
                      </select>
                    </div>
                    <div class="col-md-2 text-end">
                      <button type="button" class="btn btn-warning mt-4" data-bs-toggle="modal" data-bs-target="#reportFilterModal">
                        Generate Report
                      </button>
                    </div>
                  </div>

                  <canvas id="forecastChart" width="900" height="400"></canvas>
                  <div id="output" class="mt-4"></div>
                </div>
              </div>
            </div>

            <!-- Food Surplus Tab -->
            <div class="tab-pane fade" id="surplus" role="tabpanel" aria-labelledby="surplus-tab">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Food Surplus</h5>
                    <p class="card-text">
                      This section displays a list of surplus food items currently available in the inventory.
                      Only items with a total quantity greater than 50 units are shown. These items may be
                      redistributed to food banks, charities, or communities in need. Quantities are calculated
                      across all donations and categorized by item and unit.
                      <br><br>
                      <strong>Note:</strong>
                      <ul>
                        <li>To initiate the transfer of inventory, kindly proceed to the Inventory Management section.</li>
                      </ul>
                    </p>
                      <?php

                      $query = "
                        SELECT itm.strItem, unt.strUnit, SUM(inv.intQuantity) AS total_quantity
                        FROM tblinventory inv
                        LEFT JOIN tblitem itm ON inv.intItemId = itm.intItemId
                        LEFT JOIN tblunit unt ON inv.intUnitId = unt.intUnitId
                        GROUP BY itm.strItem, unt.strUnit
                        HAVING total_quantity > 50 ORDER BY total_quantity DESC";

                      $result = mysqli_query($conn, $query);

                      if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-bordered">';
                        echo '<thead><tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                              </tr></thead><tbody>';
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo '<tr>';
                          echo '<td>' . htmlspecialchars($row['strItem']) . '</td>';
                          echo '<td>' . htmlspecialchars($row['total_quantity']) . '</td>';
                          echo '<td>' . htmlspecialchars($row['strUnit']) . '</td>';
                          echo '</tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                      } else {
                        echo '<div class="alert alert-info">No surplus items found above 50 units.</div>';
                      }
                      ?>
                </div>
              </div>
            </div>
          </div> <!-- End tab-content -->
        </div> <!-- End col-lg-9 -->
      </div> <!-- End row -->
    </div> <!-- End container -->
  </section>
</main>


  <!-- Filter Modal -->
<div class="modal fade" id="reportFilterModal" tabindex="-1" aria-labelledby="reportFilterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportFilterModalLabel" style="color:Black;">Generate Admin Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="../../../app/functions/export_report.php">
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="month" class="form-label" style="color:Black;">Month</label>
              <input type="month" class="form-control" name="month" id="month">
            </div>
            <div class="col-md-4">
              <label for="category" class="form-label" style="color:Black;">Category</label>
              <select name="category" id="category" class="form-select">
                <option value="">All Categories</option>
                <?php
                $result = $conn->query("SELECT intCategoryId, strCategory FROM tblcategory");
                while ($row = $result->fetch_assoc()) {
                  echo '<option value="' . $row['intCategoryId'] . '">' . htmlspecialchars($row['strCategory']) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-4">
              <label for="foodbank" class="form-label" style="color:Black;">Food Bank</label>
              <select name="foodbank" id="foodbank" class="form-select">
                <option value="">All Locations</option>
                <?php
                $result = $conn->query("SELECT intFoodBankId, strMunicipality FROM tblfoodbank");
                while ($row = $result->fetch_assoc()) {
                  echo '<option value="' . $row['intFoodBankId'] . '">' . htmlspecialchars($row['strMunicipality']) . '</option>';
                }
                ?>
              </select>
            </div>
             <div class="col-md-3">
              <label for="donor" class="form-label" style="color:Black;">Donor</label>
              <select name="donor" id="donor" class="form-select">
                <option value="">All Donors</option>
                <?php
                $result = $conn->query("SELECT DISTINCT DM.intUserId, U.strFullName 
                  FROM tbldonationmanagement DM
                  JOIN tbluser U ON DM.intUserId = U.intUserId");
                while ($row = $result->fetch_assoc()) {
                  echo '<option value="' . $row['intUserId'] . '">' . htmlspecialchars($row['strFullName']) . '</option>';
                }
                ?>
              </select>
            </div>
          </div>
          <p style="color:Black;"><i>*To generate all the data, simply click the 'Generate Excel Report' button.</i></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Generate Excel Report</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Include global footer  -->
  <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Include global JS -->
  <?php include '../global/script.php'; ?>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <!-- Include Bootstrap JS (via CDN) -->

<!-- Include FontAwesome (for icons) -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
  <script>
    // Your existing groupDataByItemAndLocation function (unchanged)
    function groupDataByItemAndLocation(data) {
      const grouped = {};
      data.forEach(row => {
        const key = `${row.intItemId}-${row.intFoodBankId}`;
        if (!grouped[key]) grouped[key] = {
          description: row.strItem,
          foodbank: row.strMunicipality,
          series: []
        };
        grouped[key].series.push({
          month: row.month,
          quantity: parseInt(row.total_quantity)
        });
      });

      // Sort each series by month (ascending)
      for (const key in grouped) {
        grouped[key].series.sort((a, b) => (a.month > b.month ? 1 : -1));
      }

      return grouped;
    }

    // Your existing forecastDemandForSeries function (unchanged)
    async function forecastDemandForSeries(series) {
      if (series.length < 2) {
        return null;
      }

      const xs = series.map((_, i) => i + 1);
      const ys = series.map(item => item.quantity);

      const inputX = tf.tensor2d(xs, [xs.length, 1]);
      const inputY = tf.tensor2d(ys, [ys.length, 1]);

      const model = tf.sequential();
      model.add(tf.layers.dense({
        units: 1,
        inputShape: [1]
      }));
      model.compile({
        optimizer: 'sgd',
        loss: 'meanSquaredError'
      });

      await model.fit(inputX, inputY, {
        epochs: 300
      });

      const nextX = tf.tensor2d([
        [xs.length + 1]
      ]);
      const prediction = model.predict(nextX);
      const value = await prediction.data();

      return value[0];
    }

    let chartInstance = null;

    async function runForecast(selectedItemId = 'all') {
      const outputDiv = document.getElementById('output');
      outputDiv.innerHTML = '';

      let filteredData = forecastData;
      if (selectedItemId !== 'all') {
        filteredData = forecastData.filter(d => Number(d.intItemId) === selectedItemId);

      }

      const grouped = groupDataByItemAndLocation(filteredData);

      const chartLabels = [];
      const datasets = [];

      let colorIndex = 0;
      const colors = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
      ];

      for (const key in grouped) {
        const group = grouped[key];
        const series = group.series;

        if (series.length < 2) {
          outputDiv.innerHTML += `<p>Not enough data for Item: <b>${group.description}</b> at Food Bank: <b>${group.foodbank}</b></p>`;
          continue;
        }

        const forecastValue = await forecastDemandForSeries(series);
        if (forecastValue === null) continue;

        if (chartLabels.length === 0) {
          series.forEach(s => chartLabels.push(s.month));
          chartLabels.push('Next Month');
        }

        const dataPoints = series.map(s => s.quantity);
        dataPoints.push(forecastValue);

        datasets.push({
          label: `${group.description} @ ${group.foodbank}`,
          data: dataPoints,
          borderColor: colors[colorIndex % colors.length],
          backgroundColor: colors[colorIndex % colors.length],
          fill: false,
          tension: 0.2,
          pointRadius: 4,
          pointHoverRadius: 6,
        });

        colorIndex++;
      }

      if (datasets.length === 0) {
        outputDiv.innerHTML += `<p>No sufficient data found to forecast.</p>`;
      }

      const ctx = document.getElementById('forecastChart').getContext('2d');

      // If chart already exists, destroy before creating new
      if (chartInstance) {
        chartInstance.destroy();
      }

      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: datasets
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            },
            title: {
              display: true,
              text: 'Food Demand Forecast by Item and Food Bank'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Quantity'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Month'
              }
            }
          }
        }
      });
    }

    function populateItemDropdown() {
      const select = document.getElementById('itemFilter');
      for (const [id, name] of Object.entries(uniqueItems)) {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = name;
        select.appendChild(option);
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      populateItemDropdown();
      runForecast();

      document.getElementById('itemFilter').addEventListener('change', (e) => {
        const selectedItemId = e.target.value === 'all' ? 'all' : parseInt(e.target.value);
        runForecast(selectedItemId);
      });
    });
  </script>
</body>

</html>