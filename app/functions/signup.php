<?php
function register($conn, $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strConfirmPassword, $strAccountType, $strAddress, $latitude, $longitude, $dblSalary, $uploadFilePath) {
    // Check if User already exists
    $sql = $conn->prepare("SELECT strUsername FROM tbluser WHERE strUsername = ?");
    $sql->bind_param("s", $strUsername);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows >= 1) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "The Username already exists, please choose a different Username")));
        exit();
    }
    
    // Password pattern
    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/";
    $strSalt;

    // Check password match
    if ($strPassword != $strConfirmPassword) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Passwords do not match")));
        exit();
    } else if (strlen($strPassword) < 8) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Password must be 8 characters long")));
        exit();
    } else if (!preg_match($pattern, $strPassword)) {
        http_response_code(406);
        echo json_encode(array("data" => array("message" => "Password must contain at least 1 capital letter, 1 number and 1 special character")));
        exit();
    } else {
        $strSalt = bin2hex(random_bytes(22));
        $strPassword = crypt($strPassword, $strSalt);
    }

    $query;
    $query2;
    $stmt;
    $stmt2;
    $ysn = 1;

    switch ($strAccountType) {
        case "donor":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnDonor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        case "partner":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnPartner) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        case "beneficiary":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnActive, ysnBeneficiary) 
            VALUES (?, ?, ?, ?, ?, ?, 0, ?)";
            $stmt = $conn->prepare($query);
            $ysnActive = 1;
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
    }

    if ($strAccountType == "beneficiary") {
        if ($stmt->execute()) {
            $intUserId = $conn->insert_id;

            $query2 = "INSERT INTO tblbeneficiary (intUserId, strName, strEmail, strContact, strAddress, dblLatitude, dblLongitude, dblSalary, strDocument) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("issssddds", $intUserId, $strFullName, $strEmail, $strContact, $strAddress,$latitude,$longitude, $dblSalary, $uploadFilePath);

            if ($stmt2->execute()) {
                http_response_code(201);
                echo json_encode(array(
                    "data" => array(
                        "message" => "Registration successful",
                        "fullName" => $strFullName,
                        "email" => $strEmail,
                        "contact" => $strContact,
                        "username" => $strUsername,
                        "accountType" => $strAccountType,
                        "success" => true
                        )
                    ));
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => $stmt->error]]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["data" => ["message" => $stmt->error]]);
        }

        $stmt->close();
        $stmt2->close();
    } else {
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array(
                "data" => array(
                    "message" => "Registration successful",
                    "fullName" => $strFullName,
                    "email" => $strEmail,
                    "contact" => $strContact,
                    "username" => $strUsername,
                    "accountType" => $strAccountType,
                    "success" => true
                )
            ));
        } else {
            http_response_code(500);
            echo json_encode(["data" => ["message" => $stmt->error]]);
        }

        $stmt->close();
    }

    exit();
}

function uploadRequestDocument($strUsername) {
    header('Content-Type: application/json');
 
    if (isset($_FILES['uploadDocu']) && $_FILES['uploadDocu']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['uploadDocu']['tmp_name'];
        $fileSize = $_FILES['uploadDocu']['size'];
        $fileType = $_FILES['uploadDocu']['type'];
        $fileName = $_FILES['uploadDocu']['name'];
        $fileInfo = pathinfo($fileName);
        $fileBaseName = $fileInfo["filename"];
        $fileExtension = $fileInfo["extension"];
        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
        . DIRECTORY_SEPARATOR . "SagipPagkain" 
        . DIRECTORY_SEPARATOR . "app" 
        . DIRECTORY_SEPARATOR . "storage" 
        . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR;
        $uploadFilePath = $targetDir . $strUsername . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
 
        $ctr = 1;
        while (file_exists($uploadFilePath)) {
            $uploadFilePath = $targetDir . $strUsername . "_" . $fileBaseName . "_" . date("Ymd") . "_" . $ctr . "." . $fileExtension;
            $ctr++;
        }
 
        $allowedTypes = [
            'application/pdf'
        ];
 
        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid image type"]]);
        } else if ($fileSize > 5000000) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Image is too large"]]);
        } else if (file_exists($uploadFilePath)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File already exist"]]);
        } else {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                return $fileName;
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
            }
        }
    }
 
    return '';
}
?>