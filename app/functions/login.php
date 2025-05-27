<?php
include "../utils/sanitize.php";
include "../utils/closeResource.php";

function login($conn, $userData) {
    $query = $conn->prepare("SELECT * FROM tbluser WHERE strUsername = ? AND ysnActive = 1");
    $username = sanitize($userData->username);
    $password = sanitize($userData->password);
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $responseData = [];

    if ($result->num_rows == 1) {
        $user = $result->fetch_object();
        
        // Validate Password
        $strSalt = $user->strSalt;
        if (crypt($password, $strSalt) != $user->strPassword) {
            $responseData = ["data" => ["message" => "Invalid Username/Password", "success" => false]];
        } else {
            $responseData = ["data" => [
                "intUserId" => $user->intUserId,
                "strUsername" => $user->strUsername,
                "ysnActive" => $user->ysnActive,
                "ysnAdmin" => $user->ysnAdmin,
                "ysnDonor" => $user->ysnDonor,
                "ysnPartner" => $user->ysnPartner,
                "ysnBeneficiary" => $user->ysnBeneficiary,
                "success" => true
            ]];
        }
    } else {
        $responseData = ["data" => ["message" => "Invalid Username/Password", "success" => false]];
    }

    closeResource($conn, $query);

    return $responseData;
}

?>