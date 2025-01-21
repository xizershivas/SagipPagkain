<?php
function getUserData($conn) {
    $GLOBALS['allUserData'] = $conn->query("SELECT * FROM tbluser");
}

function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function sanitizeEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return $email;
}

function editUser($conn, $intUserId) {
    $query = $conn->prepare("SELECT * FROM tbluser WHERE intUserId = ?");
    $query->bind_param('i', $intUserId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "User does not exist";
    }
    
    return $result->fetch_object();
}

function updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnEnabled, $ysnApproved) {
    $query = $conn->prepare("UPDATE tbluser SET strEmail = ?, ysnEnabled = ?, ysnApproved = ? WHERE intUserId = ? AND strUsername = ?");
    $query->bind_param("siiis", $strEmail, $ysnEnabled, $ysnApproved, $intUserId, $strUsername);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $data = [
                "userId" => $intUserId,
                "username" => $strUsername,
                "email" => $strEmail,
                "enabled" => $ysnEnabled,
                "approved" => $ysnApproved
            ];
            return $data;
        } else {
            return "Error: " . $query->error;
        }
    } else {
        return "Error updating user: " . $query->error;
    }
}

// Load Records into the Data Table
getUserData($conn);
?>