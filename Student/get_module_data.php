<?php
header('Content-Type: application/json');
require_once "../Resource/db.php";

if (!isset($_GET['module_id']) || empty($_GET['module_id'])) {
  echo json_encode(['error' => 'Module ID is required']);
  exit();
}

$module_id = intval($_GET['module_id']);

$module_query = mysqli_query($conn, "SELECT * FROM modules WHERE module_id = $module_id");

if (mysqli_num_rows($module_query) == 0) {
  echo json_encode(['error' => 'Module not found']);
  exit();
}

$module = mysqli_fetch_assoc($module_query);

$questions_query = mysqli_query($conn, "SELECT * FROM questions WHERE module_id = $module_id ORDER BY question_id");
$questions = [];

while ($question = mysqli_fetch_assoc($questions_query)) {
  $options_query = mysqli_query($conn, "SELECT option_id, option_text, is_correct FROM options WHERE question_id = {$question['question_id']} ORDER BY option_id");
  $options = [];

  while ($option = mysqli_fetch_assoc($options_query)) {
    $options[] = [
      'option_id' => $option['option_id'],
      'option_text' => $option['option_text'],
      'is_correct' => (bool)$option['is_correct']
    ];
  }

  $questions[] = [
    'question_id' => $question['question_id'],
    'question_text' => $question['question_text'],
    'options' => $options
  ];
}

echo json_encode([
  'module' => $module,
  'questions' => $questions
]);
