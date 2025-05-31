<?php
function getUserData($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE strUsername <> 'admin'");
    return $allUserData;
}
function getDonorDate($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE ysnDonor = 1");
    return $allUserData;
}
function addUser($conn, $userData) {
    header('Content-Type: application/json');

    $strFullName = $userData['strFullName'];
    $strEmail = $userData['strEmail'];
    $strContact = $userData['strContact'];
    $strUsername = $userData['strUsername'];
    $strPassword = $userData['strPassword'];
    $strConfirmPassword = $userData['strConfirmPassword'];
    $strAccountType = $userData['strAccountType'];
    $ysnStatus = $userData['ysnStatus'];

    // Check if User already exists
    $sql = $conn->prepare("SELECT strUsername FROM tbluser WHERE strUsername = ?");
    $sql->bind_param("s", $strUsername);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows >= 1) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "The Username already exists, please choose a different Username"]]);
        exit();
    }
    
    // Password pattern
    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/";
    $strSalt;

    // Check password match
    if ($strPassword != $strConfirmPassword) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Passwords do not match"]]);
        exit();
    } else if (strlen($strPassword) < 8) {
        http_response_code(400);
        echo json_encode(["data" => ["message" => "Password must be 8 characters long"]]);
        exit();
    } else if (!preg_match($pattern, $strPassword)) {
        http_response_code(406);
        echo json_encode(["data" => ["message" => "Password must contain at least 1 capital letter, 1 number and 1 special character"]]);
        exit();
    } else {
        $strSalt = bin2hex(random_bytes(22));
        $strPassword = crypt($strPassword, $strSalt);
    }

    $query;
    $stmt;
    $ysn = $ysnStatus;

    $conn->begin_transaction();

    try {
        switch ($strAccountType) {
        case "admin":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnAdmin) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        case "donor":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnDonor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        case "staff":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnStaff) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        case "partner":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, strSalt, ysnPartner) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strSalt, $ysn);
            break;
        }

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "data" => [
                    "message" => "User successfully created",
                    "fullName" => $strFullName,
                    "email" => $strEmail,
                    "contact" => $strContact,
                    "username" => $strUsername,
                    "accountType" => $strAccountType,
                    "success" => true
                ]
            ]);
        } else {
            throw new Exception($stmt->error);
        }

        $conn->commit();
    } catch (Exception $ex) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["data" => ["message" => "Failed to create new user. " . $ex->getMessage()]]);
    }

    exit();
}

function editUser($conn, $intUserId) {
    header('Content-Type: application/json');

    if (!filter_var($intUserId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $sql = "SELECT U.intUserId
                , U.strUsername
                , U.strFullName
                , U.strContact
                , U.strEmail
                , U.ysnActive
                , U.ysnAdmin
                , U.ysnDonor
                , U.ysnStaff
                , U.ysnPartner
                , U.ysnBeneficiary
                , B.intBeneficiaryId
                , B.strAddress
                , B.dblSalary
                , B.strDocument
                FROM tbluser U
                LEFT JOIN tblbeneficiary B
                    ON U.intUserId = B.intUserId
                WHERE U.intUserId = ?";

        $query = $conn->prepare($sql);

        if (!$query) {
            http_response_code(500);
            echo json_encode(array("data" => array("message" => "Database operation failed")));
            exit();
        }

        $query->bind_param('i', $intUserId);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(array("data" => array("message" => "User does not exist")));
            $query->close();
            exit();
        }

        $user = $result->fetch_object();

        http_response_code(200);
        echo json_encode(array("data" => $user));
        $query->close();
        exit();
    }
}

function updateUser($conn, $userData) {
    header("Content-Type: application/json");

    $intUserId = $userData["intUserId"];
    $strUsername = $userData["strUsername"];
    $strEmail = $userData["strEmail"];
    $strFullName = $userData["strFullName"];
    $strContact = $userData["strContact"];
    $strAddress = $userData["strAddress"];
    $dblSalary = $userData["dblSalary"];
    $ysnActive = $userData["ysnActive"];
    $ysnAdmin = $userData["ysnAdmin"];
    $ysnDonor = $userData["ysnDonor"];
    $ysnStaff = $userData["ysnStaff"];
    $ysnPartner = $userData["ysnPartner"];

    try
    {
        $sql = "UPDATE tbluser U 
                LEFT JOIN tblbeneficiary B
                    ON U.intUserId = B.intUserId
                SET U.strEmail = ?
                    , U.strFullName = ?
                    , U.strContact = ?
                    , B.strName = ?
                    , B.strEmail = ?
                    , B.strContact = ?
                    , B.strAddress = ?
                    , B.dblSalary = ?
                    , U.ysnActive = ?
                    , U.ysnAdmin = ?
                    , U.ysnDonor = ?
                    , U.ysnStaff = ?
                    , U.ysnPartner = ? 
                WHERE U.intUserId = ?";

        $query = $conn->prepare($sql);

        if (!$query) {
            throw new Exception("Database operation failed", 500);
        }

        $query->bind_param("sssssssdiiiiii", 
            $strEmail
            , $strFullName
            , $strContact
            , $strFullName
            , $strEmail
            , $strContact
            , $strAddress
            , $dblSalary
            , $ysnActive
            , $ysnAdmin
            , $ysnDonor
            , $ysnStaff
            , $ysnPartner
            , $intUserId
        );

        if ($query->execute()) {
            if ($query->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["data" => ["message" => "User details updated successfully", "user" => $userData]]);
            } else {
                http_response_code(202);
                echo json_encode(["data" => ["message" => "No rows were affected"]]);
            }
        } else {
            throw new Exception($query->error, 500);
        }

        $query->close();
    } catch (Exception $ex) {
        $code = $ex->getCode();
        http_response_code($code);
        echo json_encode(["data" => ["message" => $ex->getMessage()]]);
    }

    exit();
}

function deleteUser($conn, $intUserId) {
    header("Content-Type: application/json");

    $sql = "DELETE U, B
            FROM tbluser U
            LEFT JOIN tblbeneficiary B
                ON U.intUserId = B.intUserId
            WHERE U.intUserId = ?";

    $query = $conn->prepare($sql);

    if (!$query) {
        echo json_encode(["data" => ["message" => "Database operation failed"]]);
        exit();
    }

    $query->bind_param("i", $intUserId);

    if ($query->execute()) {
        http_response_code(200);
        echo json_encode(array("data" => array("message" => "User was successfully deleted")));
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}
?>