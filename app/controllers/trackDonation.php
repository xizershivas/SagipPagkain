<?php
include "../config/db_connection.php";
include "../functions/trackDonation.php";
include "../utils/sanitize.php";

// POST REQUEST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData);
    $trackDonationData = [];

    if (json_last_error() === JSON_ERROR_NONE) {
        $trackDonationData = [
            "intUserId" => intval($data->intUserId)
            ,"intFoodBankId" => intval($data->intFoodBankId)
            ,"intItemId" => intval($data->intItemId)
            ,"intQuantity" => intval($data->intQuantity)
            ,"strUnit" => sanitize($data->strUnit)
            ,"intSendQuantity" => intval($data->intSendQuantity)
            ,"intBeneficiaryId" => intval($data->intBeneficiaryId)
            ,"ysnStatus" => intval($data->ysnStatus)
        ];

        saveTrackDonation($conn, $trackDonationData);
    }

    $conn->close();
}

// GET REQUEST
if (isset($_GET["user"]) && !empty($_GET["user"]) 
&& isset($_GET["foodBank"]) && !empty($_GET["foodBank"]) 
&& isset($_GET["item"]) && !empty($_GET["item"]) 
&& $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    $intFoodBankId = intval($_GET["foodBank"]);
    $intItemId = intval($_GET["item"]);
    getItemQuantity($conn, $intUserId, $intFoodBankId, $intItemId);
    $conn->close();
} else if (isset($_GET["user"]) && !empty($_GET["user"]) 
&& isset($_GET["foodBank"]) && !empty($_GET["foodBank"]) 
&& $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    $intFoodBankId = intval($_GET["foodBank"]);
    getItem($conn, $intUserId, $intFoodBankId);
    $conn->close();
} else if (isset($_GET["user"]) && !empty($_GET["user"]) 
&& $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    getFoodBank($conn, $intUserId);
    $conn->close();
} else {
    loadData($conn);
    $conn->close();
}
?>