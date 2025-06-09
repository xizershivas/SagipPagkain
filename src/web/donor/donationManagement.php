<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/donationManagement.php";
$userData;
$user;
if (isset($_SESSION["intUserId"])) {
  $userData = getUserData($conn, $_SESSION["intUserId"]);
  $user = $userData->fetch_object();
}
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
            <li class="current">Donor</li>
            <li><a href="donationManagement.php">Donation Management</a></li>
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
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="trackDonation.php"><i class="bi bi-trophy"></i><span>Track Donation</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="donate.php" class=""><i class="bi bi-gift"></i><span>Donate</span></a>
                <!--<a href="donationManagement.php" class="active"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>-->
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

          <!-- VIEW ARCHIVE (HIDDEN) -->
          <div class="modal fade" id="modalFrmViewArchive" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content modal-archive px-4 py-4">

                <div class="tbl" data-aos="fade-up" data-aos-delay="200">
                  <!-- DATA TABLE -->
                  <table id="archiveDataTable" class="display table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">Donor</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $allArchiveData = getArchiveData($conn);

                      if ($allArchiveData->num_rows > 0) {
                        while ($data = $allArchiveData->fetch_object()) {
                          ?>
                          <tr>
                            <td><?php echo $data->strDonorName; ?></td>
                            <td>
                              <a class="btn-archive-donation" href="javascript:void(0)" data-id="<?php echo $data->intDonationId; ?>" data-archive="0">
                                <i class="bi bi-archive-fill"></i>
                              </a>
                            </td>
                          </tr>
                          <?php
                        }
                      }
                    ?>
                    </tbody>
                  </table><!-- END DATA TABLE -->
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END VIEW ARCHIVE -->

          <!-- DONATION FORM (HIDDEN) -->
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black bg-light p-3" id="frmEditDonation">
                  <form class="needs-validation" id="frmDonation" enctype="multipart/form-data" novalidate>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="donor" class="form-label fw-bold">Donor</label>
                        <input type="text" class="form-control" name="donor" id="donor" readonly>
                        <div class="invalid-feedback">
                          Donor is required
                        </div>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="date" class="form-label fw-bold">Date</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                        <div class="invalid-feedback">
                          Date is required
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="foodBank" class="form-label fw-bold">Food Bank</label>
                        <select class="form-select" name="foodBank" id="foodBank" aria-label="Food Bank selection">
                          <?php
                          $foodBanks = getFoodBanks($conn);
                          if ($foodBanks->num_rows > 0) {
                            while($foodBank = $foodBanks->fetch_object()) {
                            ?>
                              <option value="<?php echo $foodBank->intFoodBankId; ?>"><?php echo $foodBank->strMunicipality; ?></option>
                            <?php
                            }
                          }
                          ?>
                        </select>
                        <div class="invalid-feedback">
                          Food Bank is required
                        </div>
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                        <div class="invalid-feedback">
                          Title is required
                        </div>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <input type="text" class="form-control" name="description" id="description" required>
                        <div class="invalid-feedback">
                          Description is required
                        </div>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="remarks" class="form-label fw-bold">Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks">
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-3 col col-md-4">
                        <label for="itemFood" class="form-label fw-bold">Item</label>
                        <input class="form-control" list="itemOptions" name="itemFood" id="itemFood" placeholder="Select Item" required>
                        <datalist id="itemOptions">
                          <?php
                            $items = getItems($conn);
                            if ($items->num_rows > 0) {
                              while($item = $items->fetch_object()) {
                              ?>
                                <option value="<?php echo $item->strItem; ?>">
                              <?php
                              }
                            }
                          ?>
                        </datalist>
                        <div class="invalid-feedback">
                          Item is required
                        </div>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label class="form-label fw-bold" for="quantity">Quantity</label>
                        <input class="form-control" type="number" name="quantity" id="quantity" min="1" required>
                        <div class="invalid-feedback">
                          Quantity is required
                        </div>
                      </div>
                      <div class="mb-3 col col-md-4">
                        <label for="unit" class="form-label fw-bold">Unit</label>
                        <input class="form-control" list="unitOptions" name="unit" id="unit" placeholder="Select Unit" required>
                        <datalist id="unitOptions">
                          <?php
                            $units = getUnits($conn);
                            if ($units->num_rows > 0) {
                              while($unit = $units->fetch_object()) {
                              ?>
                                <option value="<?php echo $unit->strUnit; ?>">
                              <?php
                              }
                            }
                          ?>
                        </datalist>
                        <div class="invalid-feedback">
                          Unit is required
                        </div>
                      </div>
                    </div>
                    <div class="row g-3">
                      <div class="mb-2 col col-md-4">
                        <label for="category" class="form-label fw-bold">Category</label>
                        <input class="form-control" list="categoryOptions" name="category" id="category" placeholder="Select Item Category" required>
                        <datalist id="categoryOptions">
                          <?php
                            $categories = getCategories($conn);
                            if ($categories->num_rows > 0) {
                              while($category = $categories->fetch_object()) {
                              ?>
                                <option value="<?php echo $category->strCategory; ?>">
                              <?php
                              }
                            }
                          ?>
                        </datalist>
                        <div class="invalid-feedback">
                          Category is required
                        </div>
                      </div>
                      <div class="mb-2 col col-md-4">
                        <label for="" class="form-label fw-bold">Verification</label> <span>(JPG/PNG)</span><br>
                        <input type="file" class="form-control" name="verification[]" id="verification" accept="image/*" multiple>
                      </div>
                      <div class="mb-2">
                        <b>Uploaded: </b><span class="d-inline my-3" id="signUploaded"></span>
                        <!-- SHOW ALL MEDIA SELECTED -->
                        <div class="d-flex justify-content-center align-items-center" id="mediaSelectedLoc"></div>
                        <div class="mb-3" id="docsUploadedMedia"></div>
                      </div>
                    </div>
                    <div class="form-check form-switch d-none">
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

          <div class="col-lg-9 tbl table-donor pe-2" data-aos="fade-up" data-aos-delay="200">
            <div class="row justify-content-center">
              <button type="button" class="btn btn-success w-25" id="btnViewArchive" data-bs-toggle="modal" data-bs-target="#modalFrmViewArchive">View Archive</button>
            </div>
            <!-- DATA TABLE -->
            <table id="donationDataTable" class="display table table-striped">
              <thead>
                <tr>
                  <th scope="col">Donor</th>
                  <th scope="col">Date</th>
                  <th class="text-nowrap" scope="col">Food Bank</th>
                  <th scope="col">Title</th>
                  <th scope="col">Description</th>
                  <th scope="col">Item</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Unit</th>
                  <th scope="col">Category</th>
                  <th scope="col">Verification</th>
                  <th scope="col">Status</th>
                  <th scope="col">Remarks</th>
                  <th scope="col" colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $allDonationData = getDonationData($conn, $user);

                if ($allDonationData && $allDonationData->num_rows > 0) {
                    while ($data = $allDonationData->fetch_object()) { 
                      ?>
                        <tr>
                            <td><?php echo $data->strDonorName; ?></td>
                            <td class="text-nowrap"><?php echo $data->dtmDate; ?></td>
                            <td><?php echo $data->strMunicipality; ?></td>
                            <td><?php echo $data->strTitle; ?></td>
                            <td><?php echo $data->strDescription; ?></td>
                            <td><?php echo $data->strItem; ?></td>
                            <td><?php echo $data->intQuantity; ?></td>
                            <td><?php echo $data->strUnit; ?></td>
                            <td><?php echo $data->strCategory; ?></td>
                            <td>
                              <?php
                                $paths = explode(",", $data->strDocFilePath);
                                $docsUploaded = "";
                                foreach($paths as $path) {
                                  $docsUploaded .= basename($path) . ", ";
                                }
                                echo rtrim($docsUploaded, ", ");
                              ?>
                            </td>
                            <td>
                              <?php 
                                echo ($data->ysnStatus == 0) ? "<span class='text-nowrap ysn-in-transit'>In Transit</span>" 
                                : ($data->ysnStatus == 1 ? "<span class='ysn-received'>Received</span>"
                                : "<span class='ysn-delivered'>Delivered</span>");
                              ?>
                            </td>
                            <td><?php echo $data->strRemarks; ?></td>
                            <td>
                                <a class="btn-edit-donation" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                                  href="javascript:void(0)" data-id="<?php echo $data->intDonationId; ?>">
                                  <i class='bi bi-pencil-square'></i>
                                </a>
                            </td>
                            <td>
                                <!-- <a class="btn-delete-donation" href="javascript:void(0)" data-id="">
                                    <i class="bi bi-trash-fill"></i>
                                </a> -->
                                <a class="btn-archive-donation" href="javascript:void(0)" data-id="<?php echo $data->intDonationId; ?>">
                                  <i class="bi bi-archive-fill"></i>
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

  <script src="../../../app/js/formValidation.js"></script>
  <script src="../../../app/js/donationManagement.js"></script>
  <script>
  $(document).ready(function() {
    new DataTable('#donationDataTable', {

      lengthMenu: [10, 20, 30, 50, 100]
    });
  });

  $(document).ready(function() {
    new DataTable('#archiveDataTable', {

      lengthMenu: [5, 10]
    });
  });
</script>

</body>

</html>