<?php
function getDonationData($conn, $user) {
    $intUserId = intval($user->intUserId);
    $ysnActive = $user->ysnActive;
    $ysnAdmin = $user->ysnAdmin;
    $ysnDonor = $user->ysnDonor;
    $ysnNgo = $user->ysnNgo;
    $allDonationData;

    if ($ysnAdmin == 1) {
        $allDonationData = $conn->query("SELECT D.*
            , FB.intFoodBankId, I.strItem, IV.intQuantity, U.strUnit, C.strCategory
            FROM tbldonationmanagement D
            INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId"
        );
    } else {
        $allDonationData = $conn->query("SELECT D.*
            , FB.intFoodBankId, I.strItem, IV.intQuantity, U.strUnit, C.strCategory
            FROM tbldonationmanagement D
            INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            WHERE D.intUserId = $intUserId"
        );
    }

    return $allDonationData;
}

function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tbluser WHERE intUserId = $intUserId");
    return $userData;
}

function getArchiveData($conn) {
    $allArchiveData = $conn->query("SELECT intDonationId, strDonorName FROM tbldonationarchive");
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
    $foodbanks = $conn->query("SELECT * FROM tblfoodbank");
    return $foodbanks;
}

function editDonation($conn, $intDonationId) {
    if (!filter_var($intDonationId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $sql = 
            "SELECT D.*, FB.intFoodBankId, I.strItem, IV.intQuantity, U.strUnit, C.strCategory
            FROM tbldonationmanagement D
            INNER JOIN tblinventory IV ON D.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
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
    $intDonationId = $donationData["intDonationId"];
    $intFoodBankId = $donationData["intFoodBankId"];
    $strItem = $donationData["strItem"];
    $strCategory = $donationData["strCategory"];
    $strUnit = $donationData["strUnit"];
    $intQuantity = $donationData["intQuantity"];
    $strDescription = $donationData["strDescription"];
    $ysnStatus = intval($donationData["ysnStatus"]);

    $conn->begin_transaction();

    try {
        $strFoodBank;
        $intItemId;
        $intCategoryId;
        $intUnitId;
        $intInventoryId;
        
        header("Content-Type: application/json");

        // Food Bank check
        $foodBankResult = $conn->query("SELECT strFoodBank FROM tblfoodbank WHERE intFoodBankId = $intFoodBankId");        
        // Item check
        $itemResult = $conn->query("SELECT intItemId FROM tblitem WHERE strItem = '$strItem'");
        // Category check
        $categoryResult = $conn->query("SELECT intCategoryId FROM tblcategory WHERE strCategory = '$strCategory'");
        // Unit check
        $unitResult = $conn->query("SELECT intUnitId FROM tblunit WHERE strUnit = '$strUnit'");
        // Inventory check
        $inventoryResult = $conn->query("SELECT intInventoryId FROM tblinventory WHERE intDonationId = $intDonationId");

        if ($foodBankResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Food Bank"]]);
            exit();
        } else if ($itemResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Item"]]);
            exit();
        } else if ($categoryResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Category"]]);
            exit();
        } else if ($unitResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid Unit"]]);
            exit();
        } else if ($inventoryResult->num_rows == 0) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Update failed, cannot find donation detail"]]);
            exit();
        } else {
            $strFoodBank = $foodBankResult->fetch_object()->strFoodBank;
            $intItemId = $itemResult->fetch_object()->intItemId;
            $intCategoryId = $categoryResult->fetch_object()->intCategoryId;
            $intUnitId = $unitResult->fetch_object()->intUnitId;
            $intInventoryId = $inventoryResult->fetch_object()->intInventoryId;
        }

        $query1 = $conn->prepare("UPDATE tbldonationmanagement SET 
            strDonorName = ?
            ,dtmDate = ?
            ,strFoodBank = ?
            ,strTitle = ?
            ,strDescription = ?
            ,strRemarks = ?
            ,ysnStatus = ?
            ,strDocFilePath = ?
            WHERE intDonationId = ?"
        );

        $query1->bind_param("ssssssisi"
            ,$donationData["strDonorName"]
            ,$donationData["dtmDate"]
            ,$strFoodBank
            ,$donationData["strTitle"]
            ,$donationData["strDescription"]
            ,$donationData["strRemarks"]
            ,$ysnStatus
            ,$donationData["strDocFilePath"]
            ,$intDonationId
        );

        if (!$query1->execute()) {
            http_response_code(500);
            echo json_encode(["data" => ["message" => "Donation update failed, ".$query1->error]]);
            exit();
        }

        $query2 = $conn->prepare("UPDATE tblinventory SET 
            intFoodBankId = ?
            ,intItemId = ?
            ,intCategoryId = ?
            ,intUnitId = ?
            ,intQuantity = ?
            WHERE intInventoryId = ?"
        );

        $query2->bind_param("iiiiii"
            ,$intFoodBankId
            ,$intItemId
            ,$intCategoryId
            ,$intUnitId
            ,$intQuantity
            ,$intInventoryId
        );

        if (!$query2->execute()) {
            http_response_code(500);
            echo json_encode(["data" => ["message" => "Inventory update failed, ".$query2->error]]);
            exit();
        }

        $conn->commit();

        $query1->close();
        $query2->close();

        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation updated successfully"]]);
    } catch (Exception $ex) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Update failed, please check user input"]]);
    }

    exit();
}

function archiveDonation($conn, $intDonationId) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into tbldonationarchive
        $query1 = $conn->prepare("INSERT INTO tbldonationarchive SELECT * FROM tbldonationmanagement WHERE intDonationId = ?");
        $query1->bind_param("s", $intDonationId);

        if (!$query1->execute()) {
            throw new Exception("Error inserting into archive: " . $query1->error);
        }

        // Delete the donation record from tbldonationmanagement
        $query2 = $conn->prepare("DELETE FROM tbldonationmanagement WHERE intDonationId = ?");
        $query2->bind_param("s", $intDonationId);

        if (!$query2->execute()) {
            throw new Exception("Error deleting from tbldonationmanagement: " . $query2->error);
        }

        // Commit the transaction if both queries succeeded
        $conn->commit();

        // Return success response
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation record moved from donation to archive"]]);
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();

        // Return error response
        http_response_code(400);
        echo json_encode(["data" => ["message" => $e->getMessage()]]);
    }

    exit();
}

function unarchiveDonation($conn, $intDonationId) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into tbldonationmanagement
        $query1 = $conn->prepare("INSERT INTO tbldonationmanagement SELECT * FROM tbldonationarchive WHERE intDonationId = ?");
        $query1->bind_param("s", $intDonationId);

        if (!$query1->execute()) {
            throw new Exception("Error moving archive into donation: " . $query1->error);
        }

        // Delete the donation record from tbldonationarchive
        $query2 = $conn->prepare("DELETE FROM tbldonationarchive WHERE intDonationId = ?");
        $query2->bind_param("s", $intDonationId);

        if (!$query2->execute()) {
            throw new Exception("Error deleting from tbldonationarchive: " . $query2->error);
        }

        // Commit the transaction if both queries succeeded
        $conn->commit();

        // Return success response
        http_response_code(200);
        echo json_encode(["data" => ["message" => "Donation record moved from archive to donation"]]);
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();

        // Return error response
        http_response_code(400);
        echo json_encode(["data" => ["message" => $e->getMessage()]]);
    }

    exit();
}
?>