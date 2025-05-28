<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/requestStatus.php";
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
  <style>
    .table-header {
      background-color: #f8f9fa;
      font-weight: bold;
    }
    .text-green {
      color: green;
    }
    .action-links a {
      margin-right: 10px;
    }
  </style>

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
            <li><a href="requestStatus.php">Request History</a></li>
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
                <a href="requestStatus.php" class="active"><i class="bi bi-clock-history"></i><span>Request History</span></a>
                <a href="availableFoodItem.php"><i class="bi bi-box"></i><span> View Available Food Items</span></a>
                <!-- <a href="RequestHistory.php"><i class="bi bi-clock-history"></i><span> Request History</span></a> -->
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
          <div class="container">

                <div class="card mt-4">
                <div class="card-body">
                    <h2 class="card-title">Request Status Details</h2>
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="requestNo" class="form-label">Request ID</label>
                         <select class="form-select border-warning" name="requestNo" id="requestNo">
                           <option value="" selected>-- Select Request No --</option>
                           <?php
                            $allRequestNo = getAllRequestNo($conn, $user->intBeneficiaryId);

                            while ($row = $allRequestNo->fetch_object()) {
                              ?>
                                <option value="<?= $row->intBeneficiaryRequestId ?>"><?= $row->strRequestNo ?></option>
                              <?php
                            }
                           ?>
                         </select>
                    </div>
                    <div class="col-md-6">
                        <label for="requestDate" class="form-label">Request Date</label>
                        <input type="date" class="form-control border-warning" id="requestDate" placeholder="dd/mm/yyy" readonly>
                    </div>
                    </div>

                </div>

                <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title">Track table</h2>
                    <div class="text-muted small text-end">
                        <div>List of items</div>
                    </div>
                    </div>
                    <hr>
                    <h5>Recent Assistance Details</h5>
                    <div class="table-responsive">
                      <table id="requestTrackDataTable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Request ID</th>
                          <th scope="col">Item</th>
                          <th scope="col">Request Type</th>
                          <th scope="col">Description</th>
                          <th scope="col">Pickup Date</th>
                          <th scope="col">Request Date</th>
                          <th scope="col">Approved</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $beneficiaryRequestData = getAllBeneficiaryRequest($conn, $user->intBeneficiaryId);

                          while ($row = $beneficiaryRequestData->fetch_object()) {
                        ?>
                            <tr>
                              <td><?= htmlspecialchars($row->strRequestNo) ?></td>
                              <td><?= htmlspecialchars($row->strItem) ?></td>
                              <td><?= htmlspecialchars($row->strRequestType) ?></td>
                              <td><?= htmlspecialchars($row->strDescription) ?></td>
                              <td><?= htmlspecialchars($row->dtmPickupDate) ?></td>
                              <td><?= htmlspecialchars($row->dtmCreatedDate) ?></td>
                              <td><?= $row->ysnApproved ? 'Yes' : 'No' ?></td>
                              <td><a href="javascript:void(0)" class="btn-delete-req" data-id="<?= $row->intBeneficiaryRequestId ?>"><i class="bi bi-trash-fill"></i></a></td>
                            </tr>
                        <?php
                          }
                          $conn->close();
                        ?>
                      </tbody>
                      </table>
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

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
  <script src="../../../app/js/requestStatus.js"></script>

</body>

</html>