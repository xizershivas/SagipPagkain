<?php
function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tblbeneficiary WHERE intUserId = $intUserId");
    return $userData;
}
 
function getAllInventoryItems($conn) {
    $sql = "SELECT IV.intInventoryId 
            ,IV.intQuantity
            ,D.intDonationId
            ,FBD.intFoodBankDetailId
            ,FBD.strFoodBankName
            ,I.intItemId
            ,I.strItem
            ,C.intCategoryId
            ,C.strCategory
            ,U.intUnitId
            ,U.strUnit
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId";
 
    $allInventoryData = $conn->query($sql);
    return $allInventoryData;
}
 
function getItems($conn) {
    $sql = "SELECT * FROM tblitem";
    $allItems = $conn->query($sql);
    return $allItems;
}
 
function uploadRequestDocument($beneficiaryId) {
    header('Content-Type: application/json');
 
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['document']['tmp_name'];
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type'];
        $fileName = $_FILES['document']['name'];
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
        $uploadFilePath = $targetDir . $beneficiaryId . "_" . $fileBaseName . "_" . date("Ymd") . "." . $fileExtension;
 
        $ctr = 1;
        while (file_exists($uploadFilePath)) {
            $uploadFilePath = $targetDir . $beneficiaryId . "_" . $fileBaseName . "_" . date("Ymd") . "_" . $ctr . "." . $fileExtension;
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
 
function submitBeneficiaryRequest($conn, $requestData) {
    header('Content-Type: application/json');
    $intBeneficiaryId = $requestData['beneficiaryId'];
    $strRequestType = $requestData['requestType'];
    $itemsNeeded = $requestData['itemsNeeded'];
    $strUrgencyLevel = $requestData['urgencyLevel'];
    $dtmPickupDate = $requestData['pickupDate'];
    $strDocument = $requestData['document'];
    $strDescription = $requestData['description'];
    $intPurposeId = $requestData['purpose'];
    $foodbankId = $requestData['foodbankId'];
    $conn->begin_transaction();
 
    try {
        $sql1 = "INSERT INTO tblbeneficiaryrequest (intBeneficiaryId, strRequestType, strUrgencyLevel, dtmPickupDate, strDocument, strDescription, intPurposeId, intFoodBankDetailId) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) throw new Exception("Database operation failed", 500);
        $stmt1->bind_param("isssssii", $intBeneficiaryId, $strRequestType, $strUrgencyLevel, $dtmPickupDate, $strDocument, $strDescription, $intPurposeId, $foodbankId);
 
        if ($stmt1->execute()) {
            $lastInsertId = $conn->insert_id;
            $strRequestNo = "RQ-" . $lastInsertId;
 
            $sql2 = "UPDATE tblbeneficiaryrequest SET strRequestNo = ? WHERE intBeneficiaryRequestId = ?";
            $stmt2 = $conn->prepare($sql2);
            if (!$stmt2) throw new Exception("Database operation failed", 500);
            $stmt2->bind_param("si", $strRequestNo, $lastInsertId);
            $stmt2->execute();
 
            // Now insert each item into tblbeneficiaryrequestdetail
            $sql3 = "INSERT INTO tblbeneficiaryrequestdetail (intBeneficiaryRequestId, intItemId) VALUES (?, ?)";
            $stmt3 = $conn->prepare($sql3);
            if (!$stmt3) throw new Exception("Database operation failed", 500);
 
            foreach ($itemsNeeded as $itemId) {
                $stmt3->bind_param("ii", $lastInsertId, $itemId);
                $stmt3->execute();
            }
 
            $tblbeneficiaryrequest = 'tblbeneficiaryrequest';
            $query3 = $conn->prepare("INSERT INTO `tblnotification`(`intSourceId`, `strSourceTable`, `ysnSeen`) VALUES (?, ?, 0)");
            $query3->bind_param("is", $lastInsertId, $tblbeneficiaryrequest);
            if (!$query3) {
                die("Prepare failed: " . $conn->error);
            }
 
            if (!$query3->execute()) {
                die("Notification insert failed: " . $query3->error);
            }
        }
 
        $conn->commit();
 
        http_response_code(201);
        echo json_encode(["data" => ["message" => "Request submitted successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to submit request. " . $ex->getMessage()]]);
    }
}
?>