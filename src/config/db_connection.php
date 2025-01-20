<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'sagippagkaindb';

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed. Error: ' . $conn->connect_error);
}
?>