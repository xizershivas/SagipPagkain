<?php
include "../config/db_connection.php";
include "../utils/sanitize.php";
include "../functions/signup.php";

function getCoordinates($address)
{
    $apiKey = 'AIzaSyA5gmcyR_6vQ7VtfIt1cKlfmKG2iHFDNBs';
    $address = urlencode($address);

    if (!empty($address)) {
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
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $address = $conn->real_escape_string($_POST['address']);
    $coords = getCoordinates($address);

    $strUsername = sanitize($_POST["username"]);
    $strFullName = sanitize($_POST["fullname"]);
    $strContact = sanitize($_POST["contact"]);
    $strEmail = sanitize($_POST["email"]);
    $strPassword = sanitize($_POST["password"]);
    $strConfirmPassword = sanitize($_POST["confirmPassword"]);
    $strAccountType = sanitize($_POST["accountType"]);
    $strAddress = "";
    $dblSalary = 0;
    $latitude = "";
    $longitude = "";

    if ($strAccountType == "beneficiary") {
        $strAddress = sanitize($_POST["address"]);
        $latitude = $coords['latitude'];
        $longitude = $coords['longitude'];
        $dblSalary = floatval($_POST["monthlyincome"]);   
    }

    $uploadFilePath = uploadRequestDocument($strUsername);
    
    register($conn, $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strConfirmPassword, $strAccountType, $strAddress,$latitude,$longitude, $dblSalary, $uploadFilePath);
    
    $conn->close();
}
?>