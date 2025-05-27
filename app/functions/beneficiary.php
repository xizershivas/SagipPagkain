<?php
function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tblbeneficiary WHERE intUserId = $intUserId");
    return $userData;
}

?>