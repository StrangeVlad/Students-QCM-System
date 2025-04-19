
<?php
session_start();
require_once "../Resource/db.php";

if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['module_id']) && !empty($_GET['module_id'])) {
  $question_id = intval($_GET['id']);
  $module_id = intval($_GET['module_id']);

  $sql = "DELETE FROM questions WHERE question_id = ?";
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $question_id);

    if (mysqli_stmt_execute($stmt)) {
      header("location: questions.php?module_id=$module_id");
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
?>