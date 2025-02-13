<?php
function register($conn, $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $strConfirmPassword, $strAccountType, $strSpecifyOther) {
    // Check if User already exists
    $sql = $conn->prepare("SELECT strUsername FROM tbluser WHERE strUsername = ?");
    $sql->bind_param("s", $strUsername);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows >= 1) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "The Username already exists, please choose a different Username")));
        exit();
    }
    
    // Password pattern
    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/";

    // Check password match
    if ($strPassword != $strConfirmPassword) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Passwords do not match")));
        exit();
    } else if (strlen($strPassword) < 8) {
        http_response_code(400);
        echo json_encode(array("data" => array("message" => "Password must be 8 characters long")));
        exit();
    } else if (!preg_match($pattern, $strPassword)) {
        http_response_code(406);
        echo json_encode(array("data" => array("message" => "Password must contain at least 1 capital letter, 1 number and 1 special character")));
        exit();
    } else {
        $salt = bin2hex(random_bytes(22));
        $strPassword = crypt($strPassword, $salt);
    }

    $query;
    $stmt;
    $ysn = 1;

    switch ($strAccountType) {
        case "donor":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, ysnDonor) 
            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $ysn);
            break;
        case "ngo":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, ysnNgo) 
            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $ysn);
            break;
        case "other":
            $query = "INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, ysnOther, strSpecifyOther) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssis", $strUsername, $strFullName, $strContact, $strEmail, $strPassword, $ysn, $strSpecifyOther);
            break;
    }

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array(
            "data" => array(
                "message" => "Registration successful",
                "fullName" => $strFullName,
                "email" => $strEmail,
                "contact" => $strContact,
                "username" => $strUsername,
                "accountType" => $strAccountType,
                "specifyOther" => $strSpecifyOther,
                "success" => true
            )
        ));
    } else {
        http_response_code(500);
        echo json_encode(["data" => ["message" => $stmt->error]]);
    }

    $stmt->close();
    exit();
}
?>