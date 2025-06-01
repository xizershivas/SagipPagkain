<?php
include "../config/db_connection.php";
include "../functions/requestApproval.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    $intBeneficiaryRequestId = intval($data->requestId);
    approveRequest($conn, $intBeneficiaryRequestId);
    $conn->close();
}
?>