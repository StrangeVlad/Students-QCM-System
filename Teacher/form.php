<?php
session_start();

require_once "../Resource/db.php";

$name = $description = "";
$time_limit = 30;
$passing_score = 70;
$edit_mode = false;
$module_id = 0;

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $module_id = intval($_GET['id']);
  $edit_mode = true;

  $sql = "SELECT * FROM modules WHERE module_id = ?";
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $module_id);

    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $name = $row['name'];
        $description = $row['description'];
        $time_limit = $row['time_limit'];
        $passing_score = $row['passing_score'];
      } else {
        echo "No module found with that ID.";
        exit();
      }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
      exit();
    }

    mysqli_stmt_close($stmt);
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]);
  $description = trim($_POST["description"]);
  $time_limit = intval($_POST["time_limit"]);
  $passing_score = intval($_POST["passing_score"]);

  if ($edit_mode) {
    $sql = "UPDATE modules SET name = ?, description = ?, time_limit = ?, passing_score = ? WHERE module_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "ssiis", $name, $description, $time_limit, $passing_score, $module_id);
    }
  } else {
    $sql = "INSERT INTO modules (name, description, time_limit, passing_score) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "ssii", $name, $description, $time_limit, $passing_score);
    }
  }

  if (isset($stmt)) {
    if (mysqli_stmt_execute($stmt)) {
      if (!$edit_mode) {
        $module_id = mysqli_insert_id($conn);
      }

      header("location: questions.php?module_id=" . $module_id);
      exit();
    } else {
      echo "Something went wrong. Please try again later.";
    }

    mysqli_stmt_close($stmt);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $edit_mode ? 'Edit' : 'Create'; ?> Module - EduQuiz Pro</title>
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
            <?php echo $edit_mode ? 'Edit Module' : 'Create New Module'; ?>
          </h1>
          <p class="page-subtitle"><?php echo $edit_mode ? 'Update the module details below' : 'Fill in the details to create a new module'; ?></p>
        </div>
      </div>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($edit_mode ? "?id=$module_id" : "")); ?>" method="post">
        <div class="form-group">
          <label class="form-label" for="name">Module Name</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
          <p class="help-text">Choose a clear, descriptive name for your module</p>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
          <p class="help-text">Briefly describe what this module covers</p>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="time_limit">Time Limit (minutes)</label>
            <input type="number" class="form-control" id="time_limit" name="time_limit" value="<?php echo $time_limit; ?>" min="1" required>
            <p class="help-text">How long students have to complete the quiz</p>
          </div>

          <div class="form-group">
            <label class="form-label" for="passing_score">Passing Score (%)</label>
            <input type="number" class="form-control" id="passing_score" name="passing_score" value="<?php echo $passing_score; ?>" min="0" max="100" required>
            <p class="help-text">Minimum percentage required to pass</p>
          </div>
        </div>

        <div class="action-buttons">
          <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas <?php echo $edit_mode ? 'fa-save' : 'fa-plus'; ?>"></i>
            <?php echo $edit_mode ? 'Update Module' : 'Create Module'; ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>