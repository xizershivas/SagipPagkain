<?php
include "../config/db_connection.php";
include "../utils/sanitize.php";
include "../functions/signup.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $strUsername = sanitize($_POST["username"]);
    $strFullName = sanitize($_POST["fullname"]);
    $strContact = sanitize($_POST["contact"]);
    $strEmail = sanitize($_POST["email"]);
    $strPassword = sanitize($_POST["password"]);
    $strConfirmPassword = sanitize($_POST["confirmPassword"]);
    $strAccountType = sanitize($_POST["accountType"]);
    $strSpecifyOther = sanitize($_POST["specifyOther"]);

    register($conn, $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strConfirmPassword, $strAccountType, $strSpecifyOther);

    $conn->close();
}
?>