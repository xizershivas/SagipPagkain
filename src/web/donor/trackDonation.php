<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/trackDonation.php";

$intUserId = intval($_SESSION["intUserId"]);
$allTrackDonationData = getAllTrackDonationData($conn, $intUserId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Track Donation</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

   <!-- Include stylesheet -->
   <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="../../../app/css/app.css" rel="stylesheet">
  <style>
    #trackDonationTable td {
      width: 13%;
    }
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
            <li class="current"><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Donor'; ?></li>
            <li><a href="trackDonation.php">Track Donation</a></li>
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
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="trackDonation.php" class="active"><i class="bi bi-trophy"></i><span>Track Donation</span></a>
                <!-- <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a> -->
                <a href="donate.php" class=""><i class="bi bi-gift"></i><span>Donate</span></a>
                <!--<a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>-->
                <a href="foodBankCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <!--<a href="reward.php"><i class="bi bi-trophy"></i><span>Reward System</span></a>-->
                <!-- <a href="inventoryManagement.php"><i class="bi bi-trophy"></i><span>Inventory Management</span></a> -->
                <!-- <a href="requestApproval.php"><i class="bi bi-trophy"></i><span>Requests for Approval</span></a> -->
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
              <!-- <h2 class="text-black">Process Beneficiary</h2> -->
              <!-- TRACK DONATION TABLE -->
              <!--<table id="trackDonationTable" class="display table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Donor</th>
                    <th scope="col">Food Bank</th>
                    <th scope="col">Item</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Unit</th>
                    <th scope="col" class="text-nowrap">Send Qty</th>
                    <th scope="col">Beneficiary</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="col">
                      <input class="form-control" list="donorOptions" name="donorItem" id="donorItem" placeholder="-- Select Donor --" required>
                      <datalist id="donorOptions">
                      </datalist>
                    </td>
                    <td class="col">
                      <input class="form-control" list="foodBankOptions" name="foodBankItem" id="foodBankItem" required>
                      <datalist id="foodBankOptions">
                      </datalist>
                    </td>
                    <td class="col">
                      <select class="form-select" name="itemSelect" id="itemSelect">
                        <option value="">-- Select Item --</option>
                      </select>
                    </td>
                    <td class="col">
                      <input class="form-control" type="text" name="itemQty" id="itemQty" value="0" readonly>
                    </td>
                    <td class="col">
                      <input class="form-control" type="text" name="unit" id="unit" readonly>
                    </td>
                    <td class="col">
                      <input class="form-control" type="number" name="itemSendQty" id="itemSendQty" value="0" min="1">
                    </td>
                    <td class="col">
                      <input class="form-control" list="beneficiaryOptions" name="beneficiaryItem" id="beneficiaryItem" placeholder="-- Select Beneficiary --" required>
                      <datalist id="beneficiaryOptions">
                      </datalist>
                    </td>
                    <td class="col">
                      <select class="form-select" name="status" id="statusSelect" aria-label="Status Options">
                        <option value="">-- Set Status --</option>
                        <option value="1">Received</option>
                        <option value="0">In Transit</option>
                      </select>
                    </td>
                    <td title="Save">
                      <a href="javascript:void(0)" id="btnSave">
                        <i class="bi bi-floppy-fill fs-4 text-success"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table> --><!-- END TRACK DONATION TABLE -->

              <!-- DONATION PROCESSED TABLE -->
              <h2 class="text-black pt-3">Processed Donations</h2>
                <table id="trackDonationDataTable" class="display table table-striped">
                  <thead>
                    <tr>
                      <th class="col">Donation No</th>
                      <th class="col">Donor</th>
                      <th class="col">Food Bank</th>
                      <th class="col">Item</th>
                      <th class="col">Received Qty.</th>
                      <th class="col">Unit</th>
                      <th class="col">Beneficiary</th>
                      <th class="col">Status</th>
                      <th class="col">Date</th>
                      <th class="col">QR Code</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- DISPLAY TRACK DONATION DATA HERE -->
                    <?php
                      $ctr = 1;

                      while($data = $allTrackDonationData->fetch_object()) {
                        ?>
                        <tr>
                          <td><?php echo $data->strTrackDonationNo; ?></td>
                          <td><?php echo $data->strFullName; ?></td>
                          <td><?php echo $data->strFoodBankName; ?></td>
                          <td><?php echo $data->strItem; ?></td>
                          <td><?php echo $data->intQuantity; ?></td>
                          <td><?php echo $data->strUnit; ?></td>
                          <td><?php echo $data->strName; ?></td>
                          <td><?php echo $data->ysnStatus == 1 ? 'Received' : ''; ?></td>
                          <td><?php echo $data->dtmCreatedDate; ?></td>
                          <td>
                            <?php if (!empty($data->strQRCode)) { ?>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#QRImage-<?php echo $ctr; ?>">
                              View QR Code
                            </button>

                            <!-- QR CODE MODAL -->
                            <div class="modal fade" id="QRImage-<?php echo $ctr; ?>" tabindex="-1" aria-labelledby="QRImageLabel-<?php echo $ctr; ?>" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content text-center">
                                  <div class="modal-header border-0 pb-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body pt-0">
                                    <img src="<?php echo "../" . $data->strQRCode; ?>" alt="">
                                    <a class="d-block link-primary" href="<?php echo "../" . $data->strQRCode; ?>" download>Download QR Code</a>
                                  </div>
                                  <!-- <div class="modal-footer"> -->
                                  <!-- </div> -->
                                </div>
                              </div>
                            </div><!-- END QR CODE MODAL -->
                            <?php } ?>

                          </td>
                        </tr>
                        <?php
                        $ctr++;
                      }
                    ?>
                  </tbody>
                </table><!-- END DONATION PROCESSED TABLE -->

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

    <!-- Include footer -->
    <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Include JS -->
  <?php include '../global/script.php'; ?>

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <!-- Main JS File -->
  <script src="../../../app/js/app.js"></script>
  <script src="../../../app/js/trackDonation.js"></script>
  <script>
    $(document).ready(function() {
    new DataTable('#trackDonationDataTable', {

      lengthMenu: [5, 10, 20, 30, 50, 100]
    });
  });
  </script>
</body>

</html>