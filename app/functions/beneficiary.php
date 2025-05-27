<?php
function getUserData($conn, $intUserId) {
    $userData = $conn->query("SELECT * FROM tblbeneficiary WHERE intUserId = $intUserId");
    return $userData;
}

function getAllInventoryItems($conn) {
    $sql = "SELECT IV.intInventoryId 
            ,IV.intQuantity
            ,D.intDonationId
            ,FB.intFoodBankId
            ,FB.strFoodBank
            ,I.intItemId
            ,I.strItem
            ,C.intCategoryId
            ,C.strCategory
            ,U.intUnitId
            ,U.strUnit
            FROM tblinventory IV
            INNER JOIN tbldonationmanagement D ON IV.intDonationId = D.intDonationId
            INNER JOIN tblfoodbank FB ON IV.intFoodBankId = FB.intFoodBankId
            INNER JOIN tblitem I ON IV.intItemId = I.intItemId
            INNER JOIN tblcategory C ON IV.intCategoryId = C.intCategoryId
            INNER JOIN tblunit U ON IV.intUnitId = U.intUnitId";

    $allInventoryData = $conn->query($sql);
    return $allInventoryData;
}

?>