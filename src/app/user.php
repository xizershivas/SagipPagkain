<?php
require "src/config/db_connection.php";

$query = $conn->query("SELECT COUNT(*) AS 'user_count' FROM tbluser");
$row = $query->fetch_object();
$user_count = $row->user_count;

# TO DO
# FIX LIMIT and OFFSET for paging
# Show function
# Filter function
# Create User function

$result = $conn->query("SELECT * FROM tbluser");
?>