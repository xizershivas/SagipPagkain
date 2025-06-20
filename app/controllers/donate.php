<?php
include "../config/db_connection.php";
include "../functions/donate.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["itemId"]) && isset($_GET["ex"]) && isset($_GET["uid"])) {
    $intItemId = intval($_GET["itemId"]);
    $intUserId = intval($_GET["uid"]);
    getFoodbank($conn, $intItemId, $intUserId);
    $conn->close();
} 
else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["itemId"])) {
    $intItemId = intval($_GET["itemId"]);
    getItemDetails($conn, $intItemId);
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intUserId = intval($_POST["userId"]);
    $strDonorName = sanitize($_POST["fullname"]);
    $dtmDate = sanitize($_POST["date"]);
    $intFoodBankDetailId = intval($_POST["foodBank"]);
    $strDescription = sanitize($_POST["description"]);
    $intPurposeId = intval($_POST["purpose"]);
    $intItemId = intval($_POST["item"]);
    $intQuantity = intval($_POST["quantity"]);
    $intCategoryId = intval($_POST["category"]);
    $intUnitId = intval($_POST["unit"]);
    $dtmExpirationDate = sanitize($_POST["date2"]);

    // File upload JPG/PNG 
    $strDocFilePath = processDocFileUpload($intUserId);

    $donationData = [
        "intUserId" => $intUserId,
        "strDonorName" => $strDonorName,
        "dtmDate" => $dtmDate,
        "intFoodBankDetailId" => $intFoodBankDetailId,
        "strDescription" => $strDescription,
        "intPurposeId" => $intPurposeId,
        "intItemId" => $intItemId,
        "intQuantity" => $intQuantity,
        "intCategoryId" => $intCategoryId,
        "intUnitId" => $intUnitId,
        "strDocFilePath" => $strDocFilePath ? implode(",", $strDocFilePath) : "",
        "dtmExpirationDate" => $dtmExpirationDate
    ];

    addDonation($conn, $donationData);

    $conn->close();
}
?>