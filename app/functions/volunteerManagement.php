<?php
function getVolunteerData($conn) {
    $allVolunteerData = $conn->query("SELECT * FROM tblvolunteer");
    return $allVolunteerData;
}

function editVolunteer($conn, $intVolunteerId) {
    if (!filter_var($intVolunteerId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $query = $conn->prepare("SELECT * FROM tblvolunteer WHERE intVolunteerId = ?");

        if (!$query) {
            http_response_code(500);
            echo json_encode(array("data" => array("message" => "Database operation failed")));
            exit();
        }

        $query->bind_param("i", $intVolunteerId);
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

function processSignFileUpload($intVolunteerId) {
    if (empty($_FILES['signature']['name'])) {
        return;
    } else {
        $fileTmpPath = $_FILES['signature']['tmp_name'];
        $fileName = $_FILES['signature']['name'];
        $fileSize = $_FILES['signature']['size'];
        $fileType = $_FILES['signature']['type'];
        $fileInfo = pathinfo($fileName);
        $fileBaseName = $fileInfo["filename"];
        $fileExtension = $fileInfo["extension"];

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . "SagipPagkain" 
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "images/";

        // DELETE signature if intVolunteerId already exist
        $existingSignFilePath = $targetDir . $intVolunteerId . "_*"; // Wildcard
        // Get all files matching the pattern
        $files = glob($existingSignFilePath);

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    // Check if the file exists and then delete it
                    unlink($file);
                }
            }
        }

        $uploadFilePath = $targetDir . $intVolunteerId . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;

        $allowedTypes = ['image/jpeg', 'image/png'];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid image type"]]);
        } else if ($fileSize > 3000000) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Image is too large"]]);
        } else if (file_exists($uploadFilePath)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File already exist"]]);
            exit(); // If file already exists
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

function updateVolunteer($conn, $volunteerData) {
    $query = $conn->prepare("UPDATE tblvolunteer SET 
        strFirstName = ?,
        strLastName = ?,
        strGender = ?,
        dtmDateOfBirth = ?,
        strStreet = ?,
        strAddress = ?,
        strCity = ?,
        strRegion = ?,
        strZipCode = ?,
        strCountry = ?,
        strContact = ?,
        strEmail = ?,
        strSignFilePath = ?
        WHERE intVolunteerId = ?"
    );

    $query->bind_param("sssssssssssssi",
        $volunteerData["strFirstName"],
        $volunteerData["strLastName"],
        $volunteerData["strGender"],
        $volunteerData["dtmDateOfBirth"],
        $volunteerData["strStreet"],
        $volunteerData["strAddress"],
        $volunteerData["strCity"],
        $volunteerData["strRegion"],
        $volunteerData["strZipCode"],
        $volunteerData["strCountry"],
        $volunteerData["strContact"],
        $volunteerData["strEmail"],
        $volunteerData["strSignFilePath"],
        $volunteerData["intVolunteerId"]
    );
    
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
}

function deleteVolunteer($conn, $intVolunteerId) {
    $query = $conn->prepare("DELETE FROM tblvolunteer WHERE intVolunteerId = ?");
    $query->bind_param("i", $intVolunteerId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Volunteer was deleted successfully"]]);
    } else {
        http_response_code(400);
        echo json_encode(["data" => ["message" => $query->error]]);
    }

    $query->close();
    exit();
}
?>