<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../../app/config/db_connection.php";
include "../../../app/functions/beneficiary.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION["intUserId"])) {
  header("Location: ../forms/login.php");
}
else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnAdmin"]) && $_SESSION["ysnAdmin"] == 1) {
  header("Location: ../app/dashboard.php");
}
else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnFoodBank"]) && $_SESSION["ysnFoodBank"] == 1) {
  header("Location: ../ngo/dashboard.php");
}
else if (isset($_SESSION["intUserId"]) && isset($_SESSION["ysnDonor"]) && $_SESSION["ysnDonor"] == 1) {
  header("Location: ../donor/dashboard.php");
}

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
          <li><a href="assistanceRequest.php">Request for Assistance</a></li>
        </ol>
      </div>
    </nav>
  </div>

  <section id="service-details" class="service-details section">
    <div class="container-fluid">
      <div class="row gy-5">

        <div class="col-lg-3 mt-0" data-aos="fade-up" data-aos-delay="100">
          <div class="service-box">
            <h4>Services List</h4>
            <div class="services-list">
              <a href="assistanceRequest.php" class="active"><i class="bi bi-pencil-square"></i><span>Request for Assistance</span></a>
              <a href="requestStatus.php"><i class="bi bi-clock-history"></i><span>Request History</span></a>
              <a href="foodBankCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
              <a href="availableFoodItem.php"><i class="bi bi-box"></i><span>View Available Food Items</span></a>
            </div>
          </div>

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
                    <h2 class="card-title"></h2>
                <form id="beneficiaryRequestForm">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="beneficiaryId" class="form-label">Beneficiary ID</label>
                      <input type="text" class="form-control border-warning" name="beneficiaryId" id="beneficiaryId" value="<?= $user->intBeneficiaryId; ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="requestType" class="form-label">Request Type</label>
                      <select class="form-select border-warning" name="requestType" id="requestType" required>
                        <option value="" selected disabled>-- Select Type --</option>
                        <option value="emergency">Emergency</option>
                        <option value="regular">Regular</option>
                        <option value="special needs">Special Needs</option>
                      </select>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="itemsNeeded" class="form-label">Items Needed</label>
                      <select class="form-select border-warning" name="itemsNeeded[]" id="itemsNeeded" aria-label="Items Needed Select" multiple size="5" required>
                      <?php
                        $allItems = getItems($conn);
                        if ($allItems->num_rows > 0) {
                          while($item = $allItems->fetch_object()) {
                            echo "<option value=\"{$item->intItemId}\">{$item->strItem}</option>";
                          }
                        }
                      ?>
                    </select>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="urgencyLevel" class="form-label">Urgency Level</label>
                      <select class="form-select border-warning" name="urgencyLevel" id="urgencyLevel" required>
                        <option value="" selected disabled>-- Select Level --</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="pickupDate" class="form-label">Preferred Pickup Date</label>
                      <input type="date" class="form-control border-warning" name="pickupDate" id="pickupDate" required>
                    </div>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Recommended Food Bank</label>
                      <div id="recommendedFoodBank" class="border p-2 bg-light">No recommendation yet.</div>
                      <input type="hidden" id="beneficiaryId" value="<?= $user->intBeneficiaryId; ?>">
                      
                      <input type="hidden" name="recommendedFoodBankId" id="recommendedFoodBankId" value="">
                  </div>

                  <!-- <div class="card mb-4">
                    <div class="card-body">
                      <h2 class="card-title">Upload Documents</h2>
                      <div class="mb-3">
                        <label for="document" class="form-label">Select Document</label>
                        <input type="file" class="form-control border-warning" name="document" id="document" accept="application/pdf">
                      </div>
                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control border-warning" name="description" id="description" placeholder="Enter Description" rows="3"></textarea>
                      </div>
                    </div>
                  </div> -->

                  <div class="card mb-4">
                    
                    <div class="card-body">
                      <!-- <h2 class="card-title">Submit Form</h2> -->
                      <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <select class="form-select border-warning" name="purpose" id="purpose" aria-label="Select purpose" required>
                          <option value="" selected disabled>-- Select Purpose --</option>
                          <option value="1">Human Consumption</option>
                          <option value="2">Animal Consumption</option>
                          <option value="3">Fertilizer</option>
                        </select>
                      </div>
                      <input type="hidden" name="foodbankId" id="foodbankId">
                      <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-white px-4">Submit</button>
                      </div>
                    </div>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
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
<script src="../../../app/js/beneficiary.js"></script>
</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const itemsNeeded = document.getElementById('itemsNeeded');
  const beneficiaryId = document.getElementById('beneficiaryId').value;
  const recommendedFoodBankDiv = document.getElementById('recommendedFoodBank');
  const recommendedFoodBankIdInput = document.getElementById('recommendedFoodBankId');

  function updateRecommendation() {
    const selectedOptions = Array.from(itemsNeeded.selectedOptions).map(opt => opt.value);

    if (selectedOptions.length === 0) {
      recommendedFoodBankDiv.textContent = 'Please select items to get recommendation.';
      recommendedFoodBankIdInput.value = '';
      return;
    }

    const params = new URLSearchParams();
    params.append('beneficiaryId', beneficiaryId);
    selectedOptions.forEach(itemId => params.append('itemIds[]', itemId));

    fetch('recommendFoodBank.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const fb = data.foodBank;
        recommendedFoodBankDiv.innerHTML = `
          <strong>${fb.name}</strong><br>
          Address: ${fb.address}<br>
          Distance: ${fb.distance} km<br>
        `;
        recommendedFoodBankIdInput.value = fb.id;

        const foodbankIdInput = document.getElementById('foodbankId');
          if (foodbankIdInput) {
            foodbankIdInput.value = fb.id;
          }
      } else {
        recommendedFoodBankDiv.textContent = data.message || 'No suitable food bank found.';
        recommendedFoodBankIdInput.value = '';
      }
    })
    .catch(err => {
      recommendedFoodBankDiv.textContent = 'Error fetching recommendation.';
      recommendedFoodBankIdInput.value = '';
      console.error(err);
    });
  }

  itemsNeeded.addEventListener('change', updateRecommendation);

  if (itemsNeeded.selectedOptions.length > 0) updateRecommendation();
});
</script>
