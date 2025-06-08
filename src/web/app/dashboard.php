<?php
session_start();
include "../../../app/config/db_connection.php";

if (!isset($_SESSION["intUserId"])) {
  header("Location: ../forms/login.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnDonor"]) && $_SESSION["ysnDonor"] == 1) {
  header("Location: ../donor/dashboard.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnPartner"]) && $_SESSION["ysnPartner"] == 1) {
  header("Location: ../ngo/dashboard.php");
} else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnBeneficiary"]) && $_SESSION["ysnBeneficiary"] == 1) {
  header("Location: ../beneficiary/assistanceRequest.php");
}

$request = "SELECT COUNT(*) AS total FROM tbldonationmanagement 
                                     WHERE ysnStatus = 0";
$resultRequest = $conn->query($request);


if ($resultRequest && $rowRequest = $resultRequest->fetch_assoc()) {
    $totalRequest = $rowRequest['total'];
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

$forecastedAvailability = "
    SELECT 
        MONTHNAME(dtmCreatedDate) AS MonthName, 
        CONCAT(MONTHNAME(dtmCreatedDate), ' ', YEAR(dtmCreatedDate)) AS Label,
        SUM(intQuantity) AS TotalQuantity 
    FROM tbltrackdonation 
    GROUP BY MONTH(dtmCreatedDate), YEAR(dtmCreatedDate)
    ORDER BY YEAR(dtmCreatedDate), MONTH(dtmCreatedDate)
";

$resultForecastedAvailability = $conn->query($forecastedAvailability);

$labels = [];
$data = [];

if ($resultForecastedAvailability) {
    while ($row = $resultForecastedAvailability->fetch_assoc()) {
        $labels[] = $row['Label'];
        $data[] = $row['TotalQuantity'];
    }
}

$jsonLabels = json_encode($labels);
$jsonData = json_encode($data);

$sqlSurplus = "
    SELECT 
        intPurposeId,
        COUNT(ysnStatus) AS count,
        ROUND(COUNT(ysnStatus) * 100.0 / (
            SELECT COUNT(ysnStatus) 
            FROM tbldonationmanagement 
            WHERE ysnStatus = 1
        ), 2) AS percentage
    FROM 
        tbldonationmanagement
    WHERE 
        ysnStatus = 1
    GROUP BY 
        intPurposeId
";

$resultSurplus = $conn->query($sqlSurplus);

// Prepare arrays for Chart.js
$labelSurplus = [];
$dataSurplus = [];

while ($rowSurplus = $resultSurplus->fetch_assoc()) {
  $labelSurplus[] = $rowSurplus['intPurposeId'] . ' %' . $rowSurplus['percentage'];
    $dataSurplus[] = $rowSurplus['percentage'];
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
          <li><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Admin'; ?></li>
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
                <?php if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnAdmin"]) && $_SESSION["ysnAdmin"] == 1)  { ?>
                  <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <?php } ?>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <!--<a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>-->
                <!-- <a href="volunteerManagement.php" class=""><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <!--<a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>-->
                <!--<a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>-->
                <!--<a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>-->
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
                        <h4><?= htmlspecialchars($totalRequest) ?></h4>
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
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm">
                        <h5 class="text-center">Forecasted Surplus Availability</h5>
                        <div class="chart-container" style="height: 305px;">
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
                labels: <?php echo json_encode($labelSurplus); ?>,
                datasets: [{
                    data: <?php echo json_encode($dataSurplus); ?>,
                    backgroundColor: ['purple', 'orange', 'red', 'green', 'blue'] // Add more if needed
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Forecasted Surplus Availability
        const forecastCtx = document.getElementById('forecastedSurplusChart').getContext('2d');
          new Chart(forecastCtx, {
              type: 'line',
              data: {
                  labels: <?php echo $jsonLabels; ?>,
                  datasets: [{
                      label: 'Forecasted Surplus Availability',
                      data: <?php echo $jsonData; ?>,
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