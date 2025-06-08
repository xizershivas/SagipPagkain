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
    $intDonationId = isset($data->intDonationId) ? intval($data->intDonationId) : '';
    $_method = isset($data->_method) ? sanitize($data->_method) : '';
    $ysnArchive = isset($data->ysnArchive) ? intval($data->ysnArchive) : '';

    if ($intDonationId && $_method && $_method == "PUT" && $ysnArchive) {
        archiveDonation($conn, $intDonationId);
    }
    else if ($intDonationId && $_method && $_method === "PUT" && !$ysnArchive) {
        unarchiveDonation($conn, $intDonationId);
    }
    else {
        $intDonationId = intval($_POST["donationId"]);
        $strFullName = sanitize($_POST["donor"]);
        $dtmDate = sanitize($_POST["date"]);
        $dtmExpirationDate = sanitize($_POST["expirationDate"]);
        $intFoodBankDetailId = intval($_POST["foodBank"]);
        $strDescription = sanitize($_POST["description"]);
        $intPurposeId = intval($_POST["purpose"]);
        $intItemId = intval($_POST["itemFood"]);
        $intQuantity = intval($_POST["quantity"]);
        $intUnitId = intval($_POST["unit"]);
        $intCategoryId = intval($_POST["category"]);
        $ysnStatus = isset($_POST["transportStatus"]) ? 1 : 0;
        $strDocsUploadedPaths = isset($_POST["docsUploadedPaths"]) ? $_POST["docsUploadedPaths"] : "";

        $strDocFilePath = processDocFileUpload($conn, $intDonationId);

        $donationData = [
            "intDonationId" => $intDonationId,
            "strFullName" => $strFullName,
            "dtmDate" => $dtmDate,
            "intFoodBankDetailId" => $intFoodBankDetailId,
            "strDescription" => $strDescription,
            "intPurposeId" => $intPurposeId,
            "intItemId" => $intItemId,
            "intQuantity" => $intQuantity,
            "intUnitId" => $intUnitId,
            "intCategoryId" => $intCategoryId,
            "ysnStatus" => $ysnStatus,
            "dtmExpirationDate" => $dtmExpirationDate,
            "strDocFilePath" => $strDocFilePath ? implode(",", $strDocFilePath) : $strDocsUploadedPaths
        ];

        updateDonation($conn, $donationData);
    }

    $conn->close();
}
?>