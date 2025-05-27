<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
$userData;
$user;
if (isset($_SESSION["intUserId"])) {
  $userData = getUserData($conn, $_SESSION["intUserId"]);
  $user = $userData->fetch_object();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Donation Management</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

 <!-- Include global stylesheet -->
 <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <link href="../../../app/css/donationManagement.css" rel="stylesheet">
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
            <li class="current">Beneficiary</li>
            <li><a href="availableFoodItem.php">View Available food items</a></li>
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
              <a href="assistanceRequest.php"><i class="bi bi-pencil-square"></i><span>Request for Assistance</span></a>
                <a href="requestStatus.php"><i class="bi bi-search"></i><span> Track Available Status</span></a>
                <a href="availableFoodItem.php"><i class="bi bi-box"></i><span> View Available Food Items</span></a>
                <a href="RequestHistory.php" class="active"><i class="bi bi-clock-history"></i><span> Request History</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <div class="col-lg-9 tbl table-donor pe-2 mt-0" data-aos="fade-up" data-aos-delay="200">
           
            <div class="container" style="margin-bottom: 25px;">

                <!-- Request History Card -->
                <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Request History</h2>
                    <div class="mb-3">
                    <label for="pastRequest" class="form-label">Past Requests</label>
                    <input type="text" class="form-control border-warning" id="pastRequest" placeholder="dd/mm/yyy">
                    </div>
                    <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-header">
                        <tr>
                            <th>Request ID</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>REQ-001</td>
                            <td>2024-03-15</td>
                            <td>Canned Goods, Rice</td>
                            <td class="text-green">Completed</td>
                            <td class="action-links">
                            <a href="#" class="text-primary">View</a>
                            <a href="#" class="text-info">Edit</a>
                            <a href="#" class="text-danger">Delete</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>

                <!-- Assistance History Card -->
                <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title">Assistance History</h2>
                    <div class="text-muted small text-end">
                        <div>Last Assistance</div>
                        <div>March 15, 2024</div>
                    </div>
                    </div>
                    <div class="mt-3">
                    <p class="mb-1">Total Assistance Received</p>
                    <h4><a href="#">12</a></h4>
                    </div>
                    <hr>
                    <h5>Recent Assistance Details</h5>
                    <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Food Package #12</strong><br>
                        <small class="text-muted">March 15, 2024</small>
                        <div class="text-green">Completed</div>
                    </li>
                    <li>
                        <strong>Food Package #11</strong><br>
                        <small class="text-muted">February 28, 2024</small>
                        <div class="text-green">Completed</div>
                    </li>
                    </ul>
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

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <script src="../../../app/js/formValidation.js"></script>
  <script src="../../../app/js/donationManagement.js"></script>
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [10, 20, 30, 50, 100]
    });
  });

  $(document).ready(function() {
    new DataTable('#archiveDataTable', {

      lengthMenu: [5, 10]
    });
  });
</script>

</body>

</html>