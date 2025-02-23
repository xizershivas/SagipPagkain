<?php
include "../config/db_connection.php";
include "../functions/volunteerManagement.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intVolunteerId = intval(sanitize($_GET["intVolunteerId"]));
    editVolunteer($conn, $intVolunteerId);
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intVolunteerId = intval($_POST["volunteerId"]);
    $strFirstName = sanitize($_POST["firstname"]);
    $strLastName = sanitize($_POST["lastname"]);
    $strGender = sanitize($_POST["gender"]);
    $dtmDateOfBirth = sanitize($_POST["birthdate"]);
    $strStreet = sanitize($_POST["street"]);
    $strAddress = sanitize($_POST["address"]);
    $strCity = sanitize($_POST["city"]);
    $strRegion = sanitize($_POST["region"]);
    $strZipCode = sanitize($_POST["zipcode"]);
    $strCountry = sanitize($_POST["country"]);
    $strContact = sanitize($_POST["contact"]);
    $strEmail = sanitize($_POST["email"]);
    $strSignUploaded = isset($_POST["signUploaded"]) ? sanitize($_POST["signUploaded"]) : "";
    $strSignFilePath;

    // SIGNATURE UPLOAD
    if (empty($strSignUploaded)) {
        $strSignFilePath = processSignFileUpload($intVolunteerId);
    }

    $volunteerData = [
        "intVolunteerId" => $intVolunteerId,
        "strFirstName" => $strFirstName,
        "strLastName" => $strLastName,
        "strGender" => $strGender,
        "dtmDateOfBirth" => $dtmDateOfBirth,
        "strStreet" => $strStreet,
        "strAddress" => $strAddress,
        "strCity" => $strCity,
        "strRegion" => $strRegion,
        "strZipCode" => $strZipCode,
        "strCountry" => $strCountry,
        "strContact" => $strContact,
        "strEmail" => $strEmail,
        "strSignFilePath" => empty($strSignUploaded) ? $strSignFilePath : $strSignUploaded
    ];

    updateVolunteer($conn, $volunteerData);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $data = json_decode($inputData);

    deleteVolunteer($conn, $data->intVolunteerId);

    $conn->close();
}
?>