<?php
session_start();

require_once "../Resource/db.php";

$sql = "SELECT * FROM modules ORDER BY name";
$modules = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduQuiz Pro - Teacher Portal</title>
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

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      margin-bottom: 20px;
      border-bottom: 1px solid #eaeaea;
    }

    .section-title {
      font-weight: 600;
      color: #333;
      margin: 0;
      font-size: 20px;
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

    .btn-success {
      background: var(--success);
      border-color: var(--success);
    }

    .btn-danger {
      background: var(--danger);
      border-color: var(--danger);
    }

    .action-buttons {
      display: flex;
      gap: 10px;
    }

    .modules-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    .modules-table thead th {
      background-color: #f8f9fa;
      color: #495057;
      font-weight: 600;
      padding: 15px;
      text-align: left;
      border-bottom: 2px solid #eaeaea;
    }

    .modules-table tbody td {
      padding: 15px;
      border-bottom: 1px solid #eaeaea;
      vertical-align: middle;
    }

    .modules-table tbody tr:hover {
      background-color: #f8faff;
    }

    .actions-toggle {
      background: none;
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .actions-toggle:hover {
      background-color: #eaeaea;
    }

    .actions-dropdown {
      position: absolute;
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      width: 180px;
      z-index: 100;
      overflow: hidden;
    }

    .actions-dropdown a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      color: #333;
      text-decoration: none;
      transition: background-color 0.2s;
    }

    .actions-dropdown a:hover {
      background-color: #f5f7ff;
    }

    .actions-dropdown .divider {
      height: 1px;
      background-color: #eaeaea;
      margin: 4px 0;
    }

    .no-modules {
      text-align: center;
      padding: 40px 0;
      color: #6c757d;
    }

    .no-modules p {
      margin-bottom: 20px;
    }

    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 20px;
      display: flex;
      align-items: center;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 22px;
      color: white;
    }

    .modules-icon {
      background-color: var(--primary);
    }

    .questions-icon {
      background-color: var(--warning);
    }

    .users-icon {
      background-color: var(--success);
    }

    .stat-content h3 {
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }

    .stat-content p {
      color: #6c757d;
      margin: 0;
      font-size: 14px;
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
      <div class="action-buttons">
        <a href="Login/Logout.php" class="btn btn-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="main-container">
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon modules-icon">
          <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
          <h3><?php echo mysqli_num_rows($modules); ?></h3>
          <p>Total Modules</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon questions-icon">
          <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
          <h3>--</h3>
          <p>Total Questions</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon users-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
          <h3>--</h3>
          <p>Active Students</p>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="section-title">Manage Modules</h2>
        <a href="form.php" class="btn btn-primary">
          <i class="fas fa-plus"></i> Create New Module
        </a>
      </div>

      <?php if (mysqli_num_rows($modules) > 0): ?>
        <table class="modules-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Time Limit (min)</th>
              <th>Passing Score (%)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($modules)): ?>
              <tr>
                <td><?php echo $row['module_id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo $row['time_limit']; ?></td>
                <td><?php echo $row['passing_score']; ?></td>
                <td>
                  <div class="actions-menu">
                    <button class="actions-toggle" data-module-id="<?php echo $row['module_id']; ?>">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="no-modules">
          <p>No modules found. Please add a new module.</p>
          <a href="form.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Your First Module
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div id="dropdown-container"></div>
  <script>
    const dropdownContainer = document.getElementById('dropdown-container');
    let currentDropdown = null;

    document.querySelectorAll('.actions-toggle').forEach(button => {
      button.addEventListener('click', function(e) {
        e.stopPropagation();

        if (currentDropdown) {
          dropdownContainer.removeChild(currentDropdown);
          currentDropdown = null;
        }

        const moduleId = this.dataset.moduleId;
        const dropdown = document.createElement('div');
        dropdown.className = 'actions-dropdown active';
        dropdown.innerHTML = `
        <a href="questions.php?module_id=${moduleId}">
          <i class="fas fa-question-circle"></i> Questions
        </a>
        <a href="form.php?id=${moduleId}">
          <i class="fas fa-edit"></i> Edit
        </a>
        <div class="divider"></div>
        <a href="delete_module.php?id=${moduleId}" 
           onclick="return confirm('Are you sure you want to delete this module?')"
           style="color: var(--danger);">
          <i class="fas fa-trash-alt"></i> Delete
        </a>
      `;

        const rect = this.getBoundingClientRect();
        dropdown.style.left = `${rect.right - 180}px`;
        dropdown.style.top = `${rect.bottom + window.scrollY + 5}px`;

        dropdownContainer.appendChild(dropdown);
        currentDropdown = dropdown;
      });
    });

    document.addEventListener('click', function(e) {
      if (currentDropdown && !currentDropdown.contains(e.target) &&
        !e.target.closest('.actions-toggle')) {
        dropdownContainer.removeChild(currentDropdown);
        currentDropdown = null;
      }
    });
  </script>
</body>

</html>