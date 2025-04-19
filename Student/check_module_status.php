<?php
session_start();

require_once "../Resource/db.php";

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['module_id'])) {
  echo json_encode(['error' => 'Module ID not provided']);
  exit;
}

$module_id = $_GET['module_id'];

$checkAttemptQuery = mysqli_query($conn, "SELECT * FROM user_module_attempts WHERE user_id = $user_id AND module_id = $module_id");
$existingAttempt = mysqli_fetch_assoc($checkAttemptQuery);

if ($existingAttempt) {
  echo json_encode([
    'already_attempted' => true,
    'passed' => $existingAttempt['passed_status'] === 'passed',
    'message' => $existingAttempt['passed_status'] === 'passed' ?
      'You have already passed this module.' :
      'You have already attempted this module and did not pass.'
  ]);
} else {
  echo json_encode(['already_attempted' => false]);
}
