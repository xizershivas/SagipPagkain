<?php
function getUserData($conn) {
    $allUserData = $conn->query("SELECT * FROM tbluser WHERE strUsername <> 'admin'");
    return $allUserData;
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

function updateUser($conn, $intUserId, $strUsername, $strEmail, $ysnEnabled, $ysnApproved, $ysnAdmin, $ysnDonor, $ysnOther) {
    $query = $conn->prepare("UPDATE tbluser SET strEmail = ?, ysnEnabled = ?, ysnApproved = ?, ysnAdmin = ?, ysnDonor = ?, ysnOther = ? WHERE intUserId = ? AND strUsername = ?");
    $query->bind_param("siiiiiis", $strEmail, $ysnEnabled, $ysnApproved, $ysnAdmin, $ysnDonor, $ysnOther, $intUserId, $strUsername);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $data = [
                "userId" => $intUserId,
                "username" => $strUsername,
                "email" => $strEmail,
                "enabled" => $ysnEnabled,
                "approved" => $ysnApproved,
                "admin" => $ysnAdmin,
                "donor" => $donor,
                "other" => $other
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
?>