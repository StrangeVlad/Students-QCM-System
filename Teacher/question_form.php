<?php
session_start();

require_once "../Resource/db.php";

if (!isset($_GET['module_id']) || empty($_GET['module_id'])) {
  header("location: main.php");
  exit();
}

$module_id = intval($_GET['module_id']);

$question_text = "";
$options = array(
  array("text" => "", "is_correct" => false),
  array("text" => "", "is_correct" => false),
  array("text" => "", "is_correct" => false),
  array("text" => "", "is_correct" => false)
);
$edit_mode = false;
$question_id = 0;

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $question_id = intval($_GET['id']);
  $edit_mode = true;

  $sql = "SELECT * FROM questions WHERE question_id = ?";
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $question_id);

    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $question_text = $row['question_text'];
        $module_id = $row['module_id'];

        $options_query = mysqli_query($conn, "SELECT * FROM options WHERE question_id = $question_id ORDER BY option_id");
        $options = array();

        while ($option = mysqli_fetch_assoc($options_query)) {
          $options[] = array(
            "id" => $option['option_id'],
            "text" => $option['option_text'],
            "is_correct" => $option['is_correct'] == 1
          );
        }

        while (count($options) < 4) {
          $options[] = array("text" => "", "is_correct" => false);
        }
      } else {
        echo "No question found with that ID.";
        exit();
      }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
      exit();
    }

    mysqli_stmt_close($stmt);
  }
}

$module_query = mysqli_query($conn, "SELECT name FROM modules WHERE module_id = $module_id");
$module = mysqli_fetch_assoc($module_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $question_text = trim($_POST["question_text"]);
  $option_texts = $_POST["option_text"];
  $correct_option = intval($_POST["correct_option"]);

  mysqli_begin_transaction($conn);

  try {
    if ($edit_mode) {
      mysqli_query($conn, "UPDATE questions SET question_text = '$question_text' WHERE question_id = $question_id");
    } else {
      mysqli_query($conn, "INSERT INTO questions (module_id, question_text) VALUES ($module_id, '$question_text')");
      $question_id = mysqli_insert_id($conn);
    }

    if ($edit_mode) {
      mysqli_query($conn, "DELETE FROM options WHERE question_id = $question_id");
    }

    for ($i = 0; $i < count($option_texts); $i++) {
      $option_text = trim($option_texts[$i]);
      $is_correct = ($i == $correct_option) ? 1 : 0;

      if (!empty($option_text)) {
        mysqli_query($conn, "INSERT INTO options (question_id, option_text, is_correct) VALUES ($question_id, '$option_text', $is_correct)");
      }
    }

    mysqli_commit($conn);
    header("location: questions.php?module_id=" . $module_id);
    exit();
  } catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $edit_mode ? 'Edit' : 'Create'; ?> Question - EduQuiz Pro</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4CAF50;
      --danger: #f72585;
      --warning: #ff9e00;
      --light: #f8f9fa;
      --dark: #212529;
      --bg-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --border-radius: 10px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7ff;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background: var(--bg-gradient);
      padding: 15px 30px;
      color: white;
      box-shadow: var(--shadow);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 24px;
      color: white;
      text-decoration: none;
    }

    .navbar-subtitle {
      font-size: 14px;
      opacity: 0.8;
    }

    .main-container {
      max-width: 800px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 30px;
      margin-bottom: 30px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 20px;
      margin-bottom: 25px;
      border-bottom: 1px solid #eaeaea;
    }

    .page-title {
      font-size: 24px;
      font-weight: 700;
      margin: 0;
      color: #333;
    }

    .page-subtitle {
      font-size: 14px;
      color: #6c757d;
      margin: 5px 0 0 0;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #dce1e9;
      border-radius: 8px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: border-color 0.2s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .btn {
      border-radius: 50px;
      padding: 12px 25px;
      font-weight: 500;
      transition: all 0.3s ease;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-success {
      background: var(--success);
      border-color: var(--success);
      color: white;
    }

    .btn-success:hover {
      background: #43a047;
      transform: translateY(-2px);
    }

    .btn-secondary {
      background: #e9ecef;
      border: 1px solid #dce1e9;
      color: #495057;
    }

    .btn-secondary:hover {
      background: #dfe3e8;
    }

    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 10px;
    }

    .card-icon {
      font-size: 24px;
      color: var(--primary);
      margin-right: 15px;
    }

    .help-text {
      font-size: 12px;
      color: #6c757d;
      margin-top: 5px;
    }

    .option-container {
      padding: 15px;
      border: 1px solid #dce1e9;
      border-radius: var(--border-radius);
      margin-bottom: 15px;
      transition: all 0.3s ease;
    }

    .option-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .option-number {
      background: var(--primary);
      color: white;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
      margin-right: 10px;
    }

    .radio-container {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .radio-container input[type="radio"] {
      margin-right: 10px;
    }

    .module-badge {
      background: var(--bg-gradient);
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .module-badge i {
      margin-right: 5px;
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
        gap: 0;
      }

      .action-buttons {
        flex-direction: column;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .card {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <div>
        <span class="navbar-brand">EduQuiz Pro</span>
        <div class="navbar-subtitle">Teacher Portal</div>
      </div>
    </div>
  </nav>

  <div class="main-container">
    <div class="card">
      <div class="card-header">
        <div>
          <h1 class="page-title">
            <i class="fas <?php echo $edit_mode ? 'fa-edit' : 'fa-plus-circle'; ?> card-icon"></i>
            <?php echo $edit_mode ? 'Edit Question' : 'Create New Question'; ?>
          </h1>
          <p class="page-subtitle"><?php echo $edit_mode ? 'Update the question details below' : 'Fill in the details to create a new question'; ?></p>
        </div>
      </div>

      <div class="module-badge">
        <i class="fas fa-book"></i> Module: <?php echo htmlspecialchars($module['name']); ?>
      </div>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?module_id=$module_id" . ($edit_mode ? "&id=$question_id" : "")); ?>" method="post">
        <div class="form-group">
          <label class="form-label" for="question_text">Question Text</label>
          <textarea class="form-control" id="question_text" name="question_text" rows="3" required><?php echo htmlspecialchars($question_text); ?></textarea>
          <p class="help-text">Enter your question clearly and concisely</p>
        </div>

        <div class="form-group">
          <label class="form-label">Answer Options</label>
          <p class="help-text">Provide 2-4 options and select the correct answer</p>

          <?php for ($i = 0; $i < 4; $i++) : ?>
            <div class="option-container">
              <div class="option-header">
                <div class="option-number"><?php echo $i + 1; ?></div>
                <div class="radio-container">
                  <input type="radio" id="correct_option_<?php echo $i; ?>" name="correct_option" value="<?php echo $i; ?>" <?php echo (isset($options[$i]['is_correct']) && $options[$i]['is_correct']) ? 'checked' : ''; ?> required>
                  <label for="correct_option_<?php echo $i; ?>">Correct Answer</label>
                </div>
              </div>
              <input type="text" class="form-control" name="option_text[]" value="<?php echo isset($options[$i]['text']) ? htmlspecialchars($options[$i]['text']) : ''; ?>" <?php echo $i < 2 ? 'required' : ''; ?>>
            </div>
          <?php endfor; ?>
        </div>

        <div class="action-buttons">
          <a href="questions.php?module_id=<?php echo $module_id; ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas <?php echo $edit_mode ? 'fa-save' : 'fa-plus'; ?>"></i>
            <?php echo $edit_mode ? 'Update Question' : 'Create Question'; ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>