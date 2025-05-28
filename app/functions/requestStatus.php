<?php
function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tblbeneficiary WHERE intUserId = $intUserId");
    return $userData;
}

function getAllRequestNo($conn, $intBeneficiaryId) {
    $allRequestNo = $conn->query("SELECT * FROM tblbeneficiaryrequest WHERE intBeneficiaryId = $intBeneficiaryId");
    return $allRequestNo;
}

function getRequestDate($conn, $reqId) {
    header('Content-Type: application/json');

    $stmt = $conn->prepare("SELECT dtmCreatedDate FROM tblbeneficiaryrequest WHERE intBeneficiaryRequestId = ?");
    $stmt->bind_param("i", $reqId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_object();

    if ($result) {
        http_response_code(200);
        echo json_encode(["data" => date('Y-m-d', strtotime($result->dtmCreatedDate))]);
    } else {
        http_response_code(404);
        echo json_encode(["data" => ["message" => "Failed to retrieve data"]]);
    }

    exit();
}

function getAllBeneficiaryRequest($conn, $intBeneficiaryId) {
    $stmt = $conn->prepare("
        SELECT
            BR.strRequestNo,
            I.strItem,
            BR.strRequestType,
            BR.strDescription,
            BR.dtmPickupDate,
            DATE_FORMAT(BR.dtmCreatedDate, '%Y-%m-%d') AS dtmCreatedDate,
            BR.ysnApproved
        FROM tblbeneficiaryrequest BR
        INNER JOIN tblbeneficiaryrequestdetail BRD
            ON BR.intBeneficiaryRequestId = BRD.intBeneficiaryRequestId
        INNER JOIN tblitem I
            ON BRD.intItemId = I.intItemId
        WHERE BR.intBeneficiaryId = ?
    ");
    $stmt->bind_param("i", $intBeneficiaryId);
    $stmt->execute();
    return $stmt->get_result();
}


?>