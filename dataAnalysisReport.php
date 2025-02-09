<?php
include "app/config/db_connection.php";
include "app/functions/user.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Services Details - Append Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="app/css/app.css" rel="stylesheet">
  <link href="app/css/dataAnalysisReport.css" rel="stylesheet">
</head>

<body class="services-details-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <img src="assets/img/sagip-pagkain-logo.JPEG" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
        <div>
          <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
          <h4 class="sitename subtitle" style="padding-left: -10px; letter-spacing: 10.5px;">PAGKAIN</h4>
        </div>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#about">
            <div>
              <h6>Our Role in</h6>
              <h5>FOOD SYSTEMS<span></h5>
            </div>
          </a></li>
          <li><a href="index.php#system">
            <div>
              <h6>Our</h6>
              <h5>APPROACH</h5>
            </div>
          </a></li>
          <li><a href="index.php#stats">
            <div>
              <h5>IMPACT</h5>
            </div>
          </a></li>
          <li><a href="index.php#services">
            <div>
              <h6>About</h6>
              <h6>Sagip pagkain</h6>
            </div>
          </a></li>
          <li><a href="index.php#recent-posts">
            <div>
              <h5>COMMUNITY-LED</h5>
            </div>
          </a></li>
          <li><a href="index.php#contact">
            <div>
            <h5>OUR SUPPORT</h5>
          </div>
          </a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="dropdown">
          <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; font-size: 24px; color: #ffffff;">
            &#x22EE;
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background: rgba(255, 255, 255, 0.3); border: none;">
          <li><a class="dropdown-item" href="index.php#donate" style="color: white;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='white'">DONATE</a></li>
          <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="window.location.href='logout.php'" style="color: white;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='white'">Logout</a></li>
          </ul>
        </div>
      </div>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        
      </div>
      <nav class="breadcrumbs">
        <div class="container-fluid">
          <ol>
            <li><a href="">Donor Management</a></li>
            <li class="current">Food Donation Management</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-arrow-right-circle"></i><span>Food Donation Management</span></a>
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

             <div class="col-lg-8 ps-lg-5 tbl grid-report">
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
                                    <div id="map" style="height: 350px;"></div>
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

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.php" class="logo d-flex align-items-center">
            <span class="sitename">Sagip pagkain</span>
          </a>
          <p>Dummy footer</p>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>


      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="sitename">Sagip pagkain</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <!-- Main JS File -->
  <script src="app/js/app.js"></script>
  <script src="app/js/user.js"></script>
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

        var map = L.map('map').setView([14.676, 121.044], 10); // Adjust coordinates as needed
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    </script>
</body>

</html>