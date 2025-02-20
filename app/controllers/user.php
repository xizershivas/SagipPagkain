<?php
include "../config/db_connection.php";
include "../functions/user.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval(sanitize($_GET["intUserId"]));
    editUser($conn, $intUserId);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Get the RAW PUT data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $userData = json_decode($inputData);

    // Check if JSON decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        $intUserId = $userData->intUserId;
        $strUsername = sanitize($userData->strUsername);
        $strEmail = sanitizeEmail($userData->strEmail) ?? '';
        $ysnActive = $userData->ysnActive ?? 0;
        $ysnAdmin = $userData->ysnAdmin ?? 0;
        $ysnDonor = $userData->ysnDonor ?? 0;
        $ysnNgo = $userData->ysnNgo ?? 0;

        updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnActive, $ysnAdmin, $ysnDonor, $ysnNgo);
    }

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $userData = json_decode($inputData);

    deleteUser($conn, $userData->intUserId);

    $conn->close();
}
?>