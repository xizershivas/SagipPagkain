<?php
session_start();
include "../config/db_connection.php";
include "../functions/login.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the RAW POST data
    $inputData = file_get_contents("php://input");

    // Decode the JSON data into an object
    $userData = json_decode($inputData);

    $responseData = login($conn, $userData);

    if ($responseData["data"]["success"] == false) {
        http_response_code(401); // Unauthorized
        echo json_encode($responseData);
    } else {
        // SET Session variable on success
        $_SESSION['intUserId'] = $responseData["data"]["intUserId"];

        if ($responseData["data"]["ysnAdmin"] == 1) {
            $_SESSION['ysnAdmin'] = $responseData["data"]["ysnAdmin"];
        } else if ($responseData["data"]["ysnDonor"] == 1) {
            $_SESSION['ysnDonor'] = $responseData["data"]["ysnDonor"];
        } else if ($responseData["data"]["ysnPartner"] == 1) {
            $_SESSION['ysnPartner'] = $responseData["data"]["ysnPartner"];
        } else if ($responseData["data"]["ysnBeneficiary"] == 1) {
            $_SESSION['ysnBeneficiary'] = $responseData["data"]["ysnBeneficiary"];
        } else if ($responseData["data"]["ysnStaff"] == 1) {
            $_SESSION['ysnStaff'] = $responseData["data"]["ysnStaff"];
        }

        http_response_code(200);
        echo json_encode($responseData);
    }

    exit();
}

?>