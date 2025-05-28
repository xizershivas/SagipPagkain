<?php
include "../config/db_connection.php";
include "../functions/requestStatus.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["reqId"])) {
    $reqId = intval($_GET["reqId"]);
    getRequestDate($conn, $reqId);
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $intBeneficiaryRequestId = intval($data['intBeneficiaryRequestId']);
    deleteBeneficiaryRequest($conn, $intBeneficiaryRequestId);
    $conn->close();
}

?>