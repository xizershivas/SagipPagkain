<?php
function getSourceFoodBanks($conn) {
    $sourceFoodBankData = $conn->query("SELECT DISTINCT IV.intFoodBankId, FB.strMunicipality FROM tblinventory IV
        INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
    ");
    return $sourceFoodBankData;
}

function getAllFoodBanks($conn) {
    $allFoodBanks = $conn->query("SELECT * FROM tblfoodbank");
    return $allFoodBanks;
}

function getAllItems($conn) {
    $allItems = $conn->query("SELECT * FROM tblitem");
    return $allItems;
}

function getAllCategories($conn) {
    $allCategories = $conn->query("SELECT * FROM tblcategory");
    return $allCategories;
}

function getAllUnits($conn) {
    $allUnits = $conn->query("SELECT * FROM tblunit");
    return $allUnits;
}

function getAvailableItems($conn, $intFoodBankId) {
    header("Content-Type: application/json");

    $sql = "SELECT IV.intItemId
        , I.strItem
        , SUM(IV.intQuantity) AS intQuantity
        , IV.intUnitId
        , U.strUnit 
        FROM tblinventory IV
        INNER JOIN tblitem I ON IV.intItemId = I.intItemId
        INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
        WHERE IV.intFoodBankId = ?
        GROUP BY IV.intItemId, I.strItem, IV.intUnitId, U.strUnit
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Database operation failed"]]);
        exit();
    }

    $stmt->bind_param("i", $intFoodBankId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    http_response_code(200);
    echo json_encode(["data" => $result]);
    exit();
}

function saveInventoryTransferDetails($conn, $data) {
    header("Content-Type: application/json");

    $intInventoryId = $data["intInventoryId"];
    $intSourceFoodBankId = $data["intSourceFoodBankId"];
    $intTargetFoodBankId = $data["intTargetFoodBankId"];
    $intItemId = $data["intItemId"];
    $intCategoryId = $data["intCategoryId"];
    $intUnitId = $data["intUnitId"];
    $intAvailableQty = $data["intAvailableQty"];
    $dtmExpirationDate = $data["dtmExpirationDate"];
    $intTransferQty = $data["intTransferQty"];

    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO tblinventorytransfer (
                    intInventoryId, intSourceFoodBankId, intTargetFoodBankId, 
                    intItemId, intCategoryId, intUnitId, 
                    intAvailableQty, dtmExpirationDate, intTransferQty
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Database operation failed", 500);
        }

        $stmt->bind_param("iiiiiiisi", 
            $intInventoryId,
            $intSourceFoodBankId,
            $intTargetFoodBankId,
            $intItemId,
            $intCategoryId,
            $intUnitId,
            $intAvailableQty,
            $dtmExpirationDate,
            $intTransferQty
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to process data", 400);
        }

        $conn->commit();

        processInventoryTransfer($conn, $data);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function processInventoryTransfer($conn, $data) {
    header("Content-Type: application/json");

    $intInventoryId = $data["intInventoryId"];
    $intDonationId = $data["intDonationId"];
    $intSourceFoodBankId = $data["intSourceFoodBankId"];
    $intTargetFoodBankId = $data["intTargetFoodBankId"];
    $intItemId = $data["intItemId"];
    $intCategoryId = $data["intCategoryId"];
    $intUnitId = $data["intUnitId"];
    $intAvailableQty = $data["intAvailableQty"];
    $dtmExpirationDate = $data["dtmExpirationDate"];
    $intTransferQty = $data["intTransferQty"];

    $conn->begin_transaction();

    try {
        // Deduct from source inventory
        $deductSql = "UPDATE tblinventory 
                      SET intQuantity = intQuantity - ? 
                      WHERE intInventoryId = ?";
        $deductStmt = $conn->prepare($deductSql);
        $deductStmt->bind_param("ii", $intTransferQty, $intInventoryId);

        if (!$deductStmt->execute()) {
            throw new Exception("Failed to deduct inventory from source", 400);
        }

        // Add to target inventory
        // First, check if an entry exists for the target food bank
        $checkSql = "SELECT intFoodBankId, intItemId, dtmExpirationDate, COUNT(*) AS intRecordCount FROM tblinventory 
                     WHERE intFoodBankId = ? AND intItemId = ? AND dtmExpirationDate = ?
                     GROUP BY intFoodBankId, intItemId, dtmExpirationDate";
        $checkStmt = $conn->prepare($checkSql);

        if (!$checkStmt) {
            throw new Exception("Database operation failed", 500);
        }

        $checkStmt->bind_param("iis", $intTargetFoodBankId, $intItemId, $dtmExpirationDate);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Entry exists, update it
            $updateSql = "UPDATE tblinventory 
                          SET intQuantity = intQuantity + ? 
                          WHERE intFoodBankId = ? AND intItemId = ? AND dtmExpirationDate = ?";
            $updateStmt = $conn->prepare($updateSql);

            if (!$updateStmt) {
                throw new Exception("Database operation failed", 500);
            }

            $updateStmt->bind_param("iiis", $intTransferQty, $intTargetFoodBankId, $intItemId, $dtmExpirationDate);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to process inventory transfer", 400);
            }
        } else {
            // Entry does not exist, insert it
            $insertSql = "INSERT INTO tblinventory (intDonationId, intFoodBankId, intItemId, intCategoryId, intUnitId, intQuantity, dtmExpirationDate) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            
            if (!$insertStmt) {
                throw new Exception("Database operation failed", 500);
            }

            $insertStmt->bind_param("iiiiiis", $intDonationId, $intTargetFoodBankId, $intItemId, $intCategoryId, $intUnitId, $intTransferQty, $dtmExpirationDate);

            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert inventory for target", 400);
            }
        }

        $conn->commit();
        echo json_encode(["data" => ["message" => "Inventory transfer processed successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}
?>