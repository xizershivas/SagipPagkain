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

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
  <!-- Include stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link href="../../../app/css/foodCenter.css" rel="stylesheet">

  <style>
        #map { height: 420px; }
    </style>
</head>

<body class="services-details-page">

   <!-- Include header -->
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
            <li class="current">Food Bank Center</li>
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
                <a href="volunteerManagement.php" class=""><i class="bi bi-arrow-right-circle"></i><span>Volunteer Management</span></a>
                <a href="foodBankCenter.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-arrow-right-circle"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-arrow-right-circle"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-arrow-right-circle"></i><span>Manage Beneficiaries</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <div class="col-lg-9 ps-lg-5 tbl table-donor" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-center" style="color: #333;">Item Stock Map</h2>
            <!-- DATA GRAPH -->
            <div class="card p-3 shadow-sm">
              <div id="map"></div>
            </div>
            <!-- END MAP GRAPH> -->
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
  <script>
    // Initialize map
    var map = L.map('map').setView([14.5995, 120.9842], 10); // Center on Philippines

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Sample data (ID, Location, Latitude, Longitude, Stock)
    var stockLocations = [
        { id: 1, location: "Rizal", lat: 14.599512, lng: 121.036079, stock: 20 },
        { id: 2, location: "Manila", lat: 14.6091, lng: 120.9822, stock: 5 },
        { id: 3, location: "Quezon City", lat: 14.6760, lng: 121.0437, stock: 50 }
    ];

    // Function to determine marker color based on stock
    function getColor(stock) {
        return stock > 30 ? "green" : stock > 10 ? "orange" : "red";
    }

    // Loop through locations and add markers
    stockLocations.forEach(function (data) {
        var marker = L.circleMarker([data.lat, data.lng], {
            color: getColor(data.stock),
            radius: 10,
            fillOpacity: 0.8
        }).addTo(map);

        // Popup info
        marker.bindPopup(`<b>${data.location}</b><br>Stock: ${data.stock}`);
    });
</script>
</body>

</html>