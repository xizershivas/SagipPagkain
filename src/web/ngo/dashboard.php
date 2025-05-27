<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";

if (!isset($_SESSION["intUserId"])) {
  header("Location: ../forms/login.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnAdmin"]) && $_SESSION["ysnAdmin"] == 1) {
  header("Location: ../app/dashboard.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnDonor"]) && $_SESSION["ysnDonor"] == 1) {
  header("Location: ../donor/dashboard.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnPartner"]) && $_SESSION["ysnPartner"] == 1) {
  header("Location: ../ngo/dashboard.php");
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


  <!-- Include global stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link href="../../../app/css/dashboardUnsinged.css" rel="stylesheet">
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
          <li class="current">NGO</li>
            <li><a href="dashboard.php">dashboard</a></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5" style=" margin-bottom: 63px;">

          <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <!-- <a href="donate.php" class=""><i class="bi bi-gift"></i><span>Donate</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>
                <a href="foodCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <a href="reward.php"><i class="bi bi-trophy"></i><span>Reward System</span></a> -->
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
          <div class="container mt-5">
        <div class="row g-3">
            <!-- Statistics Cards -->
            <div class="col-md-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Number of Donors</h6>
                        <h2 class="text-primary">300</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Number of Recipients</h6>
                        <h2 class="text-success">400</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Number of Users</h6>
                        <h2 class="text-warning">56</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Donation Trends</h5>
                        <div class="chart-container">
                            <canvas id="donationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
          </div>
          <!-- DATA GRAPH -->
        

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
        // Chart.js setup
        const ctx = document.getElementById('donationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [
                    {
                        label: "Current Week",
                        data: [10, 20, 5, 15, 8, 2, 10],
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: "Previous Week",
                        data: [5, 28, 2, 10, 3, 1, 8],
                        backgroundColor: "rgba(255, 99, 132, 0.5)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Prevents infinite expansion
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>