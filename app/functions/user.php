<?php
include "../config/db_connection.php";

function closeResource($conn, $query) {
    if (!$conn) $conn->close();
    if (!query) $query->close();
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

function getUserData($conn) {
    $GLOBALS['allUserData'] = $conn->query("SELECT * FROM tbluser WHERE strUsername <> 'admin'");
}

function editUser($conn, $intUserId) {
    $query = $conn->prepare("SELECT * FROM tbluser WHERE intUserId = ?");
    $query->bind_param('i', $intUserId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "User does not exist";
    }

    $user = $result->fetch_object();

    return $user;
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
            closeResource($conn, $query);
            return $data;
        } else {
            closeResource($conn, $query);
            return "Error: " . $query->error;
        }
    } else {
        closeResource($conn, $query);
        return "Error updating user: " . $query->error;
    }
}

function deleteUser($conn, $intUserId) {
    $query = $conn->prepare("DELETE FROM tbluser WHERE intUserId = ?");
    $query->bind_param("i", $intUserId);
    $result = $query->execute();

    if (!$result) {
        return "Error deleting user";
    }
    closeResource($conn, $query);
    return "User was successfully deleted";
}

// Load Records into the Data Table
getUserData($conn);
?>