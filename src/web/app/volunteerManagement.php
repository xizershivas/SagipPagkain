<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/volunteerManagement.php";
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

  <!-- <link href="../../../app/css/donationManagement.css" rel="stylesheet"> -->
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">Volunteer Management</li>
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
                <a href="dashboard.php"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-arrow-right-circle"></i><span>User Management</span></a>
                <a href="donationManagement.php" class=""><i class="bi bi-arrow-right-circle"></i><span>Donation Management</span></a>
                <a href="volunteerManagement.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Volunteer Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-arrow-right-circle"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-arrow-right-circle"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-arrow-right-circle"></i><span>Manage Beneficiaries</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <!-- DONATION FORM (HIDDEN) -->
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black mb-3 bg-light p-3" id="frmEditDonation">
                  <form class="" id="frmDonation" enctype="multipart/form-data">
                    <div class="row g-3">
                      <div class="mb-3 col col-md-6">
                        <label for="donor" class="form-label fw-bold">Donor</label>
                        <input type="text" class="form-control" name="donor" id="donor" required>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="date" class="form-label fw-bold">Date</label>
                        <input type="date" class="form-control" name="date" id="date">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-6">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <input type="text" class="form-control" name="description" id="description">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-6">
                        <label for="pickupLocation" class="form-label fw-bold">Pickup Location</label>
                        <input type="text" class="form-control" name="pickupLocation" id="pickupLocation" required>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="remarks" class="form-label fw-bold">Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Upload Documentation</label>
                      <span>(PDF/WORD Max: 5MB)</span><br>
                      <input type="file" class="form-control" name="uploadDocumentation" id="uploadDocumentation">
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" name="transportStatus" id="transportStatus">
                      <label class="form-check-label fw-bold" for="transportStatus" id="labelTransportStatus">Status</label>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmDonation">Save</button>
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END DONATION FORM -->

          <div class="col-lg-9 ps-lg-5 tbl table-donor" data-aos="fade-up" data-aos-delay="200">
            <!-- DATA TABLE -->
            <table id="donationDataTable" class="display table table-striped mt-5">
              <thead>
                <tr>
                  <th scope="col">First Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">Gender</th>
                  <th scope="col">Birthdate</th>
                  <th scope="col">Street</th>
                  <th scope="col">Address</th>
                  <th scope="col">City</th>
                  <th scope="col">Region</th>
                  <th scope="col">Zip Code</th>
                  <th scope="col">Country</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Email</th>
                  <th scope="col">Terms of Volunteering</th>
                  <th scope="col">Signature</th>
                  <th scope="col" colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
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

  <!-- <script src="../../../app/js/donationManagement.js"></script> -->
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [8, 20, 30, 50, 100]
    });
  });
</script>

</body>

</html>