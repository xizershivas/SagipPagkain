<?php
include "../utils/sanitize.php";
include "../utils/closeResource.php";

function login($conn, $userData) {
    $query = $conn->prepare("SELECT intUserId, strUsername, ysnAdmin FROM tbluser WHERE strUsername = ? AND strPassword = ?");
    $username = sanitize($userData->username);
    $password = sanitize($userData->password);
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();
    $responseData = [];

    if ($result->num_rows == 1) {
        $user = $result->fetch_object();
        $responseData = array("data" => array(
            "intUserId" => $user->intUserId,
            "strUsername" => $user->strUsername,
            "ysnAdmin" => $user->ysnAdmin,
            "success" => true
        ));
    } else {
        $responseData = array("data" => array("message" => "User does not exist", "success" => false));
    }

    closeResource($conn, $query);

    return $responseData;
}

?>