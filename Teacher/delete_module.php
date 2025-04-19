<?php
session_start();
require_once "../Resource/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $module_id = intval($_GET['id']);

  $sql = "DELETE FROM modules WHERE module_id = ?";
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $module_id);

    if (mysqli_stmt_execute($stmt)) {
      header("location: index.php");
      exit();
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }

    mysqli_stmt_close($stmt);
  }
} else {
  header("location: index.php");
  exit();
}
