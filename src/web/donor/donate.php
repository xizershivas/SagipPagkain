<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/donate.php";
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
  <title>Services Details - Append Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">


  <!-- Include global stylesheet -->
  <?php include '../global/stylesheet.php'; ?>
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
            <li><a href="foodDonationManagement.php">Donate</a></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5" style=" margin-bottom: 63px;">

          <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="./dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="donate.php" class="active"><i class="bi bi-gift"></i><span>Donate</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>
                <a href="foodCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <a href="reward.php"><i class="bi bi-trophy"></i><span>Reward System</span></a>
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
            <!-- DONATION FORM -->
            <form class="row g-3 needs-validation text-black" id="frmDonate" enctype="multipart/form-data" novalidate>
              <div class="col-md-4">
                <input type="hidden" name="userId" value="<?php if (isset($user)) { echo $user->intUserId; }?>">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" id="fullname" value="<?php if (isset($user)) { echo $user->strFullName; }?>" readonly>
                <div class="invalid-feedback">
                  Full Name is required
                </div>
              </div>
              <div class="col-md-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="date" value="" required>
                <div class="invalid-feedback">
                  Date is required
                </div>
              </div>
              <div class="col-md-4">
                <label for="foodBank" class="form-label">Food Bank (Drop Off Location)</label>
                <select class="form-select" name="foodBank" id="foodBank" aria-label="Food Bank selection">
                  <?php
                  $foodBanks = getFoodBanks($conn);
                  if ($foodBanks->num_rows > 0) {
                    while($foodBank = $foodBanks->fetch_object()) {
                    ?>
                      <option value="<?php echo $foodBank->intFoodBankId; ?>"><?php echo $foodBank->strFoodBank; ?></option>
                    <?php
                    }
                  }
                  ?>
                </select>
                <div class="invalid-feedback">
                  Food Bank is required
                </div>
              </div>
              <div class="col-md-4">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="" required>
                <div class="invalid-feedback">
                  Title is required
                </div>
              </div>
              <div class="col-md-4">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description" value="" required>
                <div class="invalid-feedback">
                  Description is required
                </div>
              </div>
              <div class="col-md-4">
                <label for="remarks" class="form-label">Remarks</label>
                <input type="text" class="form-control" name="remarks" id="remarks" value="">
              </div>
              <div class="col-md-3">
                <label for="item" class="form-label">Item</label>
                <input class="form-control" list="itemOptions" name="item" id="item" placeholder="Select Item" required>
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
              <div class="col-md-3">
                <label class="form-label" for="quantity">Quantity</label>
                <input class="form-control" type="number" name="quantity" id="quantity" min="1" required>
                <div class="invalid-feedback">
                  Quantity is required
                </div>
              </div>
              <div class="col-md-3">
                <label for="unit" class="form-label">Unit</label>
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
              <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
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
              <div class="col-md-4">
                <label for="" class="form-label">Upload Document (JPG/PNG Max: 5MB)</label>
                <input type="file" class="form-control" name="verification[]" id="verification" accept="image/*" multiple>
                <!-- <div class="invalid-feedback">
                  Document is required
                </div> -->
              </div>
              <div class="col-12">
                <button class="btn btn-primary" type="submit">Submit</button>
              </div>
            </form>
            <!-- END FORM DONATE -->

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

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

  <script src="../../../app/js/formValidation.js"></script>
  <script src="../../../app/js/donate.js"></script>
</body>

</html>