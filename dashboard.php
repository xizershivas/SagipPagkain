<?php
session_start();
include "app/config/db_connection.php";
include "app/functions/user.php";

if (!isset($_SESSION["intUserId"]) || $_SESSION["ysnAdmin"] != 1) {
  header("Location: login.php");
  exit();
}
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

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="app/css/app.css" rel="stylesheet">
  <link href="app/css/dashboard.css" rel="stylesheet">
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
              <h5><span>FOOD SYSTEMS</span></h5>
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
            <li class="current">Dashboard</li>
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
                <a href="dashboard.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-arrow-right-circle"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-arrow-right-circle"></i><span>Donation Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-arrow-right-circle"></i><span>Data Analysis And Reporting</span></a>
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

          <!-- DATA GRAPH -->
          <div class="card p-3 shadow-sm">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Partner Establishments</h6>
                        <h4>45</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Surplus Items</h6>
                        <h4>53</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Surplus Items</h6>
                        <h4>53</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Food Donated</h6>
                        <h4>50</h4>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="card p-3 shadow-sm" style="width: 95%;left: 12px;">
                            <h6 class="text-center">Surplus Food Distribution Status</h6>
                            <div class="chart-container" style="height: 150px;">
                                <canvas id="surplusDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 mb-6" style="margin-top: 10px;">
                        <div class="card p-3 shadow-sm">
                            <h6>Avg, Redistribution</h6>
                            <h4>5,125 min(s)</h4>
                        </div>
                    </div>
                    <div class="col-md-6 mb-6" style="margin-top: 10px;">
                        <div class="card p-3 shadow-sm" style="height: 123px;">
                            <h6>Avg, Surplus value</h6>
                            <h4>20k</h4>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm">
                        <h5 class="text-center">Forecasted Surplus Availability</h5>
                        <div class="chart-container" style="height: 280px;">
                            <canvas id="forecastedSurplusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
          <!-- END DATA GRAPH> -->
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

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Main JS File -->
  <script src="app/js/app.js"></script>
  <script>
        // Surplus Food Distribution Status
        const surplusCtx = document.getElementById('surplusDistributionChart').getContext('2d');
        new Chart(surplusCtx, {
            type: 'pie',
            data: {
                labels: ['46.47%', '23.06%', '20.77%', '9.7%'],
                datasets: [{
                    data: [46.47, 23.06, 20.77, 9.7],
                    backgroundColor: ['purple', 'orange', 'red', 'green']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Forecasted Surplus Availability
        const forecastCtx = document.getElementById('forecastedSurplusChart').getContext('2d');
        new Chart(forecastCtx, {
            type: 'line',
            data: {
                labels: ['Jul 2024', 'Aug 2024', 'Sep 2024', 'Oct 2024', 'Nov 2024', 'Dec 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025'],
                datasets: [{
                    label: 'Forecasted Surplus Availability',
                    data: [266, 8686, 3750, 11890, 15030, 17990, 21535, 24660, 30150, 37930],
                    borderColor: 'blue',
                    fill: true,
                    backgroundColor: 'rgba(0, 0, 255, 0.2)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>

</body>

</html>