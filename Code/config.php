<?php
//========== Database Connection ==========

$servername = "localhost";
$user = "mytax1";
$password = "9hwgixps7Xr*";
$dbname = "mytax1";

// Create connection
$link = new mysqli($servername, $user, $password, $dbname);
// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
?>