<?php
//Grant database access. Replace {username}, {password}, and {database} with the proper credentials.
$mysqli = new mysqli('localhost', '{username}', '{password}', '{database}');
if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

/*
    MySQL databases are as follows:
        choices(id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(200) NOT NULL)
        
        votes(id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT, number VARCHAR(15) NOT NULL, vote TINYINT(3) UNSIGNED)
        
        The id field is the primary key for both, and votes.vote is a foreign key to choices.id
 */
?>