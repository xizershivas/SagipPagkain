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
            BR.intBeneficiaryRequestId,
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

function deleteBeneficiaryRequest($conn, $intBeneficiaryRequestId) {
    header('Content-Type: application/json');

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM tblbeneficiaryrequest WHERE intBeneficiaryRequestId = ?");
        $stmt->bind_param("i", $intBeneficiaryRequestId);

        if (!$stmt->execute()) {
            throw new Exception('Database failed to process request', 500);
        }

        $stmt2 = $conn->prepare("DELETE FROM tblbeneficiaryrequestdetail WHERE intBeneficiaryRequestId = ?");
        $stmt2->bind_param("i", $intBeneficiaryRequestId);
        $stmt2->execute();

        $conn->commit();
 
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Beneficiary Request deleted successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to submit request. " . $ex->getMessage()]]);
    }
}
?>