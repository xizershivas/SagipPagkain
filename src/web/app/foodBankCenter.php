<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/user.php";


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
    $foodBankName = $conn->real_escape_string($_POST['foodBankName']);
    $address = $conn->real_escape_string($_POST['address']);

    $coords = getCoordinates($address);

    if ($coords !== false) {
        $latitude = $coords['latitude'];
        $longitude = $coords['longitude'];

        $sql = "INSERT INTO tblfoodbank (strFoodBank, strAddress, dblLatitude, dblLongitude) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdd", $foodBankName, $address, $latitude, $longitude);
        
        if ($stmt->execute()) {
            $message = "Food Bank added successfully!";
        } else {
            $message = "Error inserting data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Could not get coordinates for the address.";
    }
}

// Get food bank data with item counts
$foodBankQuery = "SELECT fb.intFoodBankId, fb.strFoodBank, fb.dblLatitude, fb.dblLongitude,fb.strAddress,
                  COUNT(DISTINCT i.intItemId) as itemCount,
                  SUM(i.intQuantity) as totalStock
                  FROM tblfoodbank fb
                  LEFT JOIN tblinventory i ON fb.intFoodBankId = i.intFoodBankId
                  GROUP BY fb.intFoodBankId, fb.strFoodBank, fb.dblLatitude, fb.dblLongitude";
$foodBankResult = mysqli_query($conn, $foodBankQuery);

$foodBanks = array();

while ($row = mysqli_fetch_assoc($foodBankResult)) {
    // Get detailed inventory
    $inventoryQuery = "SELECT DISTINCT SUM(i.intQuantity) AS intQuantity, it.strItem, u.strUnit
    FROM tblinventory i 
    JOIN tblitem it ON i.intItemId = it.intItemId 
    JOIN tblunit u ON i.intUnitId = u.intUnitId 
    WHERE i.intFoodBankId = " . $row['intFoodBankId'] . "
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
        'name' => $row['strFoodBank'],
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
            <li class="current"><?php echo isset($_SESSION['ysnStaff']) && $_SESSION['ysnStaff'] == 1 ? 'Staff' : 'Admin'; ?></li>
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
                <!--<a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>-->
                <!--<a href="trackDonation.php"><i class="bi bi-arrow-left-right"></i></i><span>Track Donation</span></a>-->
                <!-- <a href="volunteerManagement.php" class=""><i class="bi bi-people"></i><span>Volunteer Management</span></a> -->
                <a href="foodBankCenter.php" class="active"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <!--<a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>-->
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

          <div class="col-lg-9 ps-lg-5 tbl table-donor mt-0" data-aos="fade-up" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-center" style="color: #333;">Item Stock Map</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFoodBankModal">
                    Add Food Bank
                </button>
            </div>
            <!-- DATA GRAPH -->
            <div class="card p-3 shadow-sm">
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

  <!-- Include global footer  -->
  <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

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
    var locations = <?php echo json_encode($foodBanks); ?>;
    var locationList = document.getElementById("locationList");
    var currentFoodBankId = null;
   
    locations.forEach(location => {

    var marker = L.marker([location.lat, location.lng]).addTo(map);
    
    marker.bindPopup(`
        <div class="text-left">
            <h6 class="mb-1"><b>${location.address?.split(",")[0].trim()}</b></h6>
            <p class="mb-1">Total Items: ${location.itemCount}</p>
            <p class="mb-1">Total Quantity: ${location.stock}</p>
            <div class="d-flex gap-2 mt-2">
               <button class="btn btn-sm btn-warning" onclick="openEditModal('${location.id}', '${(location.strAddress || '').split(',')[0].trim()}')">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteFoodBank('${location.id}')">Delete</button>
                <a href="#" 
                   class="btn btn-sm btn-primary stock-link" 
                   data-bs-toggle="modal" 
                   data-bs-target="#stockModal"
                   data-id="${location.id}"
                   data-items='${JSON.stringify(location.items)}' 
                   data-location="${(location.strAddress || '').split(',')[0].trim()}" style="color: #fff;">
                   View Stock
                </a>
            </div>
        </div>
    `);


        // Add to list
        var listItem = document.createElement("li");
        listItem.className = "list-group-item";
        listItem.innerHTML = `<span class="pin-icon">📍</span> ${location.address}`;
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


 <!-- Add Food Bank Modal -->
<div class="modal fade" id="addFoodBankModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Food Bank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($message)): ?>
          <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" id="foodBankForm">
          <div class="mb-3">
            <label for="foodBankName" class="form-label">Food Bank Name:</label>
            <input type="text" class="form-control" id="foodBankName" name="foodBankName" placeholder="Enter Food Bank Name" required />
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address of the Foodbank" required />
          </div>

          <!-- Move buttons inside the form -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Food Bank</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

  <!-- Edit Food Bank Modal -->
  <div class="modal fade" id="editFoodBankModal" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Edit Food Bank</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <form id="editFoodBankForm">
                      <input type="hidden" id="editFoodBankId">
                      <div class="mb-3" style="color: #333;">
                          <label for="editStrFoodBank" class="form-label">Food Bank Address</label>
                          <input type="text" class="form-control" id="editStrFoodBank" required>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="updateFoodBank()">Update Food Bank</button>
              </div>
          </div>
      </div>
  </div>

  <!-- Stock Details Modal -->
  <div class="modal fade" id="stockModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-scro  llable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Stock Details - <span id="modalLocationName"></span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <table class="table table-bordered table-sm">
                      <thead>
                          <tr>
                              <th>Item</th>
                              <th>Quantity</th>
                              <th>Unit</th>
                          </tr>
                      </thead>
                      <tbody id="modalStockTableBody"></tbody>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5gmcyR_6vQ7VtfIt1cKlfmKG2iHFDNBs&libraries=places"></script>

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
  
</body>

</html>