<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/requestApproval.php";
// $userData;
// $user;
// if (isset($_SESSION["intUserId"])) {
//   $userData = getUserData($conn, $_SESSION["intUserId"]);
//   $user = $userData->fetch_object();
// }
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
            <li class="current">Food Bank</li>
            <li><a href="availableFoodItem.php">Requests for Approval</a></li>
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
                <div class="services-list">
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
                <a href="requestApproval.php" class="active"><i class="bi bi-trophy"></i><span>Requests for Approval</span></a>
              </div>
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
                    <div class="card mb-4">
                      <div class="card-body">
                        <h2 class="card-title">Beneficiary Requests for Approval</h2>
                        <div class="mb-3">

                          <!-- REQUEST APPROVAL TABLE -->
                          <table id="requestApprovalDataTable" class="display table table-striped">
                            <thead class="text-nowrap">
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Beneficiary</th>
                                <th scope="col">Request No</th>
                                <th scope="col">Request Type</th>
                                <th scope="col">Urgency Level</th>
                                <th scope="col">Pickup Date</th>
                                <th scope="col">Document</th>
                                <th scope="col">Description</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Approval Status</th>
                                <th scope="col">Purpose</th>
                                <th scope="col">Request Date</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                            $allBeneficiaryRequests = getAllBeneficiaryRequests($conn);

                            while ($row = $allBeneficiaryRequests->fetch_object()) {
                            ?>
                                <tr>
                                <td><?= htmlspecialchars($row->intBeneficiaryRequestId) ?></td>
                                <td><?= htmlspecialchars($row->strName) ?></td>
                                <td><?= htmlspecialchars($row->strRequestNo) ?></td>
                                <td><?= htmlspecialchars($row->strRequestType) ?></td>
                                <td><?= htmlspecialchars($row->strUrgencyLevel) ?></td>
                                <td><?= htmlspecialchars($row->dtmPickupDate) ?></td>
                                <td><?= htmlspecialchars($row->strDocument) ?></td>
                                <td><?= htmlspecialchars($row->strDescription) ?></td>
                                <td><?= htmlspecialchars($row->strNotes) ?></td>
                                <td>
                                    <?= ($row->intApproved == 0 && $row->ysnSubmitted == 1) ? "Waiting for Approval" : ""?>
                                </td>
                                <td><?= htmlspecialchars($row->strPurpose) ?></td>
                                <td><?= htmlspecialchars($row->dtmCreatedDate) ?></td>
                                <td>
                                    <?php if ($row->intApproved == 0 && $row->ysnSubmitted) { ?>
                                    <a class="btn btn-success btn-sm btn-approve-req" href="javascript:void(0)" data-id="<?= $row->intBeneficiaryRequestId ?>">
                                        Approve Request
                                    </a>
                                    <?php } ?>
                                </td>
                                </tr>
                            <?php
                            }
                            $conn->close();
                            ?>
                            </tbody>
                          </table>
                          <!-- END REQUEST APPROVAL TABLE -->

                        </div>
                      </div>
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
  <script src="../../../app/js/requestApproval.js"></script>
  <script>
    $(document).ready(function() {
      new DataTable('#requestApprovalDataTable', {

        lengthMenu: [5, 10, 25, 50, 100]
      });
    });
  </script>

</body>

</html>