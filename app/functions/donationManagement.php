<?php
function getDonationData($conn, $user) {
    $intUserId = intval($user->intUserId);
    $ysnActive = $user->ysnActive;
    $ysnAdmin = $user->ysnAdmin;
    $ysnDonor = $user->ysnDonor;
    $ysnPartner = $user->ysnPartner;
    $allDonationData;

    if ($ysnAdmin) {
        $allDonationData = $conn->query("SELECT D.*, US.strFullName, FBD.strFoodBankName
            , FBD.intFoodBankDetailId, I.strItem, IV.intQuantity, U.strUnit, C.strCategory, P.strPurpose
            FROM tbldonationmanagement D
            INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tbluser US ON D.intUserId = US.intUserId
            INNER JOIN tblpurpose P ON D.intPurposeId = P.intPurposeId
            WHERE D.ysnArchive = 0");
    }
    else {
        $allDonationData = $conn->query("SELECT D.*, US.strFullName, FBD.strFoodBankName
        , FBD.intFoodBankDetailId, I.strItem, IV.intQuantity, U.strUnit, C.strCategory, P.strPurpose
        FROM tbldonationmanagement D
        INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
        INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
        INNER JOIN tblitem I ON IV.intItemId = I.intItemId
        INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
        INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
        INNER JOIN tbluser US ON FBD.intFoodBankId = US.intFoodBankId
        INNER JOIN tblpurpose P ON D.intPurposeId = P.intPurposeId
        WHERE D.ysnArchive = 0 AND US.intUserId = $intUserId");
    }

    return $allDonationData;
}

function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tbluser WHERE intUserId = $intUserId");
    return $userData;
}

function getArchiveData($conn) {
    $sql = "SELECT DM.*, U.strFullName
            FROM tbldonationmanagement DM
            INNER JOIN tbluser U ON DM.intUserId = U.intUserId
            WHERE ysnArchive = 1";
    // $sql = "SELECT * FROM tbldonationarchive";
    $allArchiveData = $conn->query($sql);
    return $allArchiveData;
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

function getPurpose($conn) {
    $allPurpose = $conn->query("SELECT * FROM tblpurpose");
    return $allPurpose;
}

function editDonation($conn, $intDonationId) {
    if (!filter_var($intDonationId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $sql = 
            "SELECT D.*, US.strFullName, FBD.intFoodBankDetailId, I.intItemId, I.strItem, IV.intQuantity
            , U.intUnitId, U.strUnit, C.intCategoryId, C.strCategory, P.intPurposeId, P.strPurpose, IV.dtmExpirationDate
            FROM tbldonationmanagement D
            INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tbluser US ON D.intUserId = US.intUserId
            INNER JOIN tblpurpose P ON D.intPurposeId = P.intPurposeId
            WHERE D.intDonationId = ?";

        $query = $conn->prepare($sql);

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

function processDocFileUpload($conn, $intDonationId) {
    if (empty($_FILES['verification']['name'][0])) {
        return;
    } else {
        $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
        $basePath = $isLocal ? 'SagipPagkain' : '';

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

        $userResult = $conn->query("SELECT intUserId FROM tbldonationmanagement WHERE intDonationId = $intDonationId");
        $intUserId;
        
        // User check
        if ($userResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Server encountered an error, Upload failed."]]);
            exit();
        } else {
            $intUserId = $userResult->fetch_object()->intUserId;

            // DELETE uploaded images if intUserId already exist
            $existingSignFilePath = $targetDir . $intUserId . "_*"; // Wildcard
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

            foreach ($_FILES['verification']['name'] as $key => $fileName) {
                $fileTmpPath = $_FILES['verification']['tmp_name'][$key];
                $fileSize = $_FILES['verification']['size'][$key];
                $fileType = $_FILES['verification']['type'][$key];
                $fileInfo = pathinfo($fileName);
                $fileBaseName = $fileInfo["filename"];
                $fileExtension = $fileInfo["extension"];
                $uploadFilePath = $targetDir . $intUserId . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
        
                // Check if file type is allowed
                if (!in_array($fileType, $allowedTypes)) {
                    http_response_code(400);
                    echo json_encode(["data" => ["message" => "Invalid document type"]]);
                    exit();
                }
        
                // Check if file size is acceptable
                if ($fileSize > 5000000) { // 5MB
                    http_response_code(400);
                    echo json_encode(["data" => ["message" => "Document is too large"]]);
                    exit();
                }
        
                // Check if file already exists
                if (file_exists($uploadFilePath)) {
                    http_response_code(400);
                    echo json_encode(["data" => ["message" => "File already exists"]]);
                    exit();
                }
        
                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                    $uploadedFiles[] = $uploadFilePath;
                } else {
                    http_response_code(500);
                    echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
                    exit();
                }
            }

            return $uploadedFiles;
        }
    }
}

function updateDonation($conn, $donationData) {
    header("Content-Type: application/json");

    $intDonationId = $donationData["intDonationId"];
    $strFullName = $donationData["strFullName"];
    $dtmDate = $donationData["dtmDate"];
    $dtmExpirationDate = $donationData["dtmExpirationDate"];
    $intFoodBankDetailId = $donationData["intFoodBankDetailId"];
    $strDescription = $donationData["strDescription"];
    $intPurposeId = $donationData["intPurposeId"];
    $intItemId = $donationData["intItemId"];
    $intQuantity = $donationData["intQuantity"];
    $intUnitId = $donationData["intUnitId"];
    $intCategoryId = $donationData["intCategoryId"];
    $ysnStatus = $donationData["ysnStatus"];
    $strDocFilePath = $donationData["strDocFilePath"];

    $conn->begin_transaction();

    try {
        $sql = "UPDATE tbldonationmanagement DM
            JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            SET DM.dtmDate = ?,
                DM.strDescription = ?,
                DM.intFoodBankDetailId = ?,
                DM.strDocFilePath = ?,
                DM.intPurposeId = ?,
                DM.dtmExpirationDate = ?,
                DM.ysnStatus = ?,
                IV.intFoodBankDetailId = ?,
                IV.intItemId = ?,
                IV.intCategoryId = ?,
                IV.intUnitId = ?,
                IV.intQuantity = ?,
                IV.dtmExpirationDate = ?
            WHERE IV.intDonationId = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssisisiiiiiisi",
            $dtmDate,
            $strDescription,
            $intFoodBankDetailId,
            $strDocFilePath,
            $intPurposeId,
            $dtmExpirationDate,
            $ysnStatus,
            $intFoodBankDetailId,
            $intItemId,
            $intCategoryId,
            $intUnitId,
            $intQuantity,
            $dtmExpirationDate,
            $intDonationId
        );

        if (!$stmt->execute()) {
            throw new Exception ("Database updated operation failed", 500);
        }

        $conn->commit();

        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation updated successfully"]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function archiveDonation($conn, $intDonationId) {
    $ysnArchive = 1;

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE tbldonationmanagement SET ysnArchive = ? WHERE intDonationId = ?");
        $stmt->bind_param("ii", $ysnArchive, $intDonationId);

        if (!$stmt->execute()) {
            throw new Exception("Error updating donation archive status: " . $stmt->error, 500);
        }

        $conn->commit();

        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation record moved to archive"]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $e->getMessage()]]);
    }

    exit();
}

function unarchiveDonation($conn, $intDonationId) {
    $ysnArchive = 0;

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE tbldonationmanagement SET ysnArchive = ? WHERE intDonationId = ?");
        $stmt->bind_param("ii", $ysnArchive, $intDonationId);

        if (!$stmt->execute()) {
            throw new Exception("Error updating donation archive status: " . $stmt->error, 500);
        }

        $conn->commit();

        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation record moved to donation data"]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $e->getMessage()]]);
    }

    exit();
}
?>