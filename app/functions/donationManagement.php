<?php
function getDonationData($conn) {
    $allDonationData = $conn->query("SELECT * FROM tbldonationmanagement");
    return $allDonationData;
}

function editDonation($conn, $intDonationId) {
    if (!filter_var($intDonationId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $query = $conn->prepare("SELECT * FROM tbldonationmanagement WHERE intDonationId = ?");

        if (!$query) {
            http_response_code(500);
            echo json_encode(array("data" => array("message" => "Database operation failed")));
            exit();
        }

        $query->bind_param("i", $intDonationId);
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
        exit();
    }
}
?>