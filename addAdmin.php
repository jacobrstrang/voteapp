<?php
require "database.php";
$username = "admin";
$password = "admin";
$open = "Open";
$true = "TRUE";
$pwdhash = crypt($password);
$stmt = $mysqli->prepare("INSERT INTO poll (username, password, open, results) VALUES (?,?,?,?)");
$stmt->bind_param("iiii", $username, $pwdhash, $open, $true);
$stmt->execute();
$stmt->close();
?>