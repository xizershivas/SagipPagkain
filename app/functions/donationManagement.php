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
        header("Content-Type: application/json");
        echo json_encode(array("data" => $data));
        $query->close();
    }
}

function processDocFileUpload($intDonationId) {
    if (empty($_FILES['uploadDocumentation']['name'][0])) {
        return;
    } else {
        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . "SagipPagkain" 
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "media"
            . DIRECTORY_SEPARATOR . "donor/";
            
        $allowedTypes = [
            'image/jpeg', // JPEG/JPG
            'image/png', // PNG
            'video/mp4',  // MP4 video
        ];

        $uploadedFiles = [];

        foreach ($_FILES['uploadDocumentation']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['uploadDocumentation']['tmp_name'][$key];
            $fileSize = $_FILES['uploadDocumentation']['size'][$key];
            $fileType = $_FILES['uploadDocumentation']['type'][$key];
            $fileInfo = pathinfo($fileName);
            $fileBaseName = $fileInfo["filename"];
            $fileExtension = $fileInfo["extension"];
            $uploadFilePath = $targetDir . $intDonationId . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
    
            // Check if file type is allowed
            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                echo json_encode(["data" => ["message" => "Invalid document type"]]);
                return;
            }
    
            // Check if file size is acceptable
            // if ($fileSize > 5000000) { // 5MB
            //     http_response_code(400);
            //     echo json_encode(["data" => ["message" => "Document is too large"]]);
            //     return;
            // }
    
            // Check if file already exists
            if (file_exists($uploadFilePath)) {
                http_response_code(400);
                echo json_encode(["data" => ["message" => "File already exists"]]);
                return;
            }
    
            // Move the uploaded file to the target directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                $uploadedFiles[] = $uploadFilePath;
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
                return;
            }
        }

        return $uploadedFiles;
    }
}

function updateDonation($conn, $donationData) {
    $query = $conn->prepare("UPDATE tbldonationmanagement SET 
        strDonorName = ?,
        dtmDate = ?,
        strTitle = ?,
        strDescription = ?,
        strPickupLocation = ?,
        strRemarks = ?,
        ysnStatus = ?,
        strDocFilePath = ?
        WHERE intDonationId = ?"
    );

    $query->bind_param("ssssssssi",
        $donationData["strDonorName"],
        $donationData["dtmDate"],
        $donationData["strTitle"],
        $donationData["strDescription"],
        $donationData["strPickupLocation"],
        $donationData["strRemarks"],
        $donationData["ysnStatus"],
        $donationData["strDocFilePath"],
        $donationData["intDonationId"]
    );
    
    header("Content-Type: application/json");

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(["data" => ["message" => "Volunteer successfully updated"]]);
        } else {
            http_response_code(202);
            echo json_encode(["data" => ["message" => "No rows were affected"]]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["data" => ["message" => $query->error]]);
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