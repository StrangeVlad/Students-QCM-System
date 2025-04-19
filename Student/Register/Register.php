<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../../assets/img/logo.png">
  <title>Register - QCM System</title>
  <link rel="stylesheet" href="../../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .register-container {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      width: 900px;
      max-width: 100%;
      min-height: 550px;
      display: flex;
    }


    .register-left {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background: linear-gradient(to right, #4a6fa5, #166088);
      color: white;
    }

    .register-left-content {
      max-width: 500px;
      text-align: center;
    }

    .register-left img {
      width: 150px;
      margin-bottom: 30px;
    }

    .register-left h2 {
      margin-bottom: 20px;
      font-weight: 600;
    }

    .register-left p {
      line-height: 1.6;
    }

    .register-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }

    .register-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 40px;
      width: 100%;
      max-width: 450px;
    }

    .register-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .register-header h1 {
      color: #343a40;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .register-header p {
      color: #6c757d;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #343a40;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ced4da;
      border-radius: 6px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #4fc3f7;
      box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
      outline: none;
    }

    .error {
      color: #dc3545;
      font-size: 0.85rem;
      margin-top: 5px;
      height: 18px;
    }

    .btn-register {
      width: 100%;
      padding: 12px;
      background-color: #4a6fa5;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .btn-register:hover {
      background-color: #166088;
    }

    .register-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .register-footer a {
      color: #4a6fa5;
      text-decoration: none;
      font-weight: 500;
    }

    .register-footer a:hover {
      text-decoration: underline;
    }

    .password-strength {
      margin-top: 5px;
      font-size: 0.8rem;
      color: #6c757d;
    }

    @media (max-width: 992px) {
      .register-container {
        flex-direction: column;
      }

      .register-left {
        padding: 30px 20px;
      }

      .register-right {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <?php include_once "Register_valid.php"; ?>

  <div class="register-container">
    <div class="login-left">
      <div class="icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
        </svg>
      </div>
      <h2>Welcome</h2>
      <p>Login to access your QCM Modules Test. Be ready Before you Login</p>
    </div>

    <div class="register-right">
      <div class="register-header">
        <h1>Create Account</h1>
        <p>Fill in your details to register</p>

        <form action="Register.php" method="post" id="RegisterForm">
          <div class="form-group">
            <input type="text" class="form-control" name="full_name" id="full_name" autocomplete="off" autofocus autocapitalize="on" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
            <label for="full_name">Full Name</label>

            <?php echo "<div class='error'>$Fname</div>" ?>
          </div>

          <div class="form-group">
            <input type="email" class="form-control" name="email" id="email" required autocomplete="off" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <label for="email">Email Address</label>

            <?php echo "<div class='error'>$em</div>" ?>
          </div>

          <div class="form-group">
            <input type="password" class="form-control" name="password" id="password" required autocomplete="off">
            <label for="password">Password</label>

            <?php echo "<div class='error'>$pass</div>" ?>

            <div class="password-strength">Use at least 8 characters with a mix of letters and numbers</div>

          </div>

          <div class="form-group">
            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required autocomplete="off">
            <label for="confirmpassword">Confirm Password</label>

            <?php echo "<div class='error'>$cpass</div>" ?>
          </div>

          <button type="submit" name="btn" class="btn-register">Register</button>
        </form>

        <div class="register-footer">
          <p>Already have an account? <a href="../Login/Login.php">Log in here</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="../../assets/js/reset_form.js"></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="../../assets/js/scrollreveal.js"></script>
</body>

</html>