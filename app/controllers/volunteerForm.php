<?php
include "../config/db_connection.php";
include "../functions/volunteerForm.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $strFirstName = sanitize($_POST['firstname']);
    $strLastName = sanitize($_POST['lastname']);
    $strGender = sanitize($_POST['gender']);
    $dtmDateOfBirth = sanitize($_POST['dateOfBirth']);
    $strStreet = sanitize($_POST['street']);
    $strStreet = sanitize($_POST['address']);
    $strCity = sanitize($_POST['city']);
    $strRegion = sanitize($_POST['region']);
    $strZipCode = sanitize($_POST['zipcode']);
    $strCountry = sanitize($_POST['country']);
    $strContact = sanitize($_POST['contact']);
    $strEmail = sanitizeEmail($_POST['email']);
    $ysnTermsOfVolunteering = $_POST['terms'] == "on" ? 1 : 0;

    if (isset($_POST["terms"]) && $_POST["terms"] == "on") {
        // Handle Signature Image Upload
        $strSignFilePath = processSignFileUpload();
        
        if (!empty($strSignFilePath)) {
            $query = $conn->prepare("INSERT INTO tblvolunteer 
                (strFirstName, strLastName, strGender, dtmDateOfBirth, strStreet, 
                strAddress, strCity, strRegion, strZipCode, strCountry, 
                strContact, strEmail, ysnTermsOfVolunteering, strSignFilePath) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $query->bind_param("ssssssssssssis"
                ,$strFirstName
                ,$strLastName
                ,$strGender
                ,$dtmDateOfBirth
                ,$strStreet
                ,$strStreet
                ,$strCity
                ,$strRegion
                ,$strZipCode
                ,$strCountry
                ,$strContact
                ,$strEmail
                ,$ysnTermsOfVolunteering
                ,$strSignFilePath
            );

            if ($query->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Volunteer Form submitted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => $query->error]);
            }

            $query->close();
            $conn->close();
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Server encountered an error, submit failed."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => $query->error]);
    }
}

?>