<?php
function getAllTrackDonationData($conn, $intUserId = "") {
    $ysnDonor;
    
    if (!empty($intUserId)) {
        $stmt = $conn->query("SELECT ysnDonor FROM tbluser WHERE intUserId = $intUserId LIMIT 1");
        $ysnDonor = $stmt->fetch_object()->ysnDonor;
    }

    if ($ysnDonor) {
        $allTrackDonationData = $conn->query("
            SELECT TD.intTrackDonationId
            ,TD.strTrackDonationNo
            ,U.intUserId
            ,U.strFullName
            ,FBD.intFoodBankDetailId
            ,FBD.strFoodBankName
            ,I.intItemId
            ,I.strItem
            ,TD.intQuantity
            ,UT.strUnit
            ,B.intBeneficiaryId
            ,B.strName
            ,TD.ysnStatus
            ,TD.strQRCode
            ,DATE_FORMAT(TD.dtmCreatedDate, '%Y-%m-%d') AS dtmCreatedDate
            FROM tbltrackdonation TD
            INNER JOIN tbluser U ON TD.intUserId = U.intUserId
            INNER JOIN tblfoodbankdetail FBD ON TD.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON TD.intItemId = I.intItemId
            INNER JOIN tblunit UT ON TD.intUnitId = UT.intUnitId
            INNER JOIN tblbeneficiary B ON TD.intBeneficiaryId = B.intBeneficiaryId
            WHERE TD.intUserId = $intUserId"
        );
    }
    else {
        $allTrackDonationData = $conn->query("
            SELECT TD.intTrackDonationId
            ,TD.strTrackDonationNo
            ,U.intUserId
            ,U.strFullName
            ,FBD.intFoodBankDetailId
            ,FBD.strFoodBankName
            ,I.intItemId
            ,I.strItem
            ,TD.intQuantity
            ,UT.strUnit
            ,B.intBeneficiaryId
            ,B.strName
            ,TD.ysnStatus
            ,TD.strQRCode
            ,DATE_FORMAT(TD.dtmCreatedDate, '%Y-%m-%d') AS dtmCreatedDate
            FROM tbltrackdonation TD
            INNER JOIN tbluser U ON TD.intUserId = U.intUserId
            INNER JOIN tblfoodbankdetail FBD ON TD.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON TD.intItemId = I.intItemId
            INNER JOIN tblunit UT ON TD.intUnitId = UT.intUnitId
            INNER JOIN tblbeneficiary B ON TD.intBeneficiaryId = B.intBeneficiaryId"
        );
    }

    return $allTrackDonationData;
}

function loadData($conn) {
    header("Content-Type: application/json");

    try {
        #BEGIN DONOR
        $sqlDonor = "SELECT DISTINCT D.intUserId, U.strFullName FROM tbldonationmanagement D
                    INNER JOIN tblinventory I ON D.intDonationId = I.intDonationId
                    INNER JOIN tbluser U ON D.intUserId = U.intUserId
                    ";
    
        $queryDonor = $conn->query($sqlDonor);
    
        if (!$queryDonor) {
            throw new Exception("Database operation failed", 500);
        }

        if ($queryDonor->num_rows == 0) {
            throw new Exception("No records found", 202);
        }

        // List of Donors
        $donors = [];
        while($donor = $queryDonor->fetch_object()) {
            $donors[] = $donor;
        }
        #END DONOR

        #BEGIN BENEFICIARY
        $sqlBeneficiary = "SELECT intBeneficiaryId, strName FROM tblbeneficiary";

        $queryBeneficiary = $conn->query($sqlBeneficiary);

        if (!$queryBeneficiary) {
            throw new Exception("Database operation failed", 500);
        }

        if ($queryBeneficiary->num_rows == 0) {
            throw new Exception("No records found", 202);
        }

        // List of Beneficiaries
        $beneficiaries = [];
        while($beneficiary = $queryBeneficiary->fetch_object()) {
            $beneficiaries[] = $beneficiary;
        }
        #END BENEFICIARY

        $data = ["data" => [
            "donors" => $donors
            // ,"foodBanks" => $foodBanks
            ,"beneficiaries" => $beneficiaries
            ,"success" => true
        ]];

        http_response_code(200);
        echo json_encode($data);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        error_log("Exception occurred in {$file} on line $line: [Code $code]");
        http_response_code($code);
        echo json_encode(["data" => ["message" => $message, "records" => 0, "success" => false]]);
    }

    exit();
}

function getFoodBank($conn, $intUserId) {
    header("Content-Type: application/json");

    try {
        $sql = "SELECT DISTINCT FBD.intFoodBankDetailId, FBD.strFoodBankName FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            WHERE DM.intUserId = ?";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("i", $intUserId);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            throw new Exception("No records found", 202);
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $responseData = [];

        foreach($data as $row) {
            $responseData[] = $row;
        }

        http_response_code(200);
        echo json_encode(["data" => ["foodBanks" => $responseData, "success" => true]]);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        error_log("Exception occurred in {$file} on line $line: [Code $code]");
        http_response_code($code);
        echo json_encode(["data" => ["message" => $message, "records" => 0, "success" => false]]);
    }

    exit();
}

function getItem($conn, $intUserId, $intFoodBankId) {
    header("Content-Type: application/json");

    try {
        $sql = "SELECT DISTINCT IV.intItemId, I.strItem FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            WHERE DM.intUserId = ? AND IV.intFoodBankDetailId = ?;";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("ii", $intUserId, $intFoodBankId);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            throw new Exception("No records found", 202);
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $responseData = [];

        foreach($data as $row) {
            $responseData[] = $row;
        }

        http_response_code(200);
        echo json_encode(["data" => ["items" => $responseData, "success" => true]]);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        error_log("Exception occurred in {$file} on line $line: [Code $code]");
        http_response_code($code);
        echo json_encode(["data" => ["message" => $message, "records" => 0, "success" => false]]);
    }

    exit();
}

function getItemQuantity($conn, $intUserId, $intFoodBankId, $intItemId) {
    header("Content-Type: application/json");

    try {
        $sql = "SELECT SUM(IV.intQuantity) AS intQuantity, U.strUnit FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            WHERE DM.intUserId = ? AND IV.intFoodBankDetailId = ? AND IV.intItemId = ?
            GROUP BY IV.intItemId";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("iii", $intUserId, $intFoodBankId, $intItemId);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            throw new Exception("No records found", 202);
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $intQuantity = $data[0]["intQuantity"];
        $strUnit = $data[0]["strUnit"];

        http_response_code(200);
        echo json_encode(["data" => ["intQuantity" => $intQuantity, "strUnit" => $strUnit, "success" => true]]);
    } catch (Exception $ex) {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        error_log("Exception occurred in {$file} on line $line: [Code $code]");
        http_response_code($code);
        echo json_encode(["data" => ["message" => $message, "records" => 0, "success" => false]]);
    }

    exit();
}

function saveTrackDonation($conn, $trackDonationData) {
    header("Content-Type: application/json");

    $conn->begin_transaction();

    try {
        $intUserId = $trackDonationData["intUserId"];
        $intFoodBankId = $trackDonationData["intFoodBankId"];
        $intItemId = $trackDonationData["intItemId"];
        $intQuantity = $trackDonationData["intQuantity"];
        $strUnit = $trackDonationData["strUnit"];
        $intSendQuantity = $trackDonationData["intSendQuantity"];
        $intBeneficiaryId = $trackDonationData["intBeneficiaryId"];
        $ysnStatus = $trackDonationData["ysnStatus"];
        
        #BEGIN Add Record to tbltrackdonation
        $unitResult = $conn->query("SELECT intUnitId FROM tblunit WHERE strUnit = '$strUnit'");
        $intUnitId = $unitResult->fetch_object()->intUnitId;

        $sql = "INSERT INTO tbltrackdonation (intUserId, intFoodBankDetailId, intItemId, intQuantity, intUnitId, intBeneficiaryId, ysnStatus)
        VALUES (?,?,?,?,?,?,?)";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("iiiiiii", $intUserId, $intFoodBankId, $intItemId, $intSendQuantity, $intUnitId, $intBeneficiaryId, $ysnStatus);
        
        if (!$query->execute()) {
            throw new Exception("Failed to process data", 400);
        }

        $lastInsertId = $conn->insert_id;

        $donationNoSql = "UPDATE tbltrackdonation SET strTrackDonationNo = ? WHERE intTrackDonationId = ?";
        $stmtDonationNo = $conn->prepare($donationNoSql);

        if (!$stmtDonationNo) {
            throw new Exception("Database update tbltrackdonation query failed", 500);
        }

        $strTrackDonationNo = "DNT-" . $lastInsertId;
        $stmtDonationNo->bind_param("si", $strTrackDonationNo, $lastInsertId);

        if (!$stmtDonationNo->execute()) {
            throw new Exception("Failed to update track donation no.", 400);
        }
        #END Add Record to tbltrackdonation

        #BEGIN Update Inventory Item Quantity
        $sql2 = "
            UPDATE tblinventory IV
            JOIN tbldonationmanagement DM ON IV.intDonationId = DM.intDonationId
            SET IV.intQuantity = CASE
                                    WHEN IV.intQuantity >= ? THEN IV.intQuantity - ?
                                    ELSE 0
                                END
            WHERE DM.intUserId = ? 
            AND IV.intFoodBankDetailId = ? 
            AND IV.intItemId = ?
            AND IV.intQuantity > 0;
        ";
        #END Update Inventory Item Quantity

        $query2 = $conn->prepare($sql2);
        $query2->bind_param("iiiii", $intSendQuantity, $intSendQuantity, $intUserId, $intFoodBankId, $intItemId);

        if (!$query2->execute()) {
            throw new Exception("Database update operation failed", 500);
        }

        generateQRCode($conn, $intBeneficiaryId, $intFoodBankId, $intItemId, $intSendQuantity, $lastInsertId);

        $conn->commit();

        http_response_code(201);
        echo json_encode(["data" => ["message" => "Donation assigned successfully", "success" => true]]);
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        error_log("Exception occurred in {$file} on line $line: [Code $code]");
        http_response_code($code);
        echo json_encode(["data" => ["message" => $message, "records" => 0, "success" => false]]);
    }

    exit();
}

function generateQRCode($conn, $intBeneficiaryId, $intFoodBankId, $intItemId, $intSendQuantity, $lastInsertId) {
    
    include "../../app/utils/phpqrcode/qrlib.php";

    // Beneficiary
    $beneficiaryQuery = $conn->prepare("SELECT strName FROM tblbeneficiary WHERE intBeneficiaryId = ?");
    $beneficiaryQuery->bind_param("i", $intBeneficiaryId);
    $beneficiaryQuery->execute();
    $beneficiaryResult = $beneficiaryQuery->get_result()->fetch_assoc();

    // Inventory Item and Item Quantity
    $itemQuery = $conn->prepare("SELECT I.strItem FROM tblitem I WHERE I.intItemId = ?");
    $itemQuery->bind_param("i", $intItemId);
    $itemQuery->execute();
    $itemResult = $itemQuery->get_result()->fetch_assoc();

    // Food Bank
    $foodBankQuery = $conn->prepare("SELECT FB.strFoodBankName FROM tblfoodbankdetail FB WHERE FB.intFoodBankDetailId = ?");
    $foodBankQuery->bind_param("i", $intFoodBankId);
    $foodBankQuery->execute();
    $foodBankResult = $foodBankQuery->get_result()->fetch_assoc();

    // Date Received
    $donationQuery = $conn->prepare("SELECT U.strUnit, DATE(TD.dtmCreatedDate) AS dtmDateReceived FROM tbltrackdonation TD INNER JOIN tblunit U ON TD.intUnitId = U.intUnitId WHERE TD.intTrackDonationId = ?");
    $donationQuery->bind_param("i", $lastInsertId);
    $donationQuery->execute();
    $donationResult = $donationQuery->get_result()->fetch_assoc();

    $data = "Name: " . $beneficiaryResult["strName"]
        . "\nFood Bank: " . $foodBankResult["strFoodBankName"]
        . "\nItem: " . $itemResult["strItem"]
        . "\nQty Received: " . $intSendQuantity . " " . $donationResult["strUnit"]
        . "\nDate Received: " . $donationResult["dtmDateReceived"];

    $filePath = '../../app/storage/media/qrcodes/' . $intBeneficiaryId . '_' . $beneficiaryResult["strName"] . '_' . uniqid(). '.png';
    QRcode::png($data, $filePath, QR_ECLEVEL_L, 1000/150);

    // Update Track Donation QR Code
    $updateQuery = $conn->prepare("UPDATE tbltrackdonation SET strQRCode = ? WHERE intTrackDonationId = ?");
    $updateQuery->bind_param("si", $filePath, $lastInsertId);

    if (!$updateQuery->execute()) {
        throw new Exception("Failed to generate and update QR Code", 400);
    }
}
?>