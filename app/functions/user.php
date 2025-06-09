<?php
function getUserData($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE strUsername <> 'admin'");
    return $allUserData;
}

function getDonorDate($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE ysnDonor = 1");
    return $allUserData;
}

function getFoodBanks($conn) {
    $allAllFoodBanks = $conn->query("SELECT * FROM tblfoodbank");
    return $allAllFoodBanks;
}

function addUser($conn, $userData) {
    header('Content-Type: application/json');

    $strFullName = $userData['strFullName'];
    $strEmail = $userData['strEmail'];
    $strContact = $userData['strContact'];
    $strUsername = $userData['strUsername'];
    $strPassword = $userData['strPassword'];
    $strConfirmPassword = $userData['strConfirmPassword'];
    $strAccountType = $userData['strAccountType'];
    $ysnStatus = $userData['ysnStatus'];
    $intFoodBankId = $userData['intFoodBankId'];

    $conn->begin_transaction();

    try {
        // Check if User already exists
        $sql = $conn->prepare("SELECT strUsername FROM tbluser WHERE strUsername = ?");
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
            $strSalt = bin2hex(random_bytes(22));
            $strPassword = crypt($strPassword, $strSalt);
        }

        $query;
        $stmt;
        $ysn = 1;
    
        switch ($strAccountType) {
        case "admin":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnAdmin, ysnActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) throw new Exception("Database insert operation failed", 400);
            $stmt->bind_param("ssssssii", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn, $ysnStatus);
            break;
        case "donor":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnDonor, ysnActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) throw new Exception("Database insert operation failed", 400);
            $stmt->bind_param("ssssssii", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn, $ysnStatus);
            break;
        case "staff":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnStaff, ysnActive, intFoodBankId) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) throw new Exception("Database insert operation failed", 400);
            $stmt->bind_param("ssssssiii", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn, $ysnStatus, $intFoodBankId);
            break;
        case "partner":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnPartner, ysnActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) throw new Exception("Database insert operation failed", 400);
            $stmt->bind_param("ssssssii", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn, $ysnStatus);
            break;
        case "beneficiary":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnBeneficiary, ysnActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) throw new Exception("Database insert operation failed", 400);
            $stmt->bind_param("ssssssii", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn, $ysnStatus);
            break;
        }

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "data" => [
                    "message" => "User successfully created",
                    "fullName" => $strFullName,
                    "email" => $strEmail,
                    "contact" => $strContact,
                    "username" => $strUsername,
                    "accountType" => $strAccountType,
                    "success" => true
                ]
            ]);
        } else {
            throw new Exception($stmt->error, 500);
        }

        $conn->commit();
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to create new user. " . $ex->getMessage()]]);
    }

    exit();
}

function getUserAccessLevel($conn, $intUserId) {
    $stmt = $conn->prepare("SELECT ysnBeneficiary FROM tbluser WHERE intUserId = ? LIMIT 1");
    if (!$stmt) throw new Exception("Database operation failed", 500);
    $stmt->bind_param("i", $intUserId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_object();
    return $result;
}

function editUser($conn, $intUserId) {
    header('Content-Type: application/json');

    try {
        if (!filter_var($intUserId, FILTER_VALIDATE_INT)) {
            throw new Exception("Invalid request", 400);
        } 
        else {
            $result = getUserAccessLevel($conn, $intUserId);

            // Regular User
            if (!$result->ysnBeneficiary) {
                $sql = "SELECT U.intUserId
                        , U.strUsername
                        , U.strFullName
                        , U.strContact
                        , U.strEmail
                        , U.ysnActive
                        , U.ysnAdmin
                        , U.ysnDonor
                        , U.ysnStaff
                        , U.ysnPartner
                        , U.ysnBeneficiary
                        , U.strDocument
                        , B.intBeneficiaryId
                        , U.strAddress
                        , B.dblSalary
                        FROM tbluser U
                        LEFT JOIN tblbeneficiary B
                            ON U.intUserId = B.intUserId
                        WHERE U.intUserId = ?";
            }
            // Beneficiary
            else {
                $sql = "SELECT U.intUserId
                        , U.strUsername
                        , U.strFullName
                        , U.strContact
                        , U.strEmail
                        , U.ysnActive
                        , U.ysnAdmin
                        , U.ysnDonor
                        , U.ysnStaff
                        , U.ysnPartner
                        , U.ysnBeneficiary
                        , B.intBeneficiaryId
                        , B.strAddress
                        , B.dblSalary
                        , B.strDocument
                        FROM tbluser U
                        LEFT JOIN tblbeneficiary B
                            ON U.intUserId = B.intUserId
                        WHERE U.intUserId = ?";
            }

            $query = $conn->prepare($sql);

            if (!$query) throw new Exception("Database operation failed", 404);

            $query->bind_param('i', $intUserId);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("User does not exist", 404);
                $query->close();
                exit();
            }

            $user = $result->fetch_object();
            if ($user->strDocument) {
                $filename = basename($user->strDocument);
                // Detect localhost or live
                $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
                // Adjust the base path
                $basePath = $isLocal ? '/SagipPagkain' : '';
                $fileUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $basePath . "/app/storage/documents/" . $filename;
                $user->strDocument = $fileUrl;
            }

            http_response_code(200);
            echo json_encode(["data" => $user]);
            $query->close();
        }
    } catch (Exception $ex) {
        $code = $ex->getCose();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function uploadDocument($intUserId) {
    header('Content-Type: application/json');
 
    if (isset($_FILES['uploadDocInput']) && $_FILES['uploadDocInput']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['uploadDocInput']['tmp_name'];
        $fileSize = $_FILES['uploadDocInput']['size'];
        $fileType = $_FILES['uploadDocInput']['type'];
        $fileName = $_FILES['uploadDocInput']['name'];
        $fileInfo = pathinfo($fileName);
        $fileBaseName = $fileInfo["filename"];
        $fileExtension = $fileInfo["extension"];

        $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
        $basePath = $isLocal ? 'SagipPagkain' : '';

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
        . DIRECTORY_SEPARATOR . $basePath 
        . DIRECTORY_SEPARATOR . "app" 
        . DIRECTORY_SEPARATOR . "storage" 
        . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR;
        $uploadFilePath = $targetDir . $intUserId . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
 
        $ctr = 1;
        while (file_exists($uploadFilePath)) {
            $uploadFilePath = $targetDir . $intUserId . "_" . $fileBaseName . "_" . date("Ymd") . "_" . $ctr . "." . $fileExtension;
            $ctr++;
        }
 
        $allowedTypes = [
            'application/pdf'
        ];
 
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Invalid file type", 400);
        } else if ($fileSize > 5000000) {
            throw new Exception("Image is too large", 400);
        } else if (file_exists($uploadFilePath)) {
            throw new Exception("File already exist", 400);
        } else {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                return $uploadFilePath;
            } else {
                throw new Exception("Server encountered an error, upload failed.", 500);
            }
        }
    }
 
    return "";
}

function updateUser($conn, $userData) {
    header("Content-Type: application/json");

    $intUserId = $userData["intUserId"];
    $strUsername = $userData["strUsername"];
    $strEmail = $userData["strEmail"];
    $strFullName = $userData["strFullName"];
    $strContact = $userData["strContact"];
    $strAddress = $userData["strAddress"];
    $dblSalary = $userData["dblSalary"];
    $ysnActive = $userData["ysnActive"];
    $ysnAdmin = $userData["ysnAdmin"];
    $ysnDonor = $userData["ysnDonor"];
    $ysnStaff = $userData["ysnStaff"];
    $ysnPartner = $userData["ysnPartner"];

    try {
        $result = getUserAccessLevel($conn, $intUserId);
        $uploadDocInput = uploadDocument($intUserId);
        $sql = "";

        // Regular User
        if (!$result->ysnBeneficiary) {
            $sql = "UPDATE tbluser U 
                    LEFT JOIN tblbeneficiary B
                        ON U.intUserId = B.intUserId
                    SET U.strEmail = ?
                        , U.strFullName = ?
                        , U.strContact = ?
                        , B.strName = ?
                        , B.strEmail = ?
                        , B.strContact = ?
                        , B.strAddress = ?
                        , B.dblSalary = ?
                        , U.strDocument = ?
                        , U.ysnActive = ?
                        , U.ysnAdmin = ?
                        , U.ysnDonor = ?
                        , U.ysnStaff = ?
                        , U.ysnPartner = ? 
                    WHERE U.intUserId = ?";
        } 
        // Beneficiary
        else {
            $sql = "UPDATE tbluser U 
                    LEFT JOIN tblbeneficiary B
                        ON U.intUserId = B.intUserId
                    SET U.strEmail = ?
                        , U.strFullName = ?
                        , U.strContact = ?
                        , B.strName = ?
                        , B.strEmail = ?
                        , B.strContact = ?
                        , B.strAddress = ?
                        , B.dblSalary = ?
                        , B.strDocument = ?
                        , U.ysnActive = ?
                        , U.ysnAdmin = ?
                        , U.ysnDonor = ?
                        , U.ysnStaff = ?
                        , U.ysnPartner = ? 
                    WHERE U.intUserId = ?";
        }

        $query = $conn->prepare($sql);

        if (!$query) throw new Exception("Database operation failed", 500);

        $query->bind_param("sssssssdsiiiiii", 
            $strEmail
            , $strFullName
            , $strContact
            , $strFullName
            , $strEmail
            , $strContact
            , $strAddress
            , $dblSalary
            , $uploadDocInput
            , $ysnActive
            , $ysnAdmin
            , $ysnDonor
            , $ysnStaff
            , $ysnPartner
            , $intUserId
        );

        if ($query->execute()) {
            if ($query->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["data" => ["message" => "User details updated successfully", "user" => $userData]]);
            } else {
                http_response_code(202);
                echo json_encode(["data" => ["message" => "No rows were affected"]]);
            }
        } else {
            throw new Exception($query->error, 500);
        }

        $query->close();
    } catch (Exception $ex) {
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function deleteUser($conn, $intUserId) {
    header("Content-Type: application/json");

    $sql = "DELETE U, B
            FROM tbluser U
            LEFT JOIN tblbeneficiary B
                ON U.intUserId = B.intUserId
            WHERE U.intUserId = ?";

    $query = $conn->prepare($sql);

    if (!$query) {
        echo json_encode(["data" => ["message" => "Database operation failed"]]);
        exit();
    }

    $query->bind_param("i", $intUserId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(array("data" => array("message" => "User was successfully deleted")));
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}
?>