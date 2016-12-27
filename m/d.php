<?php

// SERVER CONNECTION

$server = '';
$user = '';
$pass = '';
$dbname = '';

$stream = mysqli_connect($server, $user, $pass);
mysqli_select_db($stream, $dbname);
mysqli_query($stream, "SET NAMES UTF8");

?>