<?php
header("Content-Type: application/json");

function loadData($conn) {
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
        
        #BEGIN FOODBANK
        // $sqlFoodBank = "
        //     SELECT DISTINCT FB.intFoodBankId, FB.strFoodBank FROM tblfoodbank FB
        //     INNER JOIN tblinventory I ON FB.intFoodBankId = I.intFoodBankId
        // ";

        // $queryFoodBank = $conn->query($sqlFoodBank);

        // if (!$queryFoodBank) {
        //     throw new Exception("Database operation failed", 500);
        // }

        // if ($queryFoodBank->num_rows == 0) {
        //     throw new Exception("No records found, 404");
        // }

        // // List of Food Banks
        // $foodBanks = [];
        // while($foodBank = $queryFoodBank->fetch_object()) {
        //     $foodBanks[] = $foodBank;
        // }
        #END FOODBANK

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
    try {
        $sql = "SELECT DISTINCT FB.strFoodBank FROM tbldonationmanagement DM
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
}

function getItem($conn, $intUserId, $strFoodBank) {
    try {
        $sql = "SELECT DISTINCT IV.intItemId, I.strItem FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            WHERE DM.intUserId = ? AND FB.strFoodBank = ?;";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("is", $intUserId, $strFoodBank);
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
}

function getItemQuantity($conn, $intUserId, $strFoodBank, $intItemId) {
    try {
        $sql = "SELECT SUM(IV.intQuantity) AS intQuantity, U.strUnit FROM tbldonationmanagement DM
            INNER JOIN tblinventory IV ON DM.intDonationId = IV.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            WHERE DM.intUserId = ? AND FB.strFoodBank = ? AND IV.intItemId = ?
            GROUP BY IV.intItemId";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("isi", $intUserId, $strFoodBank, $intItemId);
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
}
?>