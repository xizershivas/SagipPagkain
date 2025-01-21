<?php
require "src/config/db_connection.php";

function getUserData($conn) {
    $query = $conn->query("SELECT COUNT(*) AS 'user_count' FROM tbluser");
    $row = $query->fetch_object();
    $user_count = $row->user_count;

    $GLOBALS['allUserData'] = $conn->query("SELECT * FROM tbluser");
}

getUserData($conn);
?>