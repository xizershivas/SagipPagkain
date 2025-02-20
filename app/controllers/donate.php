<?php
include "../config/db_connection.php";
include "../functions/donate.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $strDonorName = $_POST["fullname"];
    $dtmDate = $_POST["date"];
    $strTitle = $_POST["title"];
    $strDescription = $_POST["description"];
    $strPickupLocation = $_POST["pickupLocation"];
    $strRemarks = $_POST["remarks"];

    // File upload JPG/PNG
    $strDocFilePath = processDocFileUpload();

    if (empty($strDonorName)) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Full Name is required"]]);
    } else if (empty($dtmDate)) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Date is required"]]);
    } else if (empty($strTitle)) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Title is required"]]);
    } else if (empty($strDescription)) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Description is required"]]);
    } else if (empty($strPickupLocation)) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Pickup Location is required"]]);
    } else {
        $query = $conn->prepare("INSERT INTO tbldonationmanagement (strDonorName, dtmDate, strTitle, strDescription, strPickupLocation, strDocFilePath, strRemarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("sssssss"
            ,$strDonorName
            ,$dtmDate
            ,$strTitle
            ,$strDescription
            ,$strPickupLocation
            ,$strDocFilePath
            ,$strRemarks
        );

        if ($query->execute()) {
            // if ($query->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["data" => ["message" => "Donation submitted successfully" ]]);
            // } else {
                // http_response_code(202);
                // echo json_encode(array("data" => array("message" => "No rows were affected")));
            // }
        } else {
            http_response_code(500);
            echo json_encode(["data" => ["message" => "Internal server error, ".$query->error]]);
        }
        
        $query->close();
    }

    $conn->close();
}
?>