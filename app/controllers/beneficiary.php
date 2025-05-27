<?php
include "../config/db_connection.php";
include "../functions/beneficiary.php";
include "../utils/sanitize.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beneficiaryId = intval($_POST['beneficiaryId']);
    $requestType = sanitize($_POST['requestType']);
    $itemsNeeded = $_POST['itemsNeeded'];
    $urgencyLevel = sanitize($_POST['urgencyLevel']);
    $pickupDate = $_POST['pickupDate'];
    $description = sanitize($_POST['description']);
    $notes = sanitize($_POST['notes']);

    $uploadFilePath = uploadRequestDocument($beneficiaryId);

    $requestData = [
        'beneficiaryId' => $beneficiaryId,
        'requestType' => $requestType,
        'itemsNeeded' => $itemsNeeded,
        'urgencyLevel' => $urgencyLevel,
        'pickupDate' => $pickupDate,
        'document' => $uploadFilePath,
        'description' => $description,
        'notes' => $notes
    ];

    submitBeneficiaryRequest($conn, $requestData);

    $conn->close();
}
?>