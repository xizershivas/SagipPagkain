<?php
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - User Management</title>
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
  <link href="../../../app/css/user.css" rel="stylesheet">
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">User Management</li>
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
                <a href="user.php" class="active"><i class="bi bi-arrow-right-circle"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-arrow-right-circle"></i><span>Donation Management</span></a>
                <a href="volunteerManagement.php" class=""><i class="bi bi-arrow-right-circle"></i><span>Volunteer Management</span></a>
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

             <div class="col-lg-9 ps-lg-5 tbl table-donor" data-aos="fade-up" data-aos-delay="200">
               <!-- USER FORM (HIDDEN) -->
              <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <div class="text-black mb-3 bg-light p-3">
                      <form id="frmUser">
                        <div class="mb-3">
                          <label for="user" class="form-label">User</label>
                          <input type="text" class="form-control" name="user" id="user" value="" disabled>
                        </div>
                        <div class="mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" name="email" id="email" value="">
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" name="active" id="active">
                          <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" name="admin" id="admin">
                          <label class="form-check-label" for="admin">Admin access</label>
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" name="donor" id="donor">
                          <label class="form-check-label" for="donor">Donor access</label>
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" name="ngo" id="ngo">
                          <label class="form-check-label" for="ngo">NGO access</label>
                        </div>
                      </form>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary me-1" id="btnSave">Save</button>
                      <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                    </div>

                  </div>
                </div>
              </div>
              <!-- END USER FORM -->


              <!-- DATA TABLE -->
              <table id="userDataTable" class="display table table-striped mt-5">
                <thead>
                  <tr>
                    <th scope="col">User</th>
                    <th scope="col">Email</th>
                    <th scope="col">Active</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Donor</th>
                    <th scope="col">NGO</th>
                    <th scope="col" colspan="2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $allUserData = getUserData($conn);

                  if($allUserData->num_rows > 0) {
                    while($user = $allUserData->fetch_object()) {
                      ?>
                      <tr>
                        <td><?php echo $user->strUsername; ?></td>
                        <td><?php echo $user->strEmail; ?></td>
                        <td><?php echo $user->ysnActive ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>"; ?></td>
                        <td><?php echo $user->ysnAdmin ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
                        <td><?php echo $user->ysnDonor ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
                        <td><?php echo $user->ysnNgo ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
                        <td><a class="btn-edit-user" data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="javascript:void(0)" value="<?php echo $user->intUserId; ?>"><i class='bi bi-pencil-square'></i></a></td>
                        <td><a class="btn-delete-user" href="javascript:void(0)" value="<?php echo $user->intUserId; ?>"><i class="bi bi-trash-fill"></i></a></td>
                      </tr>
                      <?php
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
  <script src="../../../app/js/app.js"></script>
  <script src="../../../app/js/user.js"></script>
  <script>
    $(document).ready(function() {
    new DataTable('#userDataTable', {

      lengthMenu: [9, 20, 30, 50, 100]
    });
  });
  </script>
</body>

</html>