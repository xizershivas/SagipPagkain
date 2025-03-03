<?php
function getUser($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tbluser WHERE intUserId = $intUserId");
    return $userData;
}
?>