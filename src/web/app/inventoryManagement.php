<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/manageBeneficiary.php";
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
  <link href="../../../app/css/manageBeneficiary.css" rel="stylesheet">
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">Inventory Management</li>
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
                <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="volunteerManagement.php"><i class="bi bi-people"></i><span>Volunteer Management</span></a>
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

          <!-- ADD BENEFICIARY FORM (HIDDEN) -->
          <div class="modal fade" id="modalFrmAddBeneficiary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">

                <div class="text-black mb-3 bg-light p-3">
                  <form class="" id="frmAddBeneficiary">
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="name" id="name" required>
                          <label for="name" class="form-label">Beneficiary's Full Name</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="form-floating">
                          <input type="email" class="form-control" name="email" id="email">
                          <label for="email" class="form-label">Email Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="mb-3 col col-md-6">
                        <label for="contact" class="form-label fw-bold">Contact</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="contact" id="contact">
                          <label for="contact" class="form-label">(Mobile No. / Phone No.)</label>
                        </div>
                      </div>
                      <div class="mb-3 col col-md-6">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <div class="form-floating">
                          <input type="text" class="form-control" name="address" id="address">
                          <label for="address" class="form-label">Complete Address</label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary me-1" id="btnSave" form="frmAddBeneficiary">Save</button>
                  <button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- END ADD BENEFICIARY FORM-->
          
          <div class="col-lg-9 tbl table-donor pe-2" data-aos="fade-up" data-aos-delay="200">
             <div class="row" style="padding: 20px 0;">
                <div class="col-lg-4 d-flex align-items-center">
                    <input type="text" id="column-name" class="form-control me-2" placeholder="Enter column name (optional)">
                    <button class="btn btn-primary btn-sm" style="width: 150px; height: 35px;" onclick="addColumn()">Add Column</button>
                </div>
            </div>

            <!-- DATA TABLE -->

            <table class="table table-bordered">
                <thead>
                    <tr id="inventoryTable">
                        <th>#</th>
                        <th>Quantity Need <button class="remove-btn" onclick="removeColumn(1)">×</button></th>
                        <th>Unit Need <button class="remove-btn" onclick="removeColumn(2)">×</button></th>
                        <th>Item/Food <button class="remove-btn" onclick="removeColumn(3)">×</button></th>
                        <th>Grocery Category <button class="remove-btn" onclick="removeColumn(4)">×</button></th>
                        <th>Storage <button class="remove-btn" onclick="removeColumn(5)">×</button></th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">
                    <tr>
                        <td>1</td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                    </tr>
                </tbody>
            </table>

           <!-- END DATA TABLE -->
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
<script>
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
    </script>

</body>

</html>