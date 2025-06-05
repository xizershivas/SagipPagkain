<?php
function getBeneficiaryData($conn) {
    $allBeneficiaryData = $conn->query("SELECT * FROM tblbeneficiary");
    return $allBeneficiaryData;
}

function updateBeneficiary($conn, $userData) {
    header("Content-Type: application/json");

    $intBeneficiaryId = $userData["intBeneficiaryId"];
    $strName = $userData["strName"];
    $strEmail = $userData["strEmail"];
    $strContact = $userData["strContact"];
    $strAddress = $userData["strAddress"];
    $dblSalary = $userData["dblSalary"];

    $conn->begin_transaction();

    try {
        if (!$intBeneficiaryId) {
            $query = $conn->prepare("INSERT INTO tblbeneficiary (strName, strEmail, strContact, strAddress, dblSalary) VALUES (?, ?, ?, ?, ?)");

            if (!$query) {
                throw new Exception("Database insert operation failed", 400);
            }

            $query->bind_param("ssssd", $strName, $strEmail, $strContact, $strAddress, $dblSalary);

            if ($query->execute()) {
                http_response_code(200);
                echo json_encode(["data" => ["message" => "Successfully added new Beneficiary"]]);
            } else {
                throw new Exception("An error has occurred on the server, cannot process request.", 500);
            }

            $query->close();
        } else {
            $query = $conn->prepare("UPDATE tblbeneficiary SET strName = ?, strEmail = ?, strContact = ?, strAddress = ?, dblSalary = ? WHERE intBeneficiaryId = ?");
            $query->bind_param("ssssdi"
                ,$strName
                ,$strEmail
                ,$strContact
                ,$strAddress
                ,$dblSalary
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
                        "salary" => $dblSalary,
                        "message" => "User updated successfully"
                    );

                    http_response_code(200);
                    echo json_encode(["data" => $data]);
                } else {
                    http_response_code(202);
                    echo json_encode(["data" => ["message" => "No rows were affected"]]);
                }
            } else {
                throw new Exception("Database update operation failed" . $query->error);
            }

            $query->close();
        }

        $conn->commit();
    } catch (Exception $ex) {
        $conn->rollback();
        $code = $ex-getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function editBeneficiary($conn, $intBeneficiaryId) {
    if (!filter_var($intBeneficiaryId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $query = $conn->prepare("SELECT * FROM tblbeneficiary WHERE intBeneficiaryId = ?");

        if (!$query) {
            http_response_code(500);
            echo json_encode(array("data" => array("message" => "Database operation failed")));
            exit();
        }

        $query->bind_param("i", $intBeneficiaryId);
        $query->execute();
        $result = $query->get_result();
    
        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(array("data" => array("message" => "Record does not exist")));
            $query->close();
            exit();
        }
    
        $data = $result->fetch_object();

        http_response_code(200);
        echo json_encode(array("data" => $data));
        $query->close();
    }
}

function deleteBeneficiary($conn, $intBeneficiaryId) {
    $query = $conn->prepare("DELETE FROM tblbeneficiary WHERE intBeneficiaryId = ?");
    $query->bind_param("i", $intBeneficiaryId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(array("data" => array("message" => "Beneficiary was deleted successfully")));
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}
?>