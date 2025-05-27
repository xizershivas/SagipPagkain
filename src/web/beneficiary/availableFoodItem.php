<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";
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
            <li class="current">Beneficiary</li>
            <li><a href="availableFoodItem.php">View Available food items</a></li>
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
                <a href="assistanceRequest.php"><i class="bi bi-pencil-square"></i><span>Request for Assistance</span></a>
                <a href="requestStatus.php"><i class="bi bi-search"></i><span> Track Available Status</span></a>
                <a href="availableFoodItem.php" class="active"><i class="bi bi-box"></i><span> View Available Food Items</span></a>
                <a href="RequestHistory.php"><i class="bi bi-clock-history"></i><span> Request History</span></a>
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
          <div class="container">
                <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Available Food Items</h2>
                    <div class="mb-3">
                    <label for="searchItems" class="form-label">Search Items</label>
                    <input type="text" class="form-control border-warning" id="searchItems" placeholder="Enter item name">
                    </div>
                </div>
                </div>

                <!-- <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Filter by Category</h2>
                    <div class="mb-3">
                    <label for="category" class="form-label">Select Category</label>
                    <select class="form-select border-warning" id="category">
                        <option value="">select category</option>
                    </select>
                    </div>
                </div>
                </div> -->

                <!-- <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Request Specific Items</h2>
                    <form>
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control border-warning" id="itemName" placeholder="Enter item name">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity Needed</label>
                        <input type="number" class="form-control border-warning" id="quantity" placeholder="Enter quantity">
                    </div>
                    <div class="mb-3">
                        <label for="urgencyLevel" class="form-label">Urgency Level</label>
                        <select class="form-select border-warning" id="urgencyLevel">
                        <option value="">Select urgency</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="itemNotes" class="form-label">Additional Notes</label>
                        <textarea class="form-control border-warning" id="itemNotes" placeholder="Enter notes" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-white px-4">Submit</button>
                    </div>
                    </form>
                </div>
                </div> -->
            </div>

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