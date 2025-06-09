<?php
function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tbluser WHERE intUserId = $intUserId");
    return $userData;
}
 
function getItems($conn) {
    $items = $conn->query("SELECT * FROM tblitem");
    return $items;
}
 
function getUnits($conn) {
    $units = $conn->query("SELECT * FROM tblunit");
    return $units;
}
 
function getCategories($conn) {
    $categories = $conn->query("SELECT * FROM tblcategory");
    return $categories;
}
 
function getFoodBanks($conn) {
    $foodbanks = $conn->query("SELECT * FROM tblfoodbankdetail");
    return $foodbanks;
}
function getAllPurpose($conn) {
    $allPurpose = $conn->query("SELECT * FROM tblpurpose");
    return $allPurpose;
}

function getItemDetails($conn, $intItemId) {
    header("Content-Type: application/json");

    try {
        
        $sql = "SELECT I.intItemId
            , I.strItem
            , U.intUnitId
            , U.strUnit
            , C.intCategoryId
            , C.strCategory
            FROM tblitem I
            INNER JOIN tblunit U ON I.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON I.intCategoryId = C.intCategoryId
            WHERE I.intItemId = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Database prepare statement failed " . $conn->error, 500);
        }

        $stmt->bind_param("i", $intItemId);

        if (!$stmt->execute()) {
            throw new Exception("Database statement execution failed " . $stmt->error, 500);
        }

        $itemDetails = $stmt->get_result()->fetch_object();
        echo json_encode(["data" => $itemDetails]);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function getFoodbank($conn, $intItemId, $intUserId) {
    header("Content-Type: application/json");

    try {
        
        $sql = "SELECT FB.intFoodBankDetailId, FB.strFoodBankName, COALESCE(I.intQuantity, 0) AS intQuantity
                 FROM tblfoodbankdetail FB
                 INNER JOIN tblfoodbank F ON FB.intFoodBankId = F.intFoodBankId
                 INNER JOIN tbluser U ON F.intFoodBankId = U.intFoodBankId
                 LEFT JOIN tblinventory I ON FB.intFoodbankDetailId = I.intFoodbankDetailId AND I.intItemId = ?
                 WHERE U.intUserId = ?
                 ORDER BY I.intQuantity ASC
                 LIMIT 1";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Database prepare statement failed " . $conn->error, 500);
        }

        $stmt->bind_param("ii", $intItemId, $intUserId);

        if (!$stmt->execute()) {
            throw new Exception("Database statement execution failed " . $stmt->error, 500);
        }

        $foodbanks = $stmt->get_result()->fetch_object();
        echo json_encode(["data" => $foodbanks]);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}
 
function processDocFileUpload($intUserId) {
    header("Content-Type: application/json");
 
    if (empty($_FILES['verification']['name'][0])) {
        return;
    } else {
        $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
        $basePath = $isLocal ? 'SagipPagkain' : '';
        // $fileUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $basePath . "/app/storage/documents/" . $uploadFileName;

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . $basePath
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "media"
            . DIRECTORY_SEPARATOR . "donor/";

        $allowedTypes = [
            'application/pdf'
        ];
 
        $uploadedFiles = [];
 
        foreach ($_FILES['verification']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['verification']['tmp_name'][$key];
            $fileSize = $_FILES['verification']['size'][$key];
            $fileType = $_FILES['verification']['type'][$key];
            $fileInfo = pathinfo($fileName);
            $fileBaseName = $fileInfo["filename"];
            $fileExtension = $fileInfo["extension"];
            $uploadFilePath = $targetDir . $intUserId . "_" . $fileBaseName . "_" . date("Ymdhis") . "." . $fileExtension;
            // Check if file type is allowed
            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                echo json_encode(["data" => ["message" => "Invalid document type"]]);
                return;
            }
            // Check if file size is acceptable
            if ($fileSize > 5000000) { // 5MB
                http_response_code(400);
                echo json_encode(["data" => ["message" => "Document is too large"]]);
                return;
            }
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
 
function addDonation($conn, $donationData) {
    header("Content-Type: application/json");
 
    if (!isset($donationData)) {
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Internal Server Error"]]);
    } else {
        $intUserId = $donationData["intUserId"];
        $strDonorName = $donationData["strDonorName"];
        $dtmDate = $donationData["dtmDate"];
        $strDescription = $donationData["strDescription"];
        $intFoodBankDetailId = $donationData["intFoodBankDetailId"];
        $intItemId = $donationData["intItemId"];
        $intQuantity = $donationData["intQuantity"];
        $intCategoryId = $donationData["intCategoryId"];
        $intUnitId = $donationData["intUnitId"];
        $strDocFilePath = $donationData["strDocFilePath"];
        $intPurposeId = $donationData["intPurposeId"];
        $dtmExpirationDate = $donationData["dtmExpirationDate"];
 
        if (empty($dtmDate)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Date"]]);
            exit();
        }
 
        if (empty($dtmExpirationDate)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Date for Expiration Date"]]);
            exit();
        }
 
        $conn->begin_transaction();
 
        try {
            // Insert donation
            $query1 = $conn->prepare("INSERT INTO tbldonationmanagement 
                (intUserId, dtmDate, strDescription, intFoodBankDetailId, strDocFilePath, intPurposeId, dtmExpirationDate) VALUES (?,?,?,?,?,?,?)");
            $query1->bind_param("issisis"
                ,$intUserId
                ,$dtmDate
                ,$strDescription
                ,$intFoodBankDetailId
                ,$strDocFilePath
                ,$intPurposeId
                ,$dtmExpirationDate
            );
 
            if (!$query1->execute()) {
                throw new Exception("Failed to add Donation " . $query1->error, 500);
            }
 
            // Get the last inserted ID
            $intDonationId = $conn->insert_id;
 
            // Insert inventory
            $query2 = $conn->prepare("INSERT INTO tblinventory 
                (intDonationId, intFoodBankDetailId, intItemId, intCategoryId, intUnitId, intQuantity, dtmExpirationDate) VALUES (?,?,?,?,?,?,?)");
            $query2->bind_param("iiiiiis"
                ,$intDonationId
                ,$intFoodBankDetailId
                ,$intItemId
                ,$intCategoryId
                ,$intUnitId
                ,$intQuantity
                ,$dtmExpirationDate
            );
            $tbldonationmanagement = 'tblinventory';
            $query3 = $conn->prepare("INSERT INTO `tblnotification`(`intSourceId`, `strSourceTable`, `ysnSeen`) VALUES (?, ?, 0)");
            $query3->bind_param("is", $intDonationId, $tbldonationmanagement);
            
            if (!$query3) {
                throw new Exception("Database insert operation failed: " . $conn->error, 500);
            }
 
            $query3->bind_param("is", $intDonationId, $tbldonationmanagement);
 
            if (!$query3->execute()) {
                throw new Exception("Notification insert failed: " . $query3->error, 500);
            }

            if (!$query2->execute() && !$query3 -> execute()) {
                throw new Exception("Failed to add Inventory " . $query2->error, 500);
            }
 
            $conn->commit();
 
            http_response_code(201);
            echo json_encode(["data" => ["message" => "Donation submitted successfully"]]);
 
            $query1->close();
            $query2->close();
            $query3->close();
        } catch (Exception $ex) {
            $conn->rollback();
            $code = $ex->getCode();
            http_response_code($code);
            echo json_encode(["data" => ["message" => $ex->getMessage()]]);
        }
    }
 
    exit();
}
?>