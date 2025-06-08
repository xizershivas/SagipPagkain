<?php
include "../config/db_connection.php";
include "../functions/requestApproval.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    $intBeneficiaryRequestId = intval($data->requestId);
    $isReject = isset($data->isReject) ? intval($data->isReject) : 0;
    $isPickup = isset($data->isPickup) ? intval($data->isPickup) : 0;

    if (!$isReject && !$isPickup) {
        approveRequest($conn, $intBeneficiaryRequestId);
    }
    else if (!$isReject && $isPickup) {
        readyRequest($conn, $intBeneficiaryRequestId);
    }
    else {
        rejectRequest($conn, $intBeneficiaryRequestId);
    }

    $conn->close();
}
?>