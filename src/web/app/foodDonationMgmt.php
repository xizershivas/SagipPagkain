<?php
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Services Details - Append Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Include stylesheet -->
  <?php include '../global/stylesheet.php'; ?>    

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="app/css/app.css" rel="stylesheet">
  <link href="app/css/foodDonationMgmt.css" rel="stylesheet">
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
            <li><a href="">Donor Management</a></li>
            <li class="current">Food Donation Management</li>
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
                <a href="foodDonationMgmt.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>Food Donation Management</span></a>
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

             <div class="col-lg-8 ps-lg-5 tbl table-donor" data-aos="fade-up" data-aos-delay="200">
               <!-- USER FORM (HIDDEN) -->
               <div class="text-black mb-3 bg-light p-3 d-none" id="frmUser">
                <form>
                  <div class="mb-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" class="form-control" name="user" id="user" value="" disabled>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="">
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="enabled" id="enabled">
                    <label class="form-check-label" for="enabled">Enabled</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="approved" id="approved">
                    <label class="form-check-label" for="approved">Approved</label>
                  </div>
                  <div class="d-flex justify-content-center mt-3">
                    <button type="button" class="btn btn-success me-1" id="btnSave">Save</button>
                    <button type="button" class="btn btn-danger" id="btnClose">Close</button>
                  </div>
                </form>
              </div>
              <!-- END USER FORM -->

              <!-- DATA TABLE -->
              <table id="userDataTable" class="display table table-striped">
                <thead>
                  <tr>
                    <th scope="col">User</th>
                    <th scope="col">Email</th>
                    <th scope="col">Enabled</th>
                    <th scope="col">Approved</th>
                    <th scope="col" colspan="2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if($allUserData->num_rows > 0) {
                    while($user = $allUserData->fetch_object()) {
                      echo "<tr><td>".$user->strUsername."</td>".
                      "<td>".$user->strEmail."</td>".
                      "<td>".($user->ysnEnabled ? "<span class='ysnenabled-true'>True</span>" : "<span class='ysnenabled-false'>False</span>")."</td>".
                      "<td>".($user->ysnApproved ? "<span class='ysnapproved-true'>True</span>" : "<span class='ysnapproved-false'>False</span>")."</td>".
                      "<td><a class='btn-edit-user' href='javascript:void(0)' value='".$user->intUserId."'><i class='bi bi-pencil-square'></i></a></td>".
                      "<td><a class='btn-delete-user' href='javascript:void(0)' value='".$user->intUserId."'><i class='bi bi-trash-fill'></i></a></td>".
                      "</tr>";
                    }
                  }

                  $conn->close();
                  ?>
                </tbody>
              </table>
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
  <script src="app/js/app.js"></script>
  <script src="app/js/user.js"></script>
  <script>
    $(document).ready(function() {
      $('#userDataTable').DataTable();
    });
  </script>
</body>

</html>