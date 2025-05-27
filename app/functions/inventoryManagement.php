<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Populate input datalist
function getDataListOptions($conn, $filter, $search = "") {
    header("Content-Type: application/json");

    try {
        $dbTable;
        
        switch($filter) {
            case "strItem": $dbTable = "tblitem"; break;
            case "strUnit": $dbTable = "tblunit"; break;
            case "strFoodBank": $dbTable = "tblfoodbank"; break;
            default: $dbTable = "tblcategory"; break;
        }

        $query = $conn->query("SELECT * FROM $dbTable");
        $dataListOptions = [];

        while($row = $query->fetch_object()) {
            $dataListOptions[] = $row;
        }

        getInventoryData($conn, $dataListOptions, $filter, $search);
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Internal server error, ".$ex->getMessage()]]);
    }

    exit();
}

function getInventoryData($conn, $dataListOptions, $filter, $search) {
    header("Content-Type: application/json");

    try {
        if (empty($search)) {
            $sql = "SELECT IV.intInventoryId 
            ,IV.intQuantity
            ,D.intDonationId
            ,FB.intFoodBankId
            ,FB.strFoodBank
            ,I.intItemId
            ,I.strItem
            ,C.intCategoryId
            ,C.strCategory
            ,U.intUnitId
            ,U.strUnit
            ,DATE_FORMAT(IV.dtmExpirationDate, '%M %d, %Y') AS dtmExpirationDate
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId";

            $query = $conn->query($sql);

            if (!$query) {
                throw new Exception("Database operation failed, " . $conn->error);
            }

            if ($query->num_rows == 0) {
                http_response_code(200);
                echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "message" => "No records found"]]);
                exit();
            }

            $data = $query->fetch_all(MYSQLI_ASSOC);
            $inventoryData = [];

            foreach ($data as $row) {
                $inventoryData[] = $row;
            }

            http_response_code(200);
            echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "inventoryData" => $inventoryData]]);
        } 
        else {
            $dbTable;
    
            // Table aliases
            switch($filter) {
                case "strItem": $dbTable = "I"; break;
                case "strUnit": $dbTable = "U"; break;
                case "strFoodBank": $dbTable = "FB"; break;
                default: $dbTable = "C"; break;
            }
    
            $sql = "SELECT IV.intInventoryId 
            ,IV.intQuantity
            ,D.intDonationId
            ,FB.intFoodBankId
            ,FB.strFoodBank AS strFoodBank
            ,I.intItemId
            ,I.strItem AS strItem
            ,C.intCategoryId
            ,C.strCategory AS strCategory
            ,U.intUnitId
            ,U.strUnit AS strUnit
            ,DATE_FORMAT(IV.dtmExpirationDate, '%M %d, %Y') AS dtmExpirationDate
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            WHERE $dbTable.$filter LIKE ?";
    
            $query = $conn->prepare($sql);
            
            if (!$query) {
                throw new Exception("Error in preparing query: " . $conn->error);
            }

            $searchTerm = $search . "%";
            
            $query->bind_param("s", $searchTerm);
            $query->execute();
            $result = $query->get_result();
            
            if ($result->num_rows == 0) {
                http_response_code(202);
                echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "inventoryData" => 0]]);
                exit();
            }
    
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $responseData = [];
    
            foreach($data as $row) {
                $responseData[] = $row;
            }
    
            http_response_code(200);
            echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "inventoryData" => $responseData]]);
        }
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "message" => $ex->getMessage(), "inventoryData" => 0]]);
    }

    exit();
}
?>