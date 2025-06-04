<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'sagippagkaindb';

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed. Error: ' . $conn->connect_error);
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
    $foodBankName = $conn->real_escape_string($_POST['foodBankName']);
    $address = $conn->real_escape_string($_POST['address']);

    $coords = getCoordinates($address);

    if ($coords !== false) {
        $latitude = $coords['latitude'];
        $longitude = $coords['longitude'];

        $sql = "INSERT INTO tblfoodbank (strMunicipality, strAddress, dblLatitude, dblLongitude) VALUES (?, ?, ?, ?)";
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8" />
  <title>Add Food Bank</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
  <section class="home-section">
    <div class="container py-5">
      <h2 class="mb-4">Add Food Bank</h2>

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
        <button type="submit" class="btn btn-primary">Add Food Bank</button>
      </form>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
