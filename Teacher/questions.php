<?php
session_start();

require_once "../Resource/db.php";

if (!isset($_GET['module_id']) || empty($_GET['module_id'])) {
  header("location: index.php");
  exit();
}

$module_id = intval($_GET['module_id']);

$module = null;
$questions = null;

$sql = "SELECT * FROM modules WHERE module_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $module_id);

  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
      $module = mysqli_fetch_assoc($result);

      $questions = mysqli_query($conn, "SELECT * FROM questions WHERE module_id = $module_id ORDER BY question_id");
    } else {
      echo "No module found with that ID.";
      exit();
    }
  } else {
    echo "Oops! Something went wrong. Please try again later.";
    exit();
  }

  mysqli_stmt_close($stmt);
} else {
  echo "Database error. Please try again later.";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($module['name']) ? 'Questions for ' . htmlspecialchars($module['name']) : 'Module Questions'; ?> - EduQuiz Pro</title>
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
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 25px;
      margin-bottom: 30px;
    }

    .module-header {
      background: var(--bg-gradient);
      border-radius: var(--border-radius);
      padding: 30px;
      color: white;
      box-shadow: var(--shadow);
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
    }

    .module-header::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
      transform: translate(30%, -30%);
      border-radius: 50%;
      pointer-events: none;
    }

    .module-title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .module-description {
      margin-bottom: 20px;
      font-weight: 300;
      font-size: 16px;
      opacity: 0.9;
    }

    .module-meta {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .meta-badge {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50px;
      padding: 8px 15px;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn {
      border-radius: 50px;
      padding: 10px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
    }

    .btn-primary:hover {
      background: var(--secondary);
      border-color: var(--secondary);
    }

    .btn-secondary {
      background: rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(0, 0, 0, 0.05);
      color: #333;
    }

    .btn-secondary:hover {
      background: rgba(0, 0, 0, 0.15);
    }

    .btn-warning {
      background: var(--warning);
      border-color: var(--warning);
      color: white;
    }

    .btn-danger {
      background: var(--danger);
      border-color: var(--danger);
      color: white;
    }

    .actions-row {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 22px;
      font-weight: 600;
      margin: 0 0 20px 0;
      color: #333;
    }

    .questions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 20px;
    }

    .question-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 20px;
      border: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease;
    }

    .question-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .question-text {
      font-size: 16px;
      font-weight: 500;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .options-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 20px;
    }

    .option-item {
      padding: 12px 15px;
      background: #f5f7ff;
      border-radius: 8px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .option-correct {
      background: rgba(76, 175, 80, 0.1);
      border-left: 3px solid var(--success);
    }

    .option-correct i {
      color: var(--success);
    }

    .card-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }

    .empty-icon {
      font-size: 50px;
      color: #b4c0d4;
      margin-bottom: 20px;
    }

    .empty-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .empty-text {
      color: #6c757d;
      margin-bottom: 25px;
    }

    @media (max-width: 768px) {
      .main-container {
        padding: 15px;
      }

      .questions-grid {
        grid-template-columns: 1fr;
      }

      .module-meta {
        flex-direction: column;
        gap: 10px;
      }

      .actions-row {
        flex-direction: column;
      }

      .btn {
        width: 100%;
        justify-content: center;
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
    <?php if (isset($module) && is_array($module)): ?>
      <div class="module-header">
        <h1 class="module-title"><?php echo htmlspecialchars($module['name']); ?></h1>
        <p class="module-description"><?php echo htmlspecialchars($module['description']); ?></p>

        <div class="module-meta">
          <div class="meta-badge">
            <i class="fas fa-clock"></i>
            <span><?php echo $module['time_limit']; ?> minutes</span>
          </div>
          <div class="meta-badge">
            <i class="fas fa-trophy"></i>
            <span><?php echo $module['passing_score']; ?>% passing score</span>
          </div>
          <div class="meta-badge">
            <i class="fas fa-question-circle"></i>
            <span><?php echo isset($questions) ? mysqli_num_rows($questions) : 0; ?> questions</span>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="actions-row">
      <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Modules
      </a>
      <?php if (isset($module_id)): ?>
        <a href="question_form.php?module_id=<?php echo $module_id; ?>" class="btn btn-primary">
          <i class="fas fa-plus"></i> Add New Question
        </a>
      <?php endif; ?>
    </div>

    <h2 class="section-title">Questions</h2>

    <?php if (isset($questions) && mysqli_num_rows($questions) > 0): ?>
      <div class="questions-grid">
        <?php while ($question = mysqli_fetch_assoc($questions)): ?>
          <div class="question-card">
            <div class="question-text"><?php echo htmlspecialchars($question['question_text']); ?></div>

            <?php
            $options_query = mysqli_query($conn, "SELECT * FROM options WHERE question_id = {$question['question_id']} ORDER BY option_id");
            if (mysqli_num_rows($options_query) > 0):
            ?>
              <div class="options-list">
                <?php while ($option = mysqli_fetch_assoc($options_query)): ?>
                  <div class="option-item <?php echo $option['is_correct'] ? 'option-correct' : ''; ?>">
                    <?php if ($option['is_correct']): ?>
                      <i class="fas fa-check-circle"></i>
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($option['option_text']); ?></span>
                  </div>
                <?php endwhile; ?>
              </div>
            <?php endif; ?>

            <div class="card-actions">
              <?php if (isset($module_id)): ?>
                <a href="question_form.php?id=<?php echo $question['question_id']; ?>&module_id=<?php echo $module_id; ?>" class="btn btn-warning">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="delete_question.php?id=<?php echo $question['question_id']; ?>&module_id=<?php echo $module_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-question-circle"></i>
        </div>
        <h3 class="empty-title">No Questions Found</h3>
        <p class="empty-text">This module doesn't have any questions yet.</p>
        <?php if (isset($module_id)): ?>
          <a href="question_form.php?module_id=<?php echo $module_id; ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add First Question
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>