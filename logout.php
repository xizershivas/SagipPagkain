<?php
session_start();
session_unset();
session_destroy();

// Wait for 3 seconds before redirecting
sleep(3);

header("Location: index.php");
?>