<?php
include "../config/db_connection.php";
include "../functions/manageBeneficiary.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intBeneficiaryId = intval(sanitize($_GET["intBeneficiaryId"]));
    editBeneficiary($conn, $intBeneficiaryId);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intBeneficiaryId = isset($_POST["beneficiaryId"]) ? intval($_POST["beneficiaryId"]) : "";
    $strName = sanitize($_POST["name"]);
    $strEmail = sanitize($_POST["email"]);
    $strContact = sanitize($_POST["contact"]);
    $strAddress = sanitize($_POST["address"]);
    $dblSalary =  sanitize($_POST["salary"]);

    $userData = [
        "intBeneficiaryId" => $intBeneficiaryId
        , "strName" => $strName
        , "strEmail" => $strEmail
        , "strContact" => $strContact
        , "strAddress" => $strAddress
        , "dblSalary" => $dblSalary
    ];

    updateBeneficiary($conn, $userData);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $data = json_decode($inputData);

    deleteBeneficiary($conn, $data->intBeneficiaryId);

    $conn->close();
}
?>