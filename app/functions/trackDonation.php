<?php
function getAllTrackDonationData($conn) {
    $allTrackDonationData = $conn->query("
        SELECT TD.intTrackDonationId
        ,U.intUserId
        ,U.strFullName
        ,FB.intFoodBankId
        ,FB.strFoodBank
        ,I.intItemId
        ,I.strItem
        ,TD.intQuantity
        ,UT.strUnit
        ,B.intBeneficiaryId
        ,B.strName
        ,TD.ysnStatus
        FROM tbltrackdonation TD
        INNER JOIN tbluser U ON TD.intUserId = U.intUserId
        INNER JOIN tblfoodbank FB ON TD.intFoodBankId = FB.intFoodBankId
        INNER JOIN tblitem I ON TD.intItemId = I.intItemId
        INNER JOIN tblunit UT ON TD.intUnitId = UT.intUnitId
        INNER JOIN tblbeneficiary B ON TD.intBeneficiaryId = B.intBeneficiaryId"
    );
    return $allTrackDonationData;
}

function loadData($conn) {
    header("Content-Type: application/json");

    try {
        #BEGIN DONOR
        $sqlDonor = "
            SELECT DISTINCT D.intUserId, D.strDonorName FROM tbldonationmanagement D
            INNER JOIN tblinventory I ON D.intDonationId = I.intDonationId
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
        $sql = "SELECT DISTINCT FB.intFoodBankId, FB.strFoodBank FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
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
            WHERE DM.intUserId = ? AND IV.intFoodBankId = ?;";

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
            WHERE DM.intUserId = ? AND IV.intFoodBankId = ? AND IV.intItemId = ?
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

        $sql = "INSERT INTO tbltrackdonation (intUserId, intFoodBankId, intItemId, intQuantity, intUnitId, intBeneficiaryId, ysnStatus)
        VALUES (?,?,?,?,?,?,?)";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("iiiiiii", $intUserId, $intFoodBankId, $intItemId, $intSendQuantity, $intUnitId, $intBeneficiaryId, $ysnStatus);
        
        if (!$query->execute()) {
            throw new Exception("Failed to process data", 400);
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
            AND IV.intFoodBankId = ? 
            AND IV.intItemId = ?
            AND IV.intQuantity > 0;
        ";
        #END Update Inventory Item Quantity

        $query2 = $conn->prepare($sql2);
        $query2->bind_param("iiiii", $intSendQuantity, $intSendQuantity, $intUserId, $intFoodBankId, $intItemId);

        if (!$query2->execute()) {
            throw new Exception("Database update operation failed", 500);
        }

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
?>