<?php
include "../utils/sanitize.php";
include "../utils/closeResource.php";

function login($conn, $userData) {
    $query = $conn->prepare("SELECT * FROM tbluser WHERE strUsername = ?");
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
            $responseData = array("data" => array("message" => "Invalid Username/Password", "success" => false));
        } else {
            $responseData = array("data" => array(
                "intUserId" => $user->intUserId,
                "strUsername" => $user->strUsername,
                "ysnAdmin" => $user->ysnAdmin,
                "ysnDonor" => $user->ysnDonor,
                "ysnNgo" => $user->ysnNgo,
                "ysnOther" => $user->ysnOther,
                "success" => true
            ));
        }
    } else {
        $responseData = array("data" => array("message" => "Invalid Username/Password", "success" => false));
    }

    closeResource($conn, $query);

    return $responseData;
}

?>