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
    $intSourceFoodBankId = intval($_POST["sourceFoodBank"]);
    $intTargetFoodBankId = intval($_POST["targetFoodBank"]);
    $intItemId = intval($_POST["item"]);
    $intAvailableQty = intval($_POST["availableQty"]);
    $strUnit = sanitize($_POST["itemUnit"]);
    $intTransferQty = intval($_POST["transferQty"]);

    $data = [
        "intSourceFoodBankId" => $intSourceFoodBankId,
        "intTargetFoodBankId" => $intTargetFoodBankId,
        "intItemId" => $intItemId,
        "intAvailableQty" => $intAvailableQty,
        "strUnit" => $strUnit,
        "intTransferQty" => $intTransferQty
    ];

    saveInventoryTransferDetails($conn, $data);
    // processInventoryTransfer($conn, $data);

    $conn->close();
}
?>