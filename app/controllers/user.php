<?php
include "../config/db_connection.php";
include "../functions/user.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["intUserId"])) {
    $intUserId = intval(sanitize($_GET["intUserId"]));
    editUser($conn, $intUserId);
    $conn->close();
}

// CREATE
if (!isset($_POST["_method"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
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

// UPDATE
if (isset($_POST["_method"]) && $_POST["_method"] === "PUT") {
    $intUserId = intval($_POST["userId"]);
    $strUsername = sanitize($_POST["user"]);
    $strEmail = sanitizeEmail($_POST["userEmail"]) ?? '';
    $strFullName = sanitize($_POST["fullName"]);
    $strContact = sanitize($_POST["userContact"]) ?? '';
    $strAddress = sanitize($_POST["address"]) ?? '';
    $dblSalary = floatval($_POST["salary"]) ?? 0;
    $ysnActive = isset($_POST["active"]) ? 1 : 0;
    $ysnAdmin = isset($_POST["admin"]) ? 1 : 0;
    $ysnDonor = isset($_POST["donor"]) ? 1 : 0;
    $ysnStaff = isset($_POST["staff"]) ? 1 : 0;
    $ysnPartner = isset($_POST["partner"]) ? 1 : 0;
    $ysnBeneficiary = isset($_POST["beneficiary"]) ? 1 : 0;

    $userData = [
        "intUserId" => $intUserId
        , "strUsername" => $strUsername
        , "strEmail" => $strEmail
        , "strFullName" => $strFullName
        , "strContact" => $strContact
        , "strAddress" => $strAddress
        , "dblSalary" => $dblSalary
        , "ysnActive" => $ysnActive
        , "ysnAdmin" => $ysnAdmin
        , "ysnDonor" => $ysnDonor
        , "ysnStaff" => $ysnStaff
        , "ysnPartner" => $ysnPartner
        , "ysnBeneficiary" => $ysnBeneficiary
    ];

    updateUser($conn, $userData);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $userData = json_decode($inputData);

    deleteUser($conn, $userData->intUserId);

    $conn->close();
}
?>