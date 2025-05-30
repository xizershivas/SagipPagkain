<?php
function getSourceFoodBanks($conn) {
    $sourceFoodBankData = $conn->query("SELECT DISTINCT IV.intFoodBankId, FB.strFoodBank FROM tblinventory IV
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

        http_response_code(201);
        echo json_encode(["data" => ["message" => "Successfully created inventory transfer details"]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => "Failed to process inventory transfer. " . $ex->getMessage()]]);
    }

    exit();
}

function processInventoryTransfer($conn, $data) {
    header("Content-Type: application/json");

    $intSourceFoodBankId = $data["intSourceFoodBankId"];
    $intTargetFoodBankId = $data["intTargetFoodBankId"];
    $intItemId = $data["intItemId"];
    $intAvailableQty = $data["intAvailableQty"];
    $strUnit = $data["strUnit"];
    $intTransferQty = $data["intTransferQty"];

    $unitQuery = $conn->prepare("SELECT intUnitId FROM tblunit WHERE strUnit = ?");
    $unitQuery->bind_param("s", $strUnit);
    $unitQuery->execute();
    $unitResult = $unitQuery->get_result()->fetch_object();
    $intUnitId = $unitResult->intUnitId;

    $conn->begin_transaction();

    try {
        // Deduct from source inventory
        $deductSql = "UPDATE tblinventory 
                      SET intQuantity = intQuantity - ? 
                      WHERE intFoodBankId = ? AND intItemId = ? AND intUnitId = ?";
        $deductStmt = $conn->prepare($deductSql);
        $deductStmt->bind_param("iiii", $intTransferQty, $intSourceFoodBankId, $intItemId, $intUnitId);
        if (!$deductStmt->execute()) {
            throw new Exception("Failed to deduct inventory from source", 400);
        }

        // Add to target inventory
        // First, check if an entry exists for the target food bank
        $checkSql = "SELECT intQuantity FROM tblinventory 
                     WHERE intFoodBankId = ? AND intItemId = ? AND intUnitId = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("iii", $intTargetFoodBankId, $intItemId, $intUnitId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Entry exists, update it
            $updateSql = "UPDATE tblinventory 
                          SET intQuantity = intQuantity + ? 
                          WHERE intFoodBankId = ? AND intItemId = ? AND intUnitId = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("iiii", $intTransferQty, $intTargetFoodBankId, $intItemId, $intUnitId);
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to add inventory to target", 400);
            }
        } else {
            // Entry does not exist, insert it
            $insertSql = "INSERT INTO tblinventory (intFoodBankId, intItemId, intQuantity, intUnitId) 
                          VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("iiii", $intTargetFoodBankId, $intItemId, $intTransferQty, $intUnitId);
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert inventory for target", 400);
            }
        }

        $conn->commit();
        echo json_encode(["data" => ["message" => "Inventory transfer processed successfully."]]);
    } catch (Exception $ex) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Failed to process inventory transfer. " . $ex->getMessage()]]);
    }

    exit();
}
?>