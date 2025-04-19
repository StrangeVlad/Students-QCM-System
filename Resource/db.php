<?php

$localhost = "localhost";
$user = "root";
$password = "";
$db = "db_test";

$conn = mysqli_connect($localhost, $user, $password, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
