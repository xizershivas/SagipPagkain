<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/inventoryTransfer.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Inventory Management</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

 <!-- Include global stylesheet -->
 <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" /> -->
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
            <li><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Admin'; ?></li>
            <li><a href="inventoryManagement.php">Inventory Management</a></li>
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
                  <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <?php } ?>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>
                <!-- <a href="volunteerManagement.php"><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php" class="active"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
                
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
             <div class="row">
                <div class="col col-md-4 mb-2">
                  <form id="frmInventoryFilter">
                    <div class="row g-2">
                      <div class="col col-md-8">
                        <input class="form-control" list="filterOptions" name="searchItem" id="searchItem" placeholder="Search" required>
                        <datalist id="filterOptions">
                        </datalist>
                      </div>
                      <div class="col col-md-4 d-flex">
                        <select class="form-select px-2 me-2" name="filterBy" id="filterBy" aria-label="Inventory filter">
                          <option value="strCategory" selected>Category</option>
                          <option value="strItem">Item</option>
                          <option value="strUnit">Unit</option>
                          <option value="strFoodBank">Food Bank</option>
                        </select>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col col-md-4 mb-2">
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#transferInventoryModal">
                    Transfer Inventory
                  </button>
                </div>
            </div>

            <!-- TABLE INVENTORY -->
            <table class="table table-bordered table-hover">
              <thead class="text-center">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Item</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Unit</th>
                  <th scope="col">Category</th>
                  <th scope="col">Food Bank</th>
                </tr>
              </thead>
              <tbody id="tableBody">
              </tbody>
            </table><!-- END TABLE INVENTORY -->

          </div>
        </div>
      </div>

      <!-- Transfer Inventory Modal -->
<div class="modal fade" id="transferInventoryModal" tabindex="-1" aria-labelledby="transferInventoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-black"> <!-- 👈 Added text-black -->

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="transferInventoryLabel" style="color: #333;">Transfer Inventory</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <form id="transferInventoryForm">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="sourceFoodBankSelect" class="form-label">Source Food Bank</label>
              <select class="form-select" id="sourceFoodBankSelect" name="sourceFoodBank" required>
                <option selected disabled value="">-- Select Source --</option>
                <?php
                  $sourceFoodBankData = getSourceFoodBanks($conn);

                  while($row = $sourceFoodBankData->fetch_object()) {
                    ?>
                    <option value="<?= $row->intFoodBankId; ?>"><?= $row->strFoodBank; ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="targetFoodBankSelect" class="form-label">Target Food Bank</label>
              <select class="form-select" id="targetFoodBankSelect" name="targetFoodBank" required>
                <option selected disabled value="">-- Select Destination --</option>
                <?php
                  $allFoodBanks = getAllFoodBanks($conn);

                  while($row = $allFoodBanks->fetch_object()) {
                    ?>
                    <option value="<?= $row->intFoodBankId; ?>"><?= $row->strFoodBank; ?></option>
                    <?php
                  }
                  $conn->close();
                ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="itemSelect" class="form-label">Item Name</label>
              <select class="form-select" id="itemSelect" name="item" required>
                <option selected disabled value="">-- Select Item --</option>
                <option value="Food Bank A">Tinapay</option>
                <option value="Food Bank B">Gatas</option>
                <option value="Food Bank B">Sabon</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="availableQty" class="form-label">Available Quantity</label>
              <input type="number" class="form-control" id="availableQty" name="availableQty" readonly>
            </div>
            <div class="col-md-4">
              <label for="itemUnit" class="form-label">Unit</label>
              <input type="text" class="form-control" id="itemUnit" name="itemUnit" readonly>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="transferQty" class="form-label">Quantity to Transfer</label>
              <input type="number" class="form-control" id="transferQty" name="transferQty" min="0" required>
            </div>
          </div>

        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning" form="transferInventoryForm">Proceed Transfer</button>
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
   <script src="../../../app/js/inventoryManagement.js"></script>
   <script src="../../../app/js/inventoryTransfer.js"></script>
<!-- <script>
        function addColumn() {
            let columnName = document.getElementById("column-name").value.trim();
            let tableHead = document.getElementById("inventoryTable");
            let tableBody = document.getElementById("inventoryTableBody");
            
            // Generate a default column name if none is provided
            let colCount = tableHead.getElementsByTagName("th").length;
            if (columnName === "") {
                columnName = "Column " + colCount;
            }

            // Add new column header with remove button
            let th = document.createElement("th");
            th.innerHTML = `${columnName} <button class="remove-btn" onclick="removeColumn(${colCount})">×</button>`;
            tableHead.appendChild(th);

            // Add new column cells to each row
            let rows = tableBody.getElementsByTagName("tr");
            for (let row of rows) {
                let td = document.createElement("td");
                td.contentEditable = "true";
                row.appendChild(td);
            }

            // Clear input field
            document.getElementById("column-name").value = "";
        }

        function removeColumn(index) {
            let tableHead = document.getElementById("inventoryTable");
            let tableBody = document.getElementById("inventoryTableBody");

            // Remove header column
            tableHead.removeChild(tableHead.children[index]);

            // Remove column from each row
            let rows = tableBody.getElementsByTagName("tr");
            for (let row of rows) {
                row.removeChild(row.children[index]);
            }
        }
    </script> -->

</body>

</html>