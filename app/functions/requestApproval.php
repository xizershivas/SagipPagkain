<?php
function getAllBeneficiaryRequests($conn, $intUserId) {
    $sql = "SELECT 
            BR.*
            , B.strName
            , P.strPurpose
            FROM tblbeneficiaryrequest BR
            INNER JOIN tblbeneficiary B
                ON BR.intBeneficiaryId = B.intBeneficiaryId
            INNER JOIN tblpurpose P
                ON BR.intPurposeId = P.intPurposeId
            INNER JOIN tblfoodbankdetail FBD
                ON BR.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblfoodbank FB
                ON FBD.intFoodBankId = FB.intFoodBankId
            INNER JOIN tbluser U
            	ON FB.intFoodBankId = U.intFoodBankId
            WHERE BR.ysnSubmitted = 1
            AND U.intUserId = $intUserId
        ";

    $allBeneficiaryRequests = $conn->query($sql);
    return $allBeneficiaryRequests;
}

function approveRequest($conn, $intBeneficiaryRequestId) {
    header('Content-Type: application/json');

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE tblbeneficiaryrequest SET intApproved = 1 WHERE intBeneficiaryRequestId = ?");
        $stmt->bind_param("i", $intBeneficiaryRequestId);

        if (!$stmt->execute()) {
            throw new Exception('Database failed to process request', 500);
        }

        $conn->commit();
 
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Request approved successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to approve request. " . $ex->getMessage()]]);
    }
}

function rejectRequest($conn, $intBeneficiaryRequestId) {
    header('Content-Type: application/json');

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE tblbeneficiaryrequest SET intApproved = 2 WHERE intBeneficiaryRequestId = ?");
        $stmt->bind_param("i", $intBeneficiaryRequestId);

        if (!$stmt->execute()) {
            throw new Exception('Database failed to process request', 500);
        }

        $conn->commit();
 
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Request rejected successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to reject request. " . $ex->getMessage()]]);
    }
}

function readyRequest($conn, $intBeneficiaryRequestId) {
    header('Content-Type: application/json');

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE tblbeneficiaryrequest SET intApproved = 3 WHERE intBeneficiaryRequestId = ?");
        $stmt->bind_param("i", $intBeneficiaryRequestId);

        if (!$stmt->execute()) {
            throw new Exception('Database failed to process request', 500);
        }

        $conn->commit();
 
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Request rejected successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to reject request. " . $ex->getMessage()]]);
    }
}
?>