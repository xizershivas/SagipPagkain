<?php
include "../config/db_connection.php";
include "../functions/trackDonation.php";

if (isset($_GET["user"]) && !empty($_GET["user"]) 
&& isset($_GET["foodBank"]) && !empty($_GET["foodBank"]) 
&& isset($_GET["item"]) && !empty($_GET["item"]) 
&& $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    $strFoodBank = $_GET["foodBank"];
    $intItemId = intval($_GET["item"]);
    getItemQuantity($conn, $intUserId, $strFoodBank, $intItemId);
    $conn->close();
} else if (isset($_GET["user"]) && !empty($_GET["user"]) 
&& isset($_GET["foodBank"]) && !empty($_GET["foodBank"]) 
&& $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    $strFoodBank = $_GET["foodBank"];
    getItem($conn, $intUserId, $strFoodBank);
    $conn->close();
} else if (isset($_GET["user"]) && !empty($_GET["user"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
    $intUserId = intval($_GET["user"]);
    getFoodBank($conn, $intUserId);
    $conn->close();
} else {
    loadData($conn);
    $conn->close();
}
?>