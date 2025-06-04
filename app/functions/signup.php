<?php
function register($conn, $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strConfirmPassword, $strAccountType, $strAddress, $latitude, $longitude, $dblSalary, $uploadFilePath) {
    header("Content-Type: application/json");

    $conn->begin_transaction();

    try {
        // Check if User already exists
        $sql = $conn->prepare("SELECT strUsername FROM tbluser WHERE strUsername = ?");
        if (!$sql) throw new Exception("Database insert operation failed for user", 500);
        $sql->bind_param("s", $strUsername);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows >= 1) {
            throw new Exception("The Username already exists, please choose a different Username", 400);
        }
        
        // Password pattern
        $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/";
        $strSalt;

        // Check password match
        if ($strPassword != $strConfirmPassword) {
            throw new Exception("Passwords do not match", 400);
        } else if (strlen($strPassword) < 8) {
            throw new Exception("Password must be 8 characters long", 400);
        } else if (!preg_match($pattern, $strPassword)) {
            throw new Exception("Password must contain at least 1 capital letter, 1 number and 1 special character", 406);
        } else {
            // Encrypt password
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
                if (!$stmt2) throw new Exception("Database insert operation failed for beneficiary", 500);
                $stmt2->bind_param("issssddds", $intUserId, $strFullName, $strEmail, $strContact, $strAddress, $latitude, $longitude, $dblSalary, $uploadFilePath);

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
                    throw new Exception($stmt->error, 500);
                }
            } else {
                throw new Exception($stmt->error, 500);
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
                throw new Exception($stmt->error, 500);
            }

            $stmt->close();
        }
        
        $conn->commit();
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
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

        // Directory path on disk
        $uploadDir = __DIR__ . '/../storage/documents/';
        $uploadFileName = $strUsername . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
        $destinationPath = $uploadDir . $uploadFileName;

        $ctr = 1;
        while (file_exists($destinationPath)) {
            $uploadFileName = $strUsername . "_" . $fileBaseName . "_" . date("Ymd") . "_" . $ctr . "." . $fileExtension;
            $destinationPath = $uploadDir . $uploadFileName;
            $ctr++;
        }

        // Build URL to return
        $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
        $basePath = $isLocal ? '/SagipPagkain' : '';
        $fileUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $basePath . "/app/storage/documents/" . $uploadFileName;

        $allowedTypes = ['application/pdf'];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid file type"]]);
        } else if ($fileSize > 5000000) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File is too large"]]);
        } else if (file_exists($destinationPath)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File already exists"]]);
        } else {
            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                return $fileUrl;
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
            }
        }
    }

    return '';
}
?>