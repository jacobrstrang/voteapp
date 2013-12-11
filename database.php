<?php
$mysqli = new mysqli('localhost', 'php', 'phpAccessPass', 'voting');

if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>