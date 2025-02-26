<?php
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Food Bank Center</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Data Table CSS CDN -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


    
  <!-- Include stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link href="../../../app/css/foodCenter.css" rel="stylesheet">
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
            <li class="current">Admin</li>
            <li><a href="foodBankCenter.php">Food Bank Center</a></li>
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
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="donate.php" class=""><i class="bi bi-gift"></i><span>Donate</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="foodBankCenter.php" class="active"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="reward.php" class=""><i class="bi bi-trophy"></i><span>Reward System</span></a>
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
              <div class="d-flex"> 
                <div class="col-lg-9"> 
                   <div id="map"></div>
                </div>
                <div class="col-lg-3"> 
                    <div id="sidebar">
                        <h3 class="text-center mb-3">Food Stock Areas</h3>

                        <!-- Search Bar -->
                        <div id="search-container">
                            <input type="text" id="searchBox" class="form-control" placeholder="Search for a location..." onkeyup="filterLocations()">
                        </div>

                        <ul class="list-group" id="locationList"></ul>
                    </div>
                    </div>
              </div>

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
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
        // Initialize the map and set to Laguna
        var map = L.map('map').setView([14.2044, 121.3473], 10);

        // Light-themed map (OpenStreetMap Standard)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Stock level colors
        function getStockColor(stock) {
            if (stock >= 80) return "red";    
            if (stock >= 30) return "yellow"; 
            return "green";                   
        }

        // Dummy locations with stock details
        var locations = [
            { name: "Pagsanjan Falls", lat: 14.1871, lng: 121.2423, stock: 90, branch: "FoodBanking Pagsanjan", contact: "0912-345-6789" },
            { name: "Enchanted Kingdom", lat: 14.2786, lng: 121.4107, stock: 40, branch: "FoodBanking EK", contact: "0921-234-5678" },
            { name: "Mount Makiling", lat: 14.1761, lng: 121.5491, stock: 10, branch: "FoodBanking Makiling", contact: "0933-567-8901" },
            { name: "Nuvali", lat: 14.2166, lng: 121.0573, stock: 85, branch: "FoodBanking Nuvali", contact: "0905-678-1234" },
            { name: "UPLB", lat: 14.3041, lng: 121.3769, stock: 35, branch: "FoodBanking UPLB", contact: "0917-890-4567" },
            { name: "Nagcarlan Cemetery", lat: 14.1816, lng: 121.4916, stock: 5, branch: "FoodBanking Nagcarlan", contact: "0998-765-4321" },
            { name: "Lake Pandin", lat: 14.2639, lng: 121.3645, stock: 95, branch: "FoodBanking Pandin", contact: "0916-543-2109" }
        ];

        // Add markers and populate the location list
        var locationList = document.getElementById("locationList");

        locations.forEach(location => {
            var marker = L.marker([location.lat, location.lng]).addTo(map);
            marker.bindPopup(`
                <div class="text-left">
                    <h6 class="mb-1"><b>${location.branch}</b></h6>
                    <p class="mb-1">📍 <strong>${location.name}</strong></p>
                    <p class="mb-1">📦 Stock Quantity: <strong>${location.stock} kg</strong></p>
                    <p class="mb-1">📞 Contact: <strong>${location.contact}</strong></p>
                </div>
            `);

            // Add locations to the list with pin icon
            var listItem = document.createElement("li");
            listItem.className = "list-group-item";
            listItem.innerHTML = `<span class="pin-icon">📍</span> ${location.name}`;
            listItem.onclick = function () {
                zoomToLocation(location.lat, location.lng);
            };
            locationList.appendChild(listItem);

            // Add a colored circle to indicate stock level
            L.circle([location.lat, location.lng], {
                color: getStockColor(location.stock),
                fillColor: getStockColor(location.stock),
                fillOpacity: 0.5,
                radius: 300
            }).addTo(map);
        });

        // Function to zoom into a location
        function zoomToLocation(lat, lng) {
            map.setView([lat, lng], 13);
        }

        // Search bar functionality: filters locations in the list
        function filterLocations() {
            var input = document.getElementById("searchBox").value.toLowerCase();
            var listItems = document.querySelectorAll(".list-group-item");

            listItems.forEach(item => {
                if (item.textContent.toLowerCase().includes(input)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>