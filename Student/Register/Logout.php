<?php
include_once "../Resource/db.php";
session_unset();
session_destroy();
header("Location: ../Login/Login.php");
exit();
