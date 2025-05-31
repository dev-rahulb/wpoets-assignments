<?php
$host = 'localhost';
$db   = 'wpoets_test';//if0_39126348_wpoets_test
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>