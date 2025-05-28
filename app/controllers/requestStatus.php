<?php
include "../config/db_connection.php";
include "../functions/requestStatus.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["reqId"])) {
    $reqId = intval($_GET["reqId"]);
    getRequestDate($conn, $reqId);
    $conn->close();
}
?>