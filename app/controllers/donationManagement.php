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
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData);

    if ($data) {
        $intDonationId = intval($data->intDonationId);
        $ysnArchive = $data->ysnArchive;

        if ($intDonationId && $ysnArchive) {
            archiveDonation($conn, $intDonationId);
        } else if ($intDonationId && !$ysnArchive) {
            unarchiveDonation($conn, $intDonationId);
        }
    } else {
        $intDonationId = intval($_POST["donationId"]);
        $strDonorName = sanitize($_POST["donor"]);
        $dtmDate = sanitize($_POST["date"]);
        $intFoodBankId = sanitize($_POST["foodBank"]);
        $strTitle = sanitize($_POST["title"]);
        $strDescription = sanitize($_POST["description"]);
        $strRemarks = sanitize($_POST["remarks"]);
        $strItem = sanitize($_POST["itemFood"]);
        $intQuantity = intval($_POST["quantity"]);
        $strUnit = sanitize($_POST["unit"]);
        $strCategory = sanitize($_POST["category"]);
        $ysnStatus = isset($_POST["transportStatus"]) ? 1 : 0;
        $strDocsUploadedPaths = isset($_POST["docsUploadedPaths"]) ? $_POST["docsUploadedPaths"] : "";

        $strDocFilePath = processDocFileUpload($conn, $intDonationId);

        $donationData = [
            "intDonationId" => $intDonationId,
            "strDonorName" => $strDonorName,
            "dtmDate" => $dtmDate,
            "intFoodBankId" => $intFoodBankId,
            "strTitle" => $strTitle,
            "strDescription" => $strDescription,
            "strRemarks" => $strRemarks,
            "strItem" => $strItem,
            "intQuantity" => $intQuantity,
            "strUnit" => $strUnit,
            "strCategory" => $strCategory,
            "ysnStatus" => $ysnStatus,
            "strDocFilePath" => $strDocFilePath ? implode(",", $strDocFilePath) : $strDocsUploadedPaths
        ];

        updateDonation($conn, $donationData);
    }

    $conn->close();
}
?>