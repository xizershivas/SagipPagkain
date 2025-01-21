<?php
require "src/config/db_connection.php";
require "src/app/user.php";

if (isset($_REQUEST['intUserId'])) {
    $intUserId = sanitize($_REQUEST['intUserId']);
    
    if (!filter_var($intUserId, FILTER_VALIDATE_INT)) {
        echo json_encode(['error' => 'Invalid request']);
    } else {
        // echo json_encode(['intUserId' => $intUserId]);
        $result = editUser($conn, $intUserId);
        echo json_encode($result);
    }

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
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
    
    $conn->close();
}
?>