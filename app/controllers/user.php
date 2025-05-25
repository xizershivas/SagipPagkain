<?php
include "../config/db_connection.php";
include "../functions/user.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval(sanitize($_GET["intUserId"]));
    editUser($conn, $intUserId);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userData = file_get_contents('php://input');

    $strFullName = sanitize($_POST["fullname"]);
    $strEmail = sanitize($_POST["email"]);
    $strContact = sanitize($_POST["contact"]);
    $strUsername = sanitize($_POST["username"]);
    $strPassword = sanitize($_POST["password"]);
    $strConfirmPassword = sanitize($_POST["confirmPassword"]);
    $strAccountType = sanitize($_POST["accountType"]);
    $ysnStatus = intval($_POST["status"]);

    $userData = [
        'strFullName' => $strFullName,
        'strEmail' => $strEmail,
        'strContact' => $strContact,
        'strUsername' => $strUsername,
        'strPassword' => $strPassword,
        'strConfirmPassword' => $strConfirmPassword,
        'strAccountType' => $strAccountType,
        'ysnStatus' => $ysnStatus
    ];

    addUser($conn, $userData);

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
        $ysnStaff = $userData->ysnStaff ?? 0;
        $ysnPartner = $userData->ysnPartner ?? 0;

        updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnActive, $ysnAdmin, $ysnDonor, $ysnStaff, $ysnPartner);
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