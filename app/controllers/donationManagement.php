<?php
include "../config/db_connection.php";
include "../functions/donationManagement.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intDonationId = intval(sanitize($_GET["intDonationId"]));
    editDonation($conn, $intDonationId);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intDonationId = $_POST["donationId"];
    $strDonorName = $_POST["donor"];
    $dtmDate = $_POST["date"];
    $strTitle = $_POST["title"];
    $strDescription = $_POST["description"];
    $strPickupLocation = $_POST["pickupLocation"];
    $strRemarks = $_POST["remarks"];
    $ysnStatus = isset($_POST["transportStatus"]) ? 1 : 0;

    // File upload PDF/Word
    $strDocFilePath = processDocFileUpload();

    $query = $conn->prepare("UPDATE tbldonationmanagement SET strDonorName = ?, dtmDate = ?, strTitle = ?, strDescription = ?, strPickupLocation = ?, strDocFilePath = ?, strRemarks = ?, ysnStatus = ? WHERE intDonationId = ?");
    $query->bind_param("sssssssii"
        ,$strDonorName
        ,$dtmDate
        ,$strTitle
        ,$strDescription
        ,$strPickupLocation
        ,$strDocFilePath
        ,$strRemarks
        ,$ysnStatus
        ,$intDonationId
    );

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $data = array(
                "donationId" => $intDonationId,
                "donor" => $strDonorName,
                "date" => $dtmDate,
                "title" => $strTitle,
                "description" => $strDescription,
                "pickupLocation" => $strPickupLocation,
                "remarks" => $strRemarks,
                "transportStatus" => $ysnStatus
            );

            http_response_code(200);
            echo json_encode(["data" => $data]);
        } else {
            http_response_code(202);
            echo json_encode(array("data" => array("message" => "No rows were affected")));
        }
    } else {
        http_response_code(500);
        echo json_encode(["data" => ["message" => $query->error]]);
    }

    $query->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Get the RAW DELETE data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into an object
    $data = json_decode($inputData);

    deleteDonation($conn, $data->intDonationId);

    $conn->close();
}
?>