<?php
session_start();
include "app/config/db_connection.php";
include "app/functions/donationManagement.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Donation Management</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="app/css/app.css" rel="stylesheet">
  <link href="app/css/donationManagement.css" rel="stylesheet">
</head>

<body class="services-details-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <img src="assets/img/sagip-pagkain-logo.JPEG" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
        <div>
          <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
          <h4 class="sitename subtitle" style="padding-left: -10px; letter-spacing: 10.5px;">PAGKAIN</h4>
        </div>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#about">
            <div>
              <h6>Our Role in</h6>
              <h5><span>FOOD SYSTEMS</span></h5>
            </div>
          </a></li>
          <li><a href="index.php#system">
            <div>
              <h6>Our</h6>
              <h5>APPROACH</h5>
            </div>
          </a></li>
          <li><a href="index.php#stats">
            <div>
              <h5>IMPACT</h5>
            </div>
          </a></li>
          <li><a href="index.php#services">
            <div>
              <h6>About</h6>
              <h6>Sagip pagkain</h6>
            </div>
          </a></li>
          <li><a href="index.php#recent-posts">
            <div>
              <h5>COMMUNITY-LED</h5>
            </div>
          </a></li>
          <li><a href="index.php#contact">
            <div>
            <h5>OUR SUPPORT</h5>
          </div>
          </a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="dropdown">
          <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; font-size: 24px; color: #ffffff;">
            &#x22EE;
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background: rgba(255, 255, 255, 0.3); border: none;">
          <li><a class="dropdown-item" href="index.php#donate" style="color: white;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='white'">DONATE</a></li>
          <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="window.location.href='logout.php'" style="color: white;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='white'">Logout</a></li>
          </ul>
        </div>
      </div>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        
      </div>
      <nav class="breadcrumbs">
        <div class="container-fluid">
          <ol>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">Donation Management</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php"><i class="bi bi-arrow-right-circle"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-arrow-right-circle"></i><span>User Management</span></a>
                <a href="donationManagement.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Donation Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-arrow-right-circle"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-arrow-right-circle"></i><span>Data Analysis And Reporting</span></a>
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
          </div><!-- END DONATION FORM -->

          <div class="col-lg-8 ps-lg-5 tbl table-donor" data-aos="fade-up" data-aos-delay="200">
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

              if ($allDonationData->num_rows > 0) {
                while ($data = $allDonationData->fetch_object()) {
                  ?>
                  <tr>
                    <td><?php echo $data->strDonorName; ?></td>
                    <td><?php echo $data->dtmDate; ?></td>
                    <td><?php echo $data->strTitle; ?></td>
                    <td><?php echo $data->strDescription; ?></td>
                    <td><?php echo $data->strPickupLocation; ?></td>
                    <td><?php echo basename($data->strDocFilePath); ?></td>
                    <td><?php echo $data->ysnStatus ? "<span class='ysn-true'>Received</span>" : "<span class='ysn-false'>Pending</span>"; ?></td>
                    <td><?php echo $data->strRemarks; ?></td>
                    <td><a class="btn-edit-donation" data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="javascript:void(0)" value="<?php echo $data->intDonationId; ?>"><i class='bi bi-pencil-square'></i></a></td>
                    <td><a class="btn-delete-donation" href="javascript:void(0)" value="<?php echo $data->intDonationId; ?>"><i class="bi bi-trash-fill"></i></a></td>
                  </tr>
                  <?php
                }
              }

              $conn->close();
              ?>
              </tbody>
            </table><!-- END DATA TABLE -->
          </div>

        </div>
          </div>

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.php" class="logo d-flex align-items-center">
            <span class="sitename">Sagip pagkain</span>
          </a>
          <p>Dummy footer</p>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>


      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="sitename">Sagip pagkain</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <!-- Main JS File -->
  <script src="app/js/app.js"></script>
  <script src="app/js/donationManagement.js"></script>
  <script>
    $(document).ready(function() {
      $('#donationDataTable').DataTable();
    });
  </script>
</body>

</html>