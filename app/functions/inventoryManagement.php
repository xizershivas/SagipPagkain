<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Populate input datalist
function getDataListOptions($conn, $filter = "strCategory", $search = "", $page = 1, $limit = 5) {
    header("Content-Type: application/json");

    try {
        $dbTable;
        
        switch($filter) {
            case "strItem": $dbTable = "tblitem"; break;
            case "strUnit": $dbTable = "tblunit"; break;
            case "strMunicipality": $dbTable = "tblfoodbank"; break;
            default: $dbTable = "tblcategory"; break;
        }

        $query = $conn->query("SELECT * FROM $dbTable");
        $dataListOptions = [];

        while($row = $query->fetch_object()) {
            $dataListOptions[] = $row;
        }

        getInventoryData($conn, $dataListOptions, $filter, $search, $page, $limit);
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Internal server error, ".$ex->getMessage()]]);
    }

    exit();
}

function getInventoryData($conn, $dataListOptions = "", $filter, $search, $page, $limit) {
    header("Content-Type: application/json");

    $totalQuery = $conn->query("SELECT COUNT(*) AS total FROM tblinventory WHERE intQuantity > 0");
    $totalResult = $totalQuery->fetch_assoc();
    $totalRecords = $totalResult['total'];

    $page = max(1, intval($page)); // ensure at least page 1
    $limit = max(1, intval($limit)); // ensure at least 1 item per page

    $offset = ($page - 1) * $limit;

    try {
        if (empty($search)) {
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
            ,DATE_FORMAT(IV.dtmExpirationDate, '%M %d, %Y') AS dtmExpirationDate
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbankdetail FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            WHERE IV.intQuantity > 0
            LIMIT $limit OFFSET $offset";

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
            echo json_encode(["data" => [
                "dataListOptions" => $dataListOptions, 
                "inventoryData" => $inventoryData, 
                "totalRecords" => $totalRecords
            ]]);
        } 
        else {
            $dbTable;
    
            // Table aliases
            switch($filter) {
                case "strItem": $dbTable = "I"; break;
                case "strUnit": $dbTable = "U"; break;
                case "strMunicipality": $dbTable = "FB"; break;
                default: $dbTable = "C"; break;
            }
    
            $sql = "SELECT IV.intInventoryId 
            ,IV.intQuantity
            ,D.intDonationId
            ,FBD.intFoodBankDetailId
            ,FBD.strFoodBankName
            ,I.intItemId
            ,I.strItem AS strItem
            ,C.intCategoryId
            ,C.strCategory AS strCategory
            ,U.intUnitId
            ,U.strUnit AS strUnit
            ,DATE_FORMAT(IV.dtmExpirationDate, '%M %d, %Y') AS dtmExpirationDate
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbank FBD ON IV.intFoodBankDetailId = FBD.intFoodBankDetailId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId
            WHERE $dbTable.$filter LIKE ?
            LIMIT $limit OFFSET $offset";
    
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
            echo json_encode(["data" => [
                "dataListOptions" => $dataListOptions, 
                "inventoryData" => $inventoryData, 
                "totalRecords" => $totalRecords
            ]]);
        }
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["data" => ["dataListOptions" => $dataListOptions, "message" => $ex->getMessage(), "inventoryData" => 0]]);
    }

    exit();
}
?>