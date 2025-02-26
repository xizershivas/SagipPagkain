<?php
include "../config/db_connection.php";
include "../functions/donate.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intUserId = intval($_POST["userId"]);
    $strDonorName = sanitize($_POST["fullname"]);
    $dtmDate = sanitize($_POST["date"]);
    $strTitle = sanitize($_POST["title"]);
    $strDescription = sanitize($_POST["description"]);
    $intFoodBankId = intval($_POST["foodBank"]);
    $strItem = sanitize($_POST["item"]);
    $intQuantity = intval($_POST["quantity"]);
    $strCategory = sanitize($_POST["category"]);
    $strUnit = sanitize($_POST["unit"]);
    $strRemarks = sanitize($_POST["remarks"]);

    // File upload JPG/PNG
    $strDocFilePath = processDocFileUpload($intUserId);

    $donationData = [
        "intUserId" => $intUserId,
        "strDonorName" => $strDonorName,
        "dtmDate" => $dtmDate,
        "strTitle" => $strTitle,
        "strDescription" => $strDescription,
        "intFoodBankId" => $intFoodBankId,
        "strItem" => $strItem,
        "intQuantity" => $intQuantity,
        "strCategory" => $strCategory,
        "strUnit" => $strUnit,
        "strDocFilePath" => $strDocFilePath ? implode(",", $strDocFilePath) : "",
        "strRemarks" => $strRemarks
    ];

    addDonation($conn, $donationData);

    $conn->close();
}
?>