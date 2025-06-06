<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  </style>
</head>

<body>
  <?php include_once "Login_valid.php"; ?>

  <div class="login-container">
    <div class="login-left">
      <div class="icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
        </svg>
      </div>
      <h2>Welcome Back!</h2>
      <p>Login to access your teacher account and manage your classroom activities with ease.</p>
    </div>

    <div class="login-right">
      <div class="login-header">
        <h2>Login</h2>
        <p>Enter your credentials to access your account</p>
      </div>

      <form action="Login.php" method="post" id="RegisterForm">
        <div class="form-group">
          <input type="text" class="input" name="username" id="username" required autocomplete="off" placeholder=" ">
          <label for="username">Username</label>
          <?php echo "<div class='error'>$userLogin</div>" ?>
        </div>

        <div class="form-group">
          <input type="password" class="input" name="password" id="password" required autocomplete="off" placeholder=" ">
          <label for="password">Password</label>
          <?php echo "<div class='error'>$passLogin</div>" ?>
        </div>

        <button type="submit" class="btn-login" name="btn">Login</button>
      </form>
    </div>
  </div>

  <script src="../../assets/js/reset_form.js"></script>
  <script src="login_valid.js"></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="../../assets/js/scrollreveal.js"></script>
</body>

</html>