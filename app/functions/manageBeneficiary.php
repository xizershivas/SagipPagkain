<?php
function getBeneficiaryData($conn) {
    $allBeneficiaryData = $conn->query("SELECT * FROM tblbeneficiary");
    return $allBeneficiaryData;
}

function editBeneficiary($conn, $intBeneficiaryId) {
    if (!filter_var($intBeneficiaryId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $query = $conn->prepare("SELECT * FROM tblbeneficiary WHERE intBeneficiaryId = ?");

        if (!$query) {
            http_response_code(500);
            echo json_encode(array("data" => array("message" => "Database operation failed")));
            exit();
        }

        $query->bind_param("i", $intBeneficiaryId);
        $query->execute();
        $result = $query->get_result();
    
        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(array("data" => array("message" => "Record does not exist")));
            $query->close();
            exit();
        }
    
        $data = $result->fetch_object();

        http_response_code(200);
        echo json_encode(array("data" => $data));
        $query->close();
    }
}

function deleteBeneficiary($conn, $intBeneficiaryId) {
    $query = $conn->prepare("DELETE FROM tblbeneficiary WHERE intBeneficiaryId = ?");
    $query->bind_param("i", $intBeneficiaryId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(array("data" => array("message" => "Beneficiary was deleted successfully")));
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}
?>