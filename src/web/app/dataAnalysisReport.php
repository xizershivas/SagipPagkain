<?php
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Services Details - Append Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Include global stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <link href="../../../app/css/dataAnalysisReport.css" rel="stylesheet">
</head>

<body class="services-details-page">

  <!-- Include Header -->
  <?php include '../global/header.php'; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        
      </div>
      <nav class="breadcrumbs">
        <div class="container-fluid">
          <ol>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">Data Analysis And Reporting</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5">

          <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">

            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-arrow-right-circle"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-arrow-right-circle"></i><span>Donation Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Data Analysis And Reporting</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

             <div class="col-lg-9 ps-lg-5 tbl grid-report">
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-md-3 bg-light p-3">
                            <h5>Total Donations Received</h5>
                            <select class="form-select">
                                <option>All</option>
                            </select>
                            <h5 class="mt-3">Beneficiaries</h5>
                            <select class="form-select">
                                <option>Individual</option>
                            </select>
                            <h5 class="mt-3">Municipalities</h5>
                            <select class="form-select">
                                <option>Santa Maria</option>
                            </select>
                            <h5 class="mt-3">Barangay</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked> Bagong Pook
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked> Coralan
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked> Calangay
                            </div>
                            <h5 class="mt-3">Forecast</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"> 2 years
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"> 3 years
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"> 4 years
                            </div>
                        </div>
                        
                        <!-- Main Content -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <canvas id="distributionChart" style="max-width: 400px;max-height: 350px;display: block;box-sizing: border-box;height: 350px;width: 350px;"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <div id="map"></div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <canvas id="demandChart"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="foodChart"></canvas>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

  <!-- Include global footer  -->
  <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Include global JS -->
  <?php include '../global/script.php'; ?>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <!-- Main JS File -->
  <script src="../../../app/js/app.js"></script>
  <script src="../../../app/js/user.js"></script>
  <script>
        const ctx1 = document.getElementById('distributionChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Bagong Pook', 'Coralan', 'Calangay', 'Cabuoan', 'Kayhacat'],
                datasets: [{
                    data: [90, 60, 85, 80, 95],
                    backgroundColor: ['yellow', 'blue', 'red', 'green', 'purple']
                }]
            }
        });

        const ctx2 = document.getElementById('demandChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', '2024', '2025'],
                datasets: [{
                    label: 'Predicted Demand',
                    data: [10, 20, 30, 40, 35, 25, 30],
                    borderColor: 'red',
                    fill: false
                }]
            }
        });

        const ctx3 = document.getElementById('foodChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Vegetables', 'Canned Goods', 'Surplus Food', 'Cooked Meals', 'Fish'],
                datasets: [{
                    label: 'Food Type Contributions',
                    data: [100, 40, 20, 10, 100],
                    backgroundColor: 'orange'
                }]
            }
        });
    </script>
        <script>
        // Initialize the map
        var map = L.map('map').setView([14.5995, 120.9842], 12); // Default: Manila, PH

        // Add OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Define locations with anchor links
        var locations = [
            { name: "Manila", coords: [14.5995, 120.9842], url: "https://en.wikipedia.org/wiki/Manila" },
            { name: "Quezon City", coords: [14.6760, 121.0437], url: "https://en.wikipedia.org/wiki/Quezon_City" },
            { name: "Makati", coords: [14.5547, 121.0244], url: "https://en.wikipedia.org/wiki/Makati" }
        ];

        // Add markers with clickable links
        locations.forEach(function(location) {
            L.marker(location.coords).addTo(map)
                .bindPopup(`<a href="${location.url}" target="_blank">${location.name}</a>`);
        });
    </script>
</body>

</html>