<?php
include "../config/db_connection.php";
include "../functions/donationManagement.php";
include "../utils/sanitize.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $intDonationId = intval(sanitize($_GET["intDonationId"]));
    editDonation($conn, $intDonationId);

    $conn->close();
}
?>