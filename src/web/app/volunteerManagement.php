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
  <title>Sagip Pagkain - Volunteer Management</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

 <!-- Include global stylesheet -->
 <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <link href="../../../app/css/volunteerManagement.css" rel="stylesheet">
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
            <li><a href="dashboard.php">Admin</a></li>
            <li class="current">Volunteer Management</li>
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
                <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>
                <!-- <a href="volunteerManagement.php" class="active"><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
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
                  <form class="" id="frmVolunteer" enctype="multipart/form-data">
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="firstname" class="form-label fw-bold">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="lastname" class="form-label fw-bold">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="gender" class="form-label fw-bold">Gender</label>
                        <input type="text" class="form-control" name="gender" id="gender" required>
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="birthdate" class="form-label fw-bold">Birthdate</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="street" class="form-label fw-bold">Street</label>
                        <input type="text" class="form-control" name="street" id="street">
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <input type="text" class="form-control" name="address" id="address">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="city" class="form-label fw-bold">City/Municipality</label>
                        <input type="text" class="form-control" name="city" id="city" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="region" class="form-label fw-bold">Region</label>
                        <input type="text" class="form-control" name="region" id="region" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="zipcode" class="form-label fw-bold">Zip Code</label>
                        <input type="text" class="form-control" name="zipcode" id="zipcode">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="country" class="form-label fw-bold">Country</label>
                        <input type="text" class="form-control" name="country" id="country" required>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="contact" class="form-label fw-bold">Contact</label>
                        <input type="text" class="form-control" name="contact" id="contact">
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-12">
                        <label for="" class="form-label fw-bold">Signature <span class="fw-normal">(Image: JPG/PNG Max: 3MB)</span></label>
                        <div class="mb-1">
                        <b>Uploaded: </b><span class="d-inline my-3" id="signUploaded"></span>
                          <img src="" alt="" name="signImage" id="signImage">
                        </div>
                        <input type="file" class="form-control" name="signature" id="signImageSelected">
                      </div>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmVolunteer">Save</button>
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END DONATION FORM -->

          <div class="col-lg-9 tbl table-donor pe-2 mt-0" data-aos="fade-up" data-aos-delay="200">
            <!-- DATA TABLE -->
            <table id="donationDataTable" class="display table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">FirstName</th>
                  <th scope="col">LastName</th>
                  <th scope="col">Gender</th>
                  <th scope="col">Birthdate</th>
                  <th scope="col">Street</th>
                  <th scope="col">Address</th>
                  <th scope="col">City/Municipality</th>
                  <th scope="col">Region</th>
                  <th scope="col">ZipCode</th>
                  <th scope="col">Country</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Email</th>
                  <!-- <th scope="col">Terms of Volunteering</th> -->
                  <th scope="col">Signature</th>
                  <th scope="col" colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $allVolunteerData = getVolunteerData($conn);
                $rowNumber = 1;

                if ($allVolunteerData->num_rows > 0) {
                    while ($data = $allVolunteerData->fetch_object()) { 
                      ?>
                        <tr>
                          <td><?php echo $rowNumber; ?></td>
                          <td><?php echo $data->strFirstName; ?></td>
                          <td><?php echo $data->strLastName; ?></td>
                          <td><?php echo $data->strGender; ?></td>
                          <td><?php echo $data->dtmDateOfBirth; ?></td>
                          <td><?php echo $data->strStreet; ?></td>
                          <td><?php echo $data->strAddress; ?></td>
                          <td><?php echo $data->strCity; ?></td>
                          <td><?php echo $data->strRegion; ?></td>
                          <td><?php echo $data->strZipCode; ?></td>
                          <td><?php echo $data->strCountry; ?></td>
                          <td><?php echo $data->strContact; ?></td>
                          <td><?php echo $data->strEmail; ?></td>
                          <td><?php echo $data->strSignFilePath; ?></td>
                          <td>
                              <a class="btn-edit-volunteer" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                                href="javascript:void(0)" data-id="<?php echo $data->intVolunteerId; ?>">
                                <i class='bi bi-pencil-square'></i>
                              </a>
                          </td>
                          <td>
                              <a class="btn-delete-volunteer" href="javascript:void(0)" data-id="<?php echo $data->intVolunteerId; ?>">
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

  <script src="../../../app/js/volunteerManagement.js"></script>
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [10, 20, 30, 50, 100]
    });
  });
</script>

</body>

</html>