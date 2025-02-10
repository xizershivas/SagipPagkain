<?php
function getUserData($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE strUsername <> 'admin'");
    return $allUserData;
}

function editUser($conn, $intUserId) {
    if (!filter_var($intUserId, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Invalid request")));
        exit();
    } else {
        $query = $conn->prepare("SELECT * FROM tbluser WHERE intUserId = ?");

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

function updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnEnabled, $ysnApproved, $ysnAdmin, $ysnDonor, $ysnOther) {
    $query = $conn->prepare("UPDATE tbluser SET strEmail = ?, ysnEnabled = ?, ysnApproved = ?, ysnAdmin = ?, ysnDonor = ?, ysnOther = ? WHERE intUserId = ? AND strUsername = ?");
    $query->bind_param("siiiiiis", $strEmail, $ysnEnabled, $ysnApproved, $ysnAdmin, $ysnDonor, $ysnOther, $intUserId, $strUsername);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $data = array(
                "userId" => $intUserId,
                "username" => $strUsername,
                "email" => $strEmail,
                "enabled" => $ysnEnabled,
                "approved" => $ysnApproved,
                "admin" => $ysnAdmin,
                "donor" => $ysnDonor,
                "other" => $ysnOther
            );
            
            http_response_code(200);
            echo json_encode(array("data" => $data));
        } else {
            http_response_code(400);
            echo json_encode(array("data" => array("message" => "No rows were affected")));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => $query->error)));
    }

    $query->close();
    exit();
}

function deleteUser($conn, $intUserId) {
    $query = $conn->prepare("DELETE FROM tbluser WHERE intUserId = ?");
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