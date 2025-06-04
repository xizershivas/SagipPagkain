<?php
session_start();
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
  <!-- <link href="../../../app/css/signup.css" rel="stylesheet"> -->
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
            <li><a href="dashboard.php">Admin</a></li>
            <li class="current">User Management</li>
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
                <?php if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnAdmin"]) && $_SESSION["ysnAdmin"] == 1)  { ?>
                  <a href="user.php" class="active"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <?php } ?>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <!--<a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>-->
                <!-- <a href="volunteerManagement.php" class=""><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <!--<a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>-->
                <!--<a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>-->
                <!--<a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>-->
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <!-- ADD USER FORM (HIDDEN) -->
          <div class="modal fade" id="modalFrmAddUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black mb-3 bg-light p-3">
                  <h3 class="signup-heading text-black text-center mb-3">Add New User</h3>
                  <form class="row g-3 needs-validation" id="frmAddUser" novalidate>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="fullname">Full Name</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-fullname"><i class="bi bi-person-vcard-fill"></i></span>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" aria-label="fullname" aria-describedby="addon-fullname" required>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="email">Email</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-email"><i class="bi bi-envelope-at-fill"></i></span>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" aria-label="email" aria-describedby="addon-email">
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="contact">Contact</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-contact"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" class="form-control" name="contact" id="contact" placeholder="Contact Number" aria-label="contact" aria-describedby="addon-contact">
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="username">Username</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-username"><i class="bi bi-person-circle"></i></span>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" aria-label="username" aria-describedby="addon-username" required>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="password">Password</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-password"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="password" aria-describedby="addon-password" required>
                        <span class="input-group-text signup-form-icon"><i class="bi bi-eye-fill show-hide-password" id="eyePassword"></i></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="confirmPassword">Confirm Password</label>
                      <div class="input-group">
                        <span class="input-group-text signup-form-icon" id="addon-confirm-password"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" aria-label="confirm-password" aria-describedby="addon-confirm-password" required>
                        <span class="input-group-text signup-form-icon"><i class="bi bi-eye-fill show-hide-password"></i></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="accountType">Account Type</label>
                      <select class="form-select" aria-label="Large select example" name="accountType" id="accountType" required>
                        <option selected disabled value="">-- Select account type --</option>
                        <option value="admin">Admin</option>
                        <option value="donor">Donor</option>
                        <option value="staff">Food Bank Staff</option>
                        <option value="partner">Partner (NGO, Cooperative, etc.)</option>
                      </select>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label fw-bold signup-form-label" for="status">Status</label>
                      <select class="form-select" aria-label="Large select example" name="status" id="status" required>
                        <option selected disabled value="">-- Select status --</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                      </select>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="btnCancel" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary me-1" id="btnSaveUser" form="frmAddUser">Save</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END ADD USER FORM-->

             <div class="col-lg-9 tbl table-donor pe-2 mt-0" data-aos="fade-up" data-aos-delay="200">
              <div class="row justify-content-center">
                <button type="button" class="btn btn-success w-25" id="btnAddUser" data-bs-toggle="modal" data-bs-target="#modalFrmAddUser">Add New User</button>
              </div>

               <!-- EDIT USER FORM (HIDDEN) -->
              <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">

                    <div class="text-black mb-3 bg-light p-3">
                      <form id="frmUser">
                        <div class="row">
                          <input type="hidden" name="userId" id="userId">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="user" class="form-label">User</label>
                              <input type="text" class="form-control" name="user" id="user" value="" readonly>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="userEmail" class="form-label">Email</label>
                              <input type="email" class="form-control" name="userEmail" id="userEmail" value="">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="fullName" class="form-label">Full Name</label>
                              <input type="text" class="form-control" name="fullName" id="fullName" value="">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="userContact" class="form-label">Contact</label>
                              <input type="text" class="form-control" name="userContact" id="userContact" value="">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="address" class="form-label">Address</label>
                              <input type="text" class="form-control" name="address" id="address" value="">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="salary" class="form-label">Salary</label>
                              <input type="number" class="form-control" name="salary" id="salary" value="" step="0.01">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12 col-md-6">
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
                              <input class="form-check-input" type="checkbox" role="switch" name="staff" id="staff">
                              <label class="form-check-label" for="staff">Staff access</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" name="partner" id="partner">
                              <label class="form-check-label" for="partner">Partner access</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" name="beneficiary" id="beneficiary">
                              <label class="form-check-label" for="beneficiary">Beneficiary access</label>
                            </div>
                          </div>

                          <div class="col-12 col-md-6">
                            <label for="uploadDocInput" class="form-label">Document Uploaded</label>
                            <input type="file" class="form-control" name="uploadDocInput" id="uploadDocInput" value="">

                            <div class="mt-2 position-relative" id="docContainer">
                              <iframe class="w-100" id="uploadDocPreview" src="" frameborder="0"></iframe>
                              <a class="btn btn-sm btn-success position-absolute d-none" 
                                id="btnViewDoc" 
                                href="" 
                                target="_blank"
                                style="top: 10px; left: 10px; z-index: 10;">View
                              </a>
                              <a class="btn btn-sm btn-primary position-absolute d-none" 
                                id="btnDownloadDoc" 
                                href="" 
                                target="_blank"
                                style="top: 10px; left: 70px; z-index: 10;"
                                download>Download
                              </a>
                            </div>

                          </div>
                        </div>

                      </form>
                    </div>

                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmUser">Save</button>
                      <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                    </div>

                  </div>
                </div>
              </div>
              <!-- END EDIT USER FORM -->


              <!-- DATA TABLE -->
              <table id="userDataTable" class="display table table-striped">
                <thead>
                  <tr>
                    <th scope="col">User</th>
                    <th scope="col">Email</th>
                    <th scope="col">Active</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Donor</th>
                    <th scope="col">Staff</th>
                    <th scope="col">Partner</th>
                    <th scope="col">Beneficiary</th>
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
                        <td><?php echo $user->ysnStaff ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
                        <td><?php echo $user->ysnPartner ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
                        <td><?php echo $user->ysnBeneficiary ? "<span class='ysn-true'>True</span>" : "<span class='ysn-false'>False</span>";  ?></td>
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

      lengthMenu: [10, 20, 30, 50, 100]
    });
  });
  </script>
</body>

</html>