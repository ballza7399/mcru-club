<?php
$host = '157.85.96.163';
$user = 'cs';
$pass = 'cEBzc3cwcmQ=';
$dbname = 'mcru-club';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$conn->set_charset("utf8");
?>
