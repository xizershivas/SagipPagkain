<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/beneficiary.php";
$userData;
$user;
if (isset($_SESSION["intUserId"])) {
  $userData = getUserData($conn, $_SESSION["intUserId"]);
  $user = $userData->fetch_object();
}


function getRecommendedItems($conn) {
    $sql = "
        SELECT itm.strItem, COUNT(*) AS requestCount
        FROM tblbeneficiaryrequestdetail brd
        INNER JOIN tblitem itm ON brd.intItemId = itm.intItemId
        GROUP BY brd.intItemId
        ORDER BY requestCount DESC
        LIMIT 5
    ";
    return $conn->query($sql);
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
                <a href="requestStatus.php"><i class="bi bi-clock-history"></i><span>Request History</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-box-seam"></i><span>Food Bank Center</span></a>
                <!-- <a href="RequestHistory.php"><i class="bi bi-clock-history"></i><span> Request History</span></a> -->
                <a href="availableFoodItem.php" class="active"><i class="bi bi-box"></i><span> View Available Food Items</span></a>
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

                          <!-- AVAILABLE FOOD ITEMS DATA TABLE -->
                          <table id="availableFoodItemsDataTable" class="display table table-striped">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Category</th>
                                <th scope="col">Food Bank</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $allInventoryData = getAllInventoryItems($conn);

                              if ($allInventoryData->num_rows > 0) {
                                $ctr = 1;
                                while ($row = $allInventoryData->fetch_object()) {
                                  echo "<tr>
                                          <td>{$ctr}</td>
                                          <td>{$row->strItem}</td>
                                          <td>{$row->intQuantity}</td>
                                          <td>{$row->strUnit}</td>
                                          <td>{$row->strCategory}</td>
                                          <td>{$row->strFoodBankName}</td>
                                        </tr>";
                                  $ctr++;
                                }
                              }
                              ?>
                            </tbody>
                          </table>
                          <!-- END AVAILABLE FOOD ITEMS DATA TABLE -->

                        </div>
                      </div>
                    </div>

                    <!-- START Recommended Items Section -->
                    <div class="card mb-4">
                      <div class="card-body">
                        <h4 class="card-title">Suggested Items to Request</h4>
                        <p class="card-text">
                          Based on previous request trends, you might consider requesting the following items:
                        </p>
                        <ul class="list-group list-group-flush">
                          <?php
                          $recommendedItems = getRecommendedItems($conn);
                          if ($recommendedItems->num_rows > 0) {
                            while ($rec = $recommendedItems->fetch_object()) {
                              echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                      {$rec->strItem}
                                      <span class='badge bg-primary rounded-pill'>{$rec->requestCount} requests</span>
                                    </li>";
                            }
                          } else {
                            echo "<li class='list-group-item'>No previous data available for suggestions.</li>";
                          }
                          ?>
                        </ul>
                      </div>
                    </div>
                    <!-- END Recommended Items Section -->

                  </div>
                </div>
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
  <script>
    $(document).ready(function() {
      new DataTable('#availableFoodItemsDataTable', {

        lengthMenu: [5, 10, 25, 50, 100]
      });
    });
  </script>

</body>

</html>