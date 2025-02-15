<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/donationManagement.php";
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

  <link href="../../../app/css/foodDonationManagementUnsigned.css" rel="stylesheet">
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
            <li class="current">Dashboard</li>
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
                <a href="./dashboard.php"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="foodDonationManagement.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Food Donation Management</span></a>
                <a href="foodCenter.php"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="reward.php"><i class="bi bi-arrow-right-circle"></i><span>Reward System</span></a>
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
            <!-- DATA TABLE -->
            <table id="donationDataTable" class="display table table-striped mt-5">
              <thead>
                <tr>
                  <th scope="col">Donor</th>
                  <th scope="col">Date</th>
                  <th scope="col">Title</th>
                  <th scope="col">Description</th>
                  <th scope="col">Pickup Location</th>
                  <th scope="col">Upload Documentation</th>
                  <th scope="col">Status</th>
                  <th scope="col">Remarks</th>
                  <th scope="col" colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $allDonationData = getDonationData($conn);

                if ($allDonationData && $allDonationData->num_rows > 0) {
                    while ($data = $allDonationData->fetch_object()) { 
                      ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data->strDonorName); ?></td>
                            <td><?php echo htmlspecialchars($data->dtmDate); ?></td>
                            <td><?php echo htmlspecialchars($data->strTitle); ?></td>
                            <td><?php echo htmlspecialchars($data->strDescription); ?></td>
                            <td><?php echo htmlspecialchars($data->strPickupLocation); ?></td>
                            <td><?php echo htmlspecialchars(basename($data->strDocFilePath)); ?></td>
                            <td>
                                <?php echo $data->ysnStatus ? "<span class='ysn-true'>Received</span>" : "<span class='ysn-false'>Pending</span>"; ?>
                            </td>
                            <td><?php echo htmlspecialchars($data->strRemarks); ?></td>
                            <td>
                                <a class="btn-edit-donation" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                                  href="javascript:void(0)" data-id="<?php echo $data->intDonationId; ?>">
                                  <i class='bi bi-pencil-square'></i>
                                </a>
                            </td>
                            <td>
                                <a class="btn-delete-donation" href="javascript:void(0)" data-id="<?php echo $data->intDonationId; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }
                }

                $conn->close();
                ?>
              </tbody>
            </table><!-- END DATA TABLE -->
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

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <script src="../../../app/js/donationManagement.js"></script>
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [8, 20, 30, 50, 100]
    });
  });
</script>
</body>

</html>