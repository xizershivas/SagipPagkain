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
    $intBeneficiaryId = isset($_POST["beneficiaryId"]) ? $_POST["beneficiaryId"] : "";
    $strName = sanitize($_POST["name"]);
    $strEmail = sanitize($_POST["email"]);
    $strContact = sanitize($_POST["contact"]);
    $strAddress = sanitize($_POST["address"]);

    if (empty($intBeneficiaryId)) {
        $query = $conn->prepare("INSERT INTO tblbeneficiary (strName, strEmail, strContact, strAddress) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $strName, $strEmail, $strContact, $strAddress );

        if ($query->execute()) {
            http_response_code(200);
            echo json_encode(["data" => ["message" => "Successfully added new Beneficiary"]]);
        } else {
            http_response_code(500);
            echo json_encode(["data" => ["message" => "An error has occurred on the server, cannot process request."]]);
        }

        $query->close();
        $conn->close();
    } else {
        $query = $conn->prepare("UPDATE tblbeneficiary SET strName = ?, strEmail = ?, strContact = ?, strAddress = ? WHERE intBeneficiaryId = ?");
        $query->bind_param("ssssi"
            ,$strName
            ,$strEmail
            ,$strContact
            ,$strAddress
            ,$intBeneficiaryId
        );

        if ($query->execute()) {
            if ($query->affected_rows > 0) {
                $data = array(
                    "beneficiaryId" => $intBeneficiaryId,
                    "name" => $strName,
                    "email" => $strEmail,
                    "contact" => $strContact,
                    "address" => $strAddress,
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

    exit();
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