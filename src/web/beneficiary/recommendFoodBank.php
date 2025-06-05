<?php
session_start();

while (ob_get_level()) {
    ob_end_clean();
}
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
 
header('Content-Type: application/json');

include "../../../app/config/db_connection.php";
include "../../../app/functions/recommendFoodBank.php";

$beneficiaryId = $_POST['beneficiaryId'] ?? null;
$itemIds = $_POST['itemIds'] ?? [];

if (!isset($_SESSION["intUserId"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if (!is_array($itemIds)) {
    $itemIds = [$itemIds];
}

if (count($itemIds) === 0) {
    echo json_encode(["success" => false, "message" => "No items requested"]);
    exit;
}

$itemIds = array_map('intval', $itemIds);
$beneficiaryId = intval($beneficiaryId);


$bestFoodBank = recommendFoodBank($conn, $beneficiaryId, $itemIds);

if ($bestFoodBank) {
    echo json_encode(["success" => true, "foodBank" => $bestFoodBank]);
} else {
    echo json_encode(["success" => false, "message" => "No suitable food bank found"]);
}

exit;

