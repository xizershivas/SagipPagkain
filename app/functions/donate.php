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
    $foodbanks = $conn->query("SELECT * FROM tblfoodbank");
    return $foodbanks;
}

function processDocFileUpload($intUserId) {
    header("Content-Type: application/json");

    if (empty($_FILES['verification']['name'][0])) {
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
            'image/png' // PNG
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
        $strTitle = $donationData["strTitle"];
        $strDescription = $donationData["strDescription"];
        $intFoodBankId = $donationData["intFoodBankId"];
        $strItem = $donationData["strItem"];
        $intQuantity = $donationData["intQuantity"];
        $strCategory = $donationData["strCategory"];
        $strUnit = $donationData["strUnit"];
        $strDocFilePath = $donationData["strDocFilePath"];
        $strRemarks = $donationData["strRemarks"];

        if (empty($dtmDate)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Date"]]);
            exit();
        }

        // Get Food Bank Name
        $strFoodBankResult = $conn->query("SELECT strFoodBank FROM tblfoodbank WHERE intFoodBankId = '$intFoodBankId'");
        $strFoodBank = $strFoodBankResult->fetch_object()->strFoodBank;

        $conn->begin_transaction();

        try {
            // Insert donation
            $query1 = $conn->prepare("INSERT INTO tbldonationmanagement 
                (intUserId, strDonorName, dtmDate, strTitle, strDescription, strFoodBank, strDocFilePath, strRemarks) VALUES (?,?,?,?,?,?,?,?)");
            $query1->bind_param("isssssss"
                ,$intUserId
                ,$strDonorName
                ,$dtmDate
                ,$strTitle
                ,$strDescription
                ,$strFoodBank
                ,$strDocFilePath
                ,$strRemarks
            );

            if (!$query1->execute()) {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Failed to add Donation".$query1->error]]);
                exit();
            }

            // Get the last inserted ID
            $intDonationId = $conn->insert_id;

            // Get intItemId
            $itemResult = $conn->query("SELECT intItemId FROM tblitem WHERE strItem = '$strItem'");
            if ($itemResult->num_rows == 0) {
                http_response_code(401);
                echo json_encode(["data" => ["message" => "Invalid item selected"]]);
                exit();
            }
            $intItemId = $itemResult->fetch_object()->intItemId;
            // Get intCategoryId
            $categoryResult = $conn->query("SELECT intCategoryId FROM tblcategory WHERE strCategory = '$strCategory'");
            if ($categoryResult->num_rows == 0) {
                http_response_code(401);
                echo json_encode(["data" => ["message" => "Invalid category selected"]]);
                exit();
            }
            $intCategoryId = $categoryResult->fetch_object()->intCategoryId;
            // Get intUnitId
            $unitResult = $conn->query("SELECT intUnitId FROM tblunit WHERE strUnit = '$strUnit'");
            if ($unitResult->num_rows == 0) {
                http_response_code(401);
                echo json_encode(["data" => ["message" => "Invalid unit selected"]]);
                exit();
            }
            $intUnitId = $unitResult->fetch_object()->intUnitId;

            // Insert inventory
            $query2 = $conn->prepare("INSERT INTO tblinventory 
                (intDonationId, intFoodBankId, intItemId, intCategoryId, intUnitId, intQuantity) VALUES (?,?,?,?,?,?)");
            $query2->bind_param("iiiiii"
                ,$intDonationId
                ,$intFoodBankId
                ,$intItemId
                ,$intCategoryId
                ,$intUnitId
                ,$intQuantity
            );

            if (!$query2->execute()) {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Failed to add Inventory".$query2->error]]);
                exit();
            }

            $conn->commit();

            http_response_code(200);
            echo json_encode(["data" => ["message" => "Donation sent successfully"]]);

            $query1->close();
            $query2->close();
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(["data" => ["message" => $e->getMessage()]]);
        }
    }

    exit();
}
?>