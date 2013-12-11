<?php
//Grant database access. Replace {username}, {password}, and {database} with the proper credentials.
$mysqli = new mysqli('localhost', '{username}', '{password}', '{database}');
if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>