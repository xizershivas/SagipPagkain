<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/manageBeneficiary.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Manage Beneficiaries</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

 <!-- Include global stylesheet -->
 <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" /> -->
  <link href="../../../app/css/manageBeneficiary.css" rel="stylesheet">
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
            <li><?php echo isset($_SESSION['ysnFoodBank']) && $_SESSION['ysnFoodBank'] == 1 ? 'Food Bank' : 'Food Bank'; ?></li>
            <li><a href="manageBeneficiary.php">Manage Beneficiaries</a></li>
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
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <!-- <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a> -->
                <!-- <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a> -->
                <!-- <a href="manageBeneficiary.php" class="active"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a> -->
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
                <a href="requestApproval.php"><i class="bi bi-trophy"></i><span>Requests for Approval</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <!-- ADD BENEFICIARY FORM (HIDDEN) -->
          <div class="modal fade" id="modalFrmAddBeneficiary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black mb-3 bg-light p-3">
                  <form class="" id="frmAddBeneficiary">
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="name" id="name" required>
                          <label for="name" class="form-label">Beneficiary's Full Name</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="form-floating">
                          <input type="email" class="form-control" name="email" id="email">
                          <label for="email" class="form-label">Email Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="contact" class="form-label fw-bold">Contact</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="contact" id="contact">
                          <label for="contact" class="form-label">(Mobile No. / Phone No.)</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="address" id="address">
                          <label for="address" class="form-label">Complete Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="salary" class="form-label fw-bold">Monthly Income</label>
                        <div class="form-floating">
                          <input type="number" class="form-control" name="salary" id="salary" step="0.01">
                          <label for="salary" class="form-label">Monthly Income</label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmAddBeneficiary">Save</button>
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END ADD BENEFICIARY FORM-->

          <!-- BENEFICIARY FORM (HIDDEN) -->
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black mb-3 bg-light p-3" id="frmEditBeneficiary">
                  <form class="" id="frmBeneficiary">
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="name" id="name" required>
                          <label for="name" class="form-label">Beneficiary's Full Name</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="form-floating">
                          <input type="email" class="form-control" name="email" id="email">
                          <label for="email" class="form-label">Email Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="contact" class="form-label fw-bold">Contact</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="contact" id="contact">
                          <label for="contact" class="form-label">(Mobile No. / Phone No.)</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="address" id="address">
                          <label for="address" class="form-label">Complete Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="salary" class="form-label fw-bold">Monthly Income</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="salary" id="salary">
                          <label for="salary" class="form-label">Monthly Income</label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmBeneficiary">Save</button>
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END BENEFICIARY FORM -->
          
          <div class="col-lg-9 tbl table-donor pe-2 mt-0" data-aos="fade-up" data-aos-delay="200">
            <div class="row justify-content-center">
              <button type="button" class="btn btn-success w-25" id="btnAddBeneficiary" data-bs-toggle="modal" data-bs-target="#modalFrmAddBeneficiary">Add Beneficiary</button>
            </div>
            <!-- DATA TABLE -->
            <table id="donationDataTable" class="display table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Address</th>
                  <th scope="col">Salary</th>
                  <th scope="col" colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $allBeneficiaryData = getBeneficiaryData($conn);
                $rowNumber = 1;
                if ($allBeneficiaryData && $allBeneficiaryData->num_rows > 0) {
                    while ($data = $allBeneficiaryData->fetch_object()) { 
                      ?>
                        <tr>
                            <td><?php echo $rowNumber; ?></td>
                            <td><?php echo htmlspecialchars($data->strName); ?></td>
                            <td><?php echo htmlspecialchars($data->strEmail); ?></td>
                            <td><?php echo htmlspecialchars($data->strContact); ?></td>
                            <td><?php echo htmlspecialchars($data->strAddress); ?></td>
                            <td><?php echo htmlspecialchars($data->dblSalary); ?></td>
                            <td>
                                <a class="btn-edit-beneficiary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                                  href="javascript:void(0)" data-id="<?php echo $data->intBeneficiaryId; ?>">
                                  <i class='bi bi-pencil-square'></i>
                                </a>
                            </td>
                            <td>
                                <a class="btn-delete-beneficiary" href="javascript:void(0)" data-id="<?php echo $data->intBeneficiaryId; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php 
                      $rowNumber++; 
                    }
                }

                $conn->close();
                ?>
              </tbody>
            </table><!-- END DATA TABLE -->
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

  <script src="../../../app/js/manageBeneficiary.js"></script>
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [10, 20, 30, 50, 100]
    });
  });
</script>

</body>

</html>