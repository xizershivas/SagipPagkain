<?php
include "../config/db_connection.php";
include "../functions/inventoryManagement.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" 
    && isset($_GET["filter"]) 
    && (!isset($_GET["search"]) || empty($_GET["search"]))
    && (!isset($_GET["page"]) || empty($_GET["page"]))
) {
    $filter = sanitize($_GET["filter"]);
    getDataListOptions($conn, $filter);
    $conn->close();
}
else if ($_SERVER["REQUEST_METHOD"] == "GET" 
    && isset($_GET["filter"]) 
    && (isset($_GET["search"]) && !empty($_GET["search"]))
) {
    $filter = sanitize($_GET["filter"]);
    $search = sanitize($_GET["search"]);
    getDataListOptions($conn, $filter, $search);
    $conn->close();
}
else if ($_SERVER["REQUEST_METHOD"] == "GET" 
    && isset($_GET["filter"])
    && isset($_GET["search"])
    && (isset($_GET["page"]) && !empty($_GET["page"]))
    && (isset($_GET["limit"]) && !empty($_GET["limit"]))
) {
    $filter = sanitize($_GET["filter"]);
    $search = sanitize($_GET["search"]);
    $page = intval($_GET["page"]);
    $limit = intval($_GET["limit"]);
    getDataListOptions($conn, $filter, $search, $page, $limit);
    $conn->close();
}
?>