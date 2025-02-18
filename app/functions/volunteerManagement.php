<?php
function getVolunteerData($conn) {
    $allVolunteerData = $conn->query("SELECT * FROM tblvolunteer");
    return $allVolunteerData;
}
?>