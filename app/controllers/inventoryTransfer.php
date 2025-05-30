<?php
include "../config/db_connection.php";
include "../functions/inventoryTransfer.php";
include "../utils/sanitize.php";

// Get all available items in the selected Food Bank
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["foodBankId"])) {
    $intFoodBankId = intval($_GET["foodBankId"]);
    getAvailableItems($conn, $intFoodBankId);
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intInventoryId = intval($_POST["inventoryId"]);
    $intDonationId = intval($_POST["donationId"]);
    $intSourceFoodBankId = intval($_POST["sourceFoodBank"]);
    $intTargetFoodBankId = intval($_POST["targetFoodBank"]);
    $intItemId = intval($_POST["item"]);
    $intCategoryId = intval($_POST["category"]);
    $intUnitId = intval($_POST["unit"]);
    $intAvailableQty = intval($_POST["availableQty"]);
    $dtmExpirationDate = $_POST["expirationDate"];
    $intTransferQty = intval($_POST["transferQty"]);

    $data = [
        "intInventoryId" => $intInventoryId,
        "intDonationId" => $intDonationId,
        "intSourceFoodBankId" => $intSourceFoodBankId,
        "intTargetFoodBankId" => $intTargetFoodBankId,
        "intItemId" => $intItemId,
        "intCategoryId" => $intCategoryId,
        "intUnitId" => $intUnitId,
        "intAvailableQty" => $intAvailableQty,
        "dtmExpirationDate" => $dtmExpirationDate,
        "intTransferQty" => $intTransferQty
    ];

    saveInventoryTransferDetails($conn, $data);
    
    $conn->close();
}
?>