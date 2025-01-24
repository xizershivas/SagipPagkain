<?php
include "../functions/user.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval(sanitize($_GET['intUserId']));

    // Validate that the intUserId is a valid integer
    if (!filter_var($intUserId, FILTER_VALIDATE_INT)) {
        echo json_encode(["Error" => "Invalid request"]);
    }

    $result = editUser($conn, $intUserId);
    echo json_encode($result);
}

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Get the RAW PUT data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an associative array
    $userData = json_decode($inputData, true);

    // Check if JSON decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        $intUserId = $userData['intUserId'];
        $strUsername = sanitize($userData['strUsername']);
        $strEmail = sanitizeEmail($userData['strEmail']) ?? '';
        $ysnEnabled = $userData['ysnEnabled'] ?? 0;
        $ysnApproved = $userData['ysnApproved'] ?? 0;

        $result = updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnEnabled, $ysnApproved);
        echo json_encode($result);
    }       

    echo json_encode(["Error: " => $e->getMessage()]);
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an associative array
    $userData = json_decode($inputData, true);

    $result = deleteUser($conn, $userData['intUserId']);

    echo json_encode($result);
}
?>