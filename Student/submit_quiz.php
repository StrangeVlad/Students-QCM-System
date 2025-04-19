<?php
session_start();

require_once "../Resource/db.php";

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['module_id']) || !isset($data['answers']) || !isset($data['score'])) {
  echo json_encode(['error' => 'Invalid data']);
  exit;
}

$module_id = $data['module_id'];
$score = $data['score'];
$userAnswers = $data['answers'];

$moduleQuery = mysqli_query($conn, "SELECT * FROM modules WHERE module_id = $module_id");
$module = mysqli_fetch_assoc($moduleQuery);

if (!$module) {
  echo json_encode(['error' => 'Module not found']);
  exit;
}

$checkAttemptQuery = mysqli_query($conn, "SELECT * FROM user_module_attempts WHERE user_id = $user_id AND module_id = $module_id");
$existingAttempt = mysqli_fetch_assoc($checkAttemptQuery);

if ($existingAttempt) {
  echo json_encode([
    'error' => 'Already attempted',
    'passed' => $existingAttempt['passed_status'] === 'passed',
    'message' => $existingAttempt['passed_status'] === 'passed' ?
      'You have already passed this module.' :
      'You have already attempted this module and did not pass.'
  ]);
  exit;
}

$passed = $score >= $module['passing_score'];
$passedStatus = $passed ? 'passed' : 'failed';

$insertAttemptQuery = "INSERT INTO user_module_attempts (user_id, module_id, score, passed_status, attempt_date) 
                      VALUES ($user_id, $module_id, $score, '$passedStatus', NOW())";

if (!mysqli_query($conn, $insertAttemptQuery)) {
  echo json_encode(['error' => 'Database error: ' . mysqli_error($conn)]);
  exit;
}

foreach ($userAnswers as $questionId => $answerId) {
  $insertAnswerQuery = "INSERT INTO user_answers (user_id, module_id, question_id, answer_id) 
                         VALUES ($user_id, $module_id, $questionId, $answerId)";
  mysqli_query($conn, $insertAnswerQuery);
}

echo json_encode([
  'success' => true,
  'passed' => $passed,
  'message' => $passed ?
    'Congratulations! You passed the test.' :
    'You did not pass the test. Required: ' . $module['passing_score'] . '%. Keep practicing!'
]);
