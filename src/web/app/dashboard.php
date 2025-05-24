<?php
session_start();
include "../../../app/config/db_connection.php";

if (!isset($_SESSION["intUserId"])) {
  header("Location: ../forms/login.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnDonor"]) && $_SESSION["ysnDonor"] == 1) {
  header("Location: ../donor/dashboard.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnNgo"]) && $_SESSION["ysnNgo"] == 1) {
  header("Location: ../ngo/dashboard.php");
}

$user = "SELECT COUNT(*) AS total FROM tbluser";
$resultUser = $conn->query($user);


if ($resultUser && $rowUser = $resultUser->fetch_assoc()) {
    $totalUsers = $rowUser['total'];
}

$donation = "SELECT COUNT(*) AS totalDonation FROM tbldonationmanagement";
$resultDonation = $conn->query($donation);

if ($resultDonation && $rowDonation = $resultDonation->fetch_assoc()) {
$totalDonation = $rowDonation['totalDonation'];
}

$inventory = "SELECT COUNT(*) AS totalInventory FROM tblinventory";
$resultInventory = $conn->query($inventory);

if ($resultInventory && $rowInventory = $resultInventory->fetch_assoc()) {
$totalInventory = $rowInventory['totalInventory'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Dashboard</title>
  <meta name="description" content="">
  <meta name="keywords" content="">


  <!-- Include global stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link href="../../../app/css/dashboard.css" rel="stylesheet">
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
          <li class="current">Admin</li>
           <li><a href="dashboard.php">Dashboard</a></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5">

          <div class="col-lg-3 mt-0" data-aos="fade-up" data-aos-delay="100">

            
            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>
                <a href="volunteerManagement.php" class=""><i class="bi bi-people"></i><span>Volunteer Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <div class="col-lg-9 ps-lg-5 tbl table-donor mt-0" data-aos="fade-up" data-aos-delay="200">

          <!-- DATA GRAPH -->
          <div class="card p-3 shadow-sm">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Request</h6>
                        <h4>45</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Users</h6>
                        <h4><?= htmlspecialchars($totalUsers) ?></h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Donations</h6>
                        <h4><?= htmlspecialchars($totalDonation) ?></h4>        
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Inventory</h6>
                        <h4><?= htmlspecialchars($totalInventory) ?></h4>  
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="card p-3 shadow-sm" style="width: 95%;left: 12px;">
                            <h6 class="text-center">Surplus Food Distribution Status</h6>
                            <div class="chart-container" style="height: 300px;">
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
                        <div class="card p-3 shadow-sm">
                            <h6>Avg, Surplus value</h6>
                            <h4>20k</h4>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm">
                        <h5 class="text-center">Forecasted Surplus Availability</h5>
                        <div class="chart-container" style="height: 400px;">
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

  <!-- Include global footer  -->
  <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Include global JS -->
  <?php include '../global/script.php'; ?>

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Main JS File -->
  <script src="../../../app/js/app.js"></script>
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