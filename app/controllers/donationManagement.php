<?php
include "../config/db_connection.php";
include "../functions/donationManagement.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intDonationId = intval(sanitize($_GET["intDonationId"]));
    editDonation($conn, $intDonationId);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intDonationId = $_POST["donationId"];
    $strDonorName = $_POST["donor"];
    $dtmDate = $_POST["date"];
    $strTitle = $_POST["title"];
    $strDescription = $_POST["description"];
    $strPickupLocation = $_POST["pickupLocation"];
    $strRemarks = $_POST["remarks"];
    $ysnStatus = isset($_POST["transportStatus"]) ? 1 : 0;
    $strDocsUploadedPaths = isset($_POST["docsUploadedPaths"]) ? $_POST["docsUploadedPaths"] : "";

    $strDocFilePath = processDocFileUpload($intDonationId);

    $donationData = [
        "intDonationId" => $intDonationId,
        "strDonorName" => $strDonorName,
        "dtmDate" => $dtmDate,
        "strTitle" => $strTitle,
        "strDescription" => $strDescription,
        "strPickupLocation" => $strPickupLocation,
        "strRemarks" => $strRemarks,
        "ysnStatus" => $ysnStatus,
        "strDocFilePath" => $strDocFilePath ? implode(",", $strDocFilePath) : $strDocsUploadedPaths
    ];

    updateDonation($conn, $donationData);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $data = json_decode($inputData);

    deleteDonation($conn, $data->intDonationId);

    $conn->close();
}
?>