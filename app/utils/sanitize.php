<?php
function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function sanitizeEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return $email;
}
?>