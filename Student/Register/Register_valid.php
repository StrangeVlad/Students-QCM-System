<?php
include_once '../../Resource/db.php';

$em = $user = $pass = $Fname = $cpass = "";

if (isset($_POST["btn"])) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirmpassword']));

    if (empty($full_name)) {
      $Fname = "Full Name is required";
    }

    if (empty($email)) {
      $em = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $em = "Invalid email format";
    } else {
      // Check if email already exists
      $sql = "SELECT * FROM users WHERE email = '$email'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        $em = "Email already exists";
      }
    }

    if (empty($password)) {
      $pass = "Password is required";
    } elseif (strlen($password) < 6) {
      $pass = "Password must be at least 6 characters";
    }

    if (empty($confirm_password)) {
      $cpass = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
      $cpass = "Passwords do not match";
    }

    if (empty($Fname) && empty($em) && empty($pass) && empty($cpass)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
      if (mysqli_query($conn, $sql)) {
        echo "User registered successfully";
        header('Location: ../Login/Login.php');
        exit();
      } else {
        echo "User registration failed: " . mysqli_error($conn);
      }
    }
  }
}

mysqli_close($conn);
