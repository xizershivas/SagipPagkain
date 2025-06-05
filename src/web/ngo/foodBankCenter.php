<?php
session_start();
include "../../../app/config/db_connection.php"; 
include "../../../app/functions/user.php";
include "../../../app/utils/sanitize.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFoodBankId'])) {
  header('Content-Type: application/json');

  $deleteId = intval($_POST['deleteFoodBankId']);

  $conn->query("DELETE FROM tblinventory WHERE intFoodBankId = $deleteId");

  if ($conn->query("DELETE FROM tblfoodbank WHERE intFoodBankId = $deleteId")) {
      echo json_encode(["status" => "success", "message" => "Food Bank deleted successfully."]);
  } else {
      echo json_encode(["status" => "error", "message" => "Error deleting food bank."]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foodBankId']) && isset($_POST['foodBankName'])) {
  $id = intval($_POST['foodBankId']);
  $name = $conn->real_escape_string($_POST['foodBankName']);

  $sql = "UPDATE tblfoodbank SET strMunicipality = '$name' WHERE intFoodBankId = $id";

  if ($conn->query($sql)) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
  }
  exit;
}


function getCoordinates($address)
{
    $apiKey = 'AIzaSyA5gmcyR_6vQ7VtfIt1cKlfmKG2iHFDNBs';
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['status'] == 'OK') {
        $latitude = $data['results'][0]['geometry']['location']['lat'];
        $longitude = $data['results'][0]['geometry']['location']['lng'];
        return array('latitude' => $latitude, 'longitude' => $longitude);
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');

    $conn->begin_transaction();

    try {
      $municipality = sanitize($_POST['municipality']);

      $checkStmt = $conn->prepare("SELECT * FROM tblfoodbank WHERE strMunicipality = ? LIMIT 1");
      $checkStmt->bind_param("s", $municipality);
      $checkStmt->execute();
      $result = $checkStmt->get_result();
      $intFoodBankId = 0;
      $lastInsertId = 0;

      if ($result->num_rows > 0) {
        $intFoodBankId = $result->fetch_object()->intFoodBankId;
      } else {
        // Insert into tblfoodbank
        $sql = "INSERT INTO tblfoodbank (strMunicipality) VALUES (?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Database operation failed", 500);
        $stmt->bind_param("s", $municipality);
        $stmt->execute();
        $intFoodBankId = $conn->insert_id;
        $stmt->close();
      }

      if (isset($_POST['foodBankName'], $_POST['address']) && 
          is_array($_POST['foodBankName']) && 
          is_array($_POST['address']) && 
          count($_POST['foodBankName']) === count($_POST['address'])) {

          $sql2 = "INSERT INTO tblfoodbankdetail (intFoodBankId, strFoodBankName, strAddress, dblLatitude, dblLongitude) 
                  VALUES (?, ?, ?, ?, ?)";
          $stmt2 = $conn->prepare($sql2);

          for ($i = 0; $i < count($_POST['foodBankName']); $i++) {
              $foodBankName = sanitize($_POST['foodBankName'][$i]);
              $address = sanitize($_POST['address'][$i]);

              // Get coordinates per address
              $coords = getCoordinates($address);
              if ($coords !== false) {
                  $latitude = $coords['latitude'];
                  $longitude = $coords['longitude'];

                  $stmt2->bind_param("issdd", $intFoodBankId, $foodBankName, $address, $latitude, $longitude);
                  $stmt2->execute();
              }
          }

          $stmt2->close();
      }

      $conn->commit();
  } catch (Exception $ex) {
    $conn->rollback();
    $code = $ex->getCode();
    http_response_code($code);
    echo json_encode(["data" => ["message" => $ex->getMessage()]]);
  }
}
$userId = $_SESSION["intUserId"];
// Get food bank data with item counts
$foodBankQuery = "SELECT fb.intFoodBankId, fbd.intFoodBankDetailId, fbd.strFoodBankName, fbd.dblLatitude, fbd.dblLongitude, fbd.strAddress,
                  COUNT(DISTINCT i.intItemId) as itemCount,
                  SUM(i.intQuantity) as totalStock
                  FROM tblfoodbank fb
                  LEFT JOIN tblfoodbankdetail fbd on fb.intFoodBankId = fbd.intFoodBankId
                  LEFT JOIN tblinventory i ON fbd.intFoodBankDetailId = i.intFoodBankDetailId
                  LEFT JOIN tbluser U ON fb.intFoodBankId = U.intFoodbankId
				  WHERE fb.intFoodBankId = U.intFoodbankId AND U.intUserId = '$userId'
                  GROUP BY fb.intFoodBankId, fbd.strFoodBankName, fbd.dblLatitude, fbd.dblLongitude";
$foodBankResult = mysqli_query($conn, $foodBankQuery);

$foodBanks = array();



$foodBankDetailQuery = "SELECT * FROM tblfoodbankdetail tfbd LEFT JOIN tblfoodbank tfb ON tfbd.intFoodBankId = tfb.intFoodBankId";
$foodBankDetialResult = mysqli_query($conn, $foodBankDetailQuery);

$foodBanksDetail = array();

while ($row = mysqli_fetch_assoc($foodBankResult)) {
    // Get detailed inventory
    $inventoryQuery = "SELECT DISTINCT SUM(i.intQuantity) AS intQuantity, it.strItem, u.strUnit
    FROM tblinventory i 
    JOIN tblitem it ON i.intItemId = it.intItemId 
    JOIN tblunit u ON i.intUnitId = u.intUnitId 
    WHERE i.intFoodBankDetailId = " . $row['intFoodBankDetailId'] . "
    GROUP BY  it.strItem, u.strUnit";


    
    $inventoryResult = mysqli_query($conn, $inventoryQuery);
    $inventory = array();
    
    while ($invRow = mysqli_fetch_assoc($inventoryResult)) {
        $inventory[] = array(
            'name' => $invRow['strItem'],
            'quantity' => $invRow['intQuantity'],
            'unit' => $invRow['strUnit']
        );
    }
    
    $foodBanks[] = array(
        'id' => $row['intFoodBankId'],
        'name' => $row['strFoodBankName'],
        'lat' => $row['dblLatitude'],
        'lng' => $row['dblLongitude'],
        'address' => $row['strAddress'],
        'itemCount' => $row['itemCount'],
        'stock' => $row['totalStock'] ?? 0,
        'items' => $inventory
    );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Food Bank Center</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Data Table CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <!-- Include stylesheet -->
  <?php include '../global/stylesheet.php'; ?>

  <link href="../../../app/css/foodCenter.css" rel="stylesheet">
</head>

<style>
  .pac-container {
    z-index: 2000 !important;
  }
</style>

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
            <li class="current"><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Food Bank'; ?></li>
            <li><a href="foodBankCenter.php">Food Bank Center</a></li>
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
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Food Donation Management</span></a>
                <a href="foodBankCenter.php" class="active"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
                <a href="requestApproval.php" class=""><i class="bi bi-people"></i><span>Request for Approval</span></a>
                <!--<a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>-->
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

          <div class="col-lg-9 ps-lg-5 tbl table-donor mt-0" data-aos="fade-up" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-center" style="color: #333;">Item Stock Map</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFoodBankModal">
                    Add Food Bank
                </button>
            </div>
            <!-- DATA GRAPH -->
            <div class="card shadow-sm" style="margin-bottom: 4%;">
                <div class="d-flex"> 
                    <div class="col-lg-9"> 
                       <div id="map"></div>
                    </div>
                    <div class="col-lg-3"> 
                        <div id="sidebar">
                            <h3 class="text-center mb-3">Food Stock Areas</h3>
                            <!-- Search Bar -->
                            <div id="search-container">
                                <input type="text" id="searchBox" class="form-control" placeholder="Search for a location..." onkeyup="filterLocations()">
                            </div>
                            <ul class="list-group" id="locationList"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MAP GRAPH> -->
          </div>

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>
  <!-- Preloader -->
  <div id="preloader"></div>

 <!-- Add Food Bank Modal -->
<div class="modal fade" id="addFoodBankModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="color: #333;">
      <div class="modal-header">
        <h5 class="modal-title" style="color: #333;">Add Food Bank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($message)): ?>
          <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" id="foodBankForm">
              <div class="mb-3">
                  <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label for="municipality" class="form-label"><b>Municipality:</b></label>
                    <span id="addMore" style="color: #3f3737; cursor: pointer; font-size: 35px;font-weight: 900; padding-right: 5px;">+</span>
                  </div>
                  <input type="text" class="form-control" name="municipality" placeholder="Enter Municipality" required />
                </div>
              <div id="foodBankFormContainer">
                <div class="mb-3">
                  <label for="foodBankName" class="form-label">Food Bank Name:</label>
                  <input type="text" class="form-control" name="foodBankName[]" placeholder="Enter Food Bank Name" required />
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">Address:</label>
                  <input type="text" class="form-control" name="address[]" placeholder="Enter Address of the Foodbank" required />
                </div>
              </div> 
              
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnSaveFoodBank">Save Food Bank</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

  <!-- Stock Details Modal -->
  <div class="modal fade" id="stockModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Stock Details - <span id="modalLocationName"></span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <table class="table">
                      <thead>
                          <tr>
                              <th>Item</th>
                              <th>Quantity</th>
                              <th>Unit</th>
                          </tr>
                      </thead>
                      <tbody id="modalStockTableBody">
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5gmcyR_6vQ7VtfIt1cKlfmKG2iHFDNBs&libraries=places"></script>

<script>
  let fieldCount = 1;

  function initAutocompleteForInput(input) {
    if (google.maps.places) {
      new google.maps.places.Autocomplete(input);
    }
  }

  // Initialize for first address field on page load
  window.addEventListener('load', function () {
    const firstAddressInput = document.querySelector('input[name="address[]"]');
    if (firstAddressInput) {
      initAutocompleteForInput(firstAddressInput);
    }
  });

  document.getElementById('addMore').addEventListener('click', function () {
    const container = document.getElementById('foodBankFormContainer');

    const newFields = document.createElement('div');
    newFields.innerHTML = `
      <hr />
      <div class="mb-3">
        <label for="foodBankName_${fieldCount}" class="form-label">Food Bank Name:</label>
        <input type="text" class="form-control" name="foodBankName[]" id="foodBankName_${fieldCount}" placeholder="Enter Food Bank Name" required />
      </div>
      <div class="mb-3">
        <label for="address_${fieldCount}" class="form-label">Address:</label>
        <input type="text" class="form-control" name="address[]" id="address_${fieldCount}" placeholder="Enter Address of the Foodbank" required />
      </div>
    `;

    container.appendChild(newFields);

    // Get the new address input and apply Google Places Autocomplete
    const newAddressInput = newFields.querySelector(`#address_${fieldCount}`);
    initAutocompleteForInput(newAddressInput);

    fieldCount++;
  });
</script>

  <script>
    // Initialize Google Maps Places Autocomplete
    function initAutocomplete() {
      const addressInputs = ['address', 'updateAddress'];
      
      addressInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
          const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            componentRestrictions: { country: "ph" }
          });
        }
      });
    }

    // Initialize autocomplete when the page loads
    google.maps.event.addDomListener(window, 'load', initAutocomplete);

    // Delete Food Bank Function
    async function deleteFoodBank(foodBankId) {
      if (!confirm('Are you sure you want to delete this food bank?')) return;

      try {
        const formData = new FormData();
        formData.append('deleteFoodBankId', foodBankId);

        const response = await fetch(window.location.href, {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
          alert(data.message);
          window.location.reload();
        } else {
          throw new Error(data.message || 'Failed to delete food bank');
        }
      } catch (error) {
        alert(error.message);
      }
    }
  </script>

<script>
    function initAutocomplete() {
      const input = document.getElementById('address');
      const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode'],
        componentRestrictions: { country: "ph" } 
      });
    }
    google.maps.event.addDomListener(window, 'load', initAutocomplete);
  </script>

    <!-- Include global footer  -->
    <?php include '../global/footer.php'; ?>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Include global JS -->
<?php include '../global/script.php'; ?>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  // Initialize the map and set to Laguna
  var map = L.map('map').setView([14.2044, 121.3473], 10);

  // Light-themed map
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Stock level colors
  function getStockColor(stock) {
      if (stock >= 80) return "red";    
      if (stock >= 30) return "yellow"; 
      return "green";                   
  }

  // Use PHP data directly
  var locations = <?php echo json_encode($foodBanks) ?>;
  var locationList = document.getElementById("locationList");
  var currentFoodBankId = null;
 
  locations.forEach(location => {

  var marker = L.marker([location.lat, location.lng]).addTo(map);
  
  marker.bindPopup(`
      <div class="text-left">
          <h6 class="mb-1"><b>${location.name}</b></h6>
          <p class="mb-1">Total Items: ${location.itemCount}</p>
          <p class="mb-1">Total Quantity: ${location.stock}</p>
          <div class="d-flex gap-2 mt-2">
              <button class="btn btn-sm btn-danger" onclick="deleteFoodBank('${location.id}')">Delete</button>
              <a href="#" 
                 class="btn btn-sm btn-primary stock-link" 
                 data-bs-toggle="modal" 
                 data-bs-target="#stockModal"
                 data-id="${location.id}"
                 data-items='${JSON.stringify(location.items)}' 
                 data-location="${location.name}" style="color: #fff;">
                 View Stock
              </a>
          </div>
      </div>
  `);


      // Add to list
      var listItem = document.createElement("li");
      listItem.className = "list-group-item";
      listItem.innerHTML = `<span class="pin-icon">📍</span> ${location.name}`;
      listItem.onclick = function () {
          map.setView([location.lat, location.lng], 13);
      };
      locationList.appendChild(listItem);

      // Add circle
      L.circle([location.lat, location.lng], {
          color: getStockColor(location.stock),
          fillColor: getStockColor(location.stock),
          fillOpacity: 0.5,
          radius: 300
      }).addTo(map);
  });

  // Handle modal display
  document.addEventListener('click', function (e) {
      if (e.target.closest('.stock-link')) {
          const link = e.target.closest('.stock-link');
          const items = JSON.parse(link.dataset.items);
          const locationName = link.dataset.location;
          currentFoodBankId = link.dataset.id;

          document.getElementById('modalLocationName').textContent = locationName;

          const tbody = document.getElementById('modalStockTableBody');
          tbody.innerHTML = '';

          items.forEach(item => {
              const row = `<tr>
                  <td>${item.name}</td>
                  <td>${item.quantity}</td>
                  <td>${item.unit}</td>
              </tr>`;
              tbody.insertAdjacentHTML('beforeend', row);
          });
      }
  });

  // Search functionality
  function filterLocations() {
      var input = document.getElementById("searchBox").value.toLowerCase(); 
      var listItems = document.querySelectorAll(".list-group-item");

      listItems.forEach(item => {
          if (item.textContent.toLowerCase().includes(input)) {
              item.style.display = "";
          } else {
              item.style.display = "none";
          }
      });
  }
</script>
</body>
</html>