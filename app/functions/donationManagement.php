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
    }
}

function processDocFileUpload() {
    if (empty($_FILES['uploadDocumentation']['name'])) {
        return;
    } else {
        $fileTmpPath = $_FILES['uploadDocumentation']['tmp_name'];
        $fileName = $_FILES['uploadDocumentation']['name'];
        $fileSize = $_FILES['uploadDocumentation']['size'];
        $fileType = $_FILES['uploadDocumentation']['type'];

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . "SagipPagkain" 
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "documents/";

        $uploadFilePath = $targetDir . basename($fileName);

        $allowedTypes = [
            'application/pdf', // PDF
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // DOCX
        ];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid document type"]]);
        } else if ($fileSize > 5000000) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Document is too large"]]);
        } else if (file_exists($uploadFilePath)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File already exist"]]);
        } else {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                return $uploadFilePath;
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
            }
        }
    }

    exit();
}

function deleteDonation($conn, $intDonationId) {
    $query = $conn->prepare("DELETE FROM tbldonationmanagement WHERE intDonationId = ?");
    $query->bind_param("i", $intDonationId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(array("data" => array("message" => "Donation record was successfully deleted")));
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}
?>