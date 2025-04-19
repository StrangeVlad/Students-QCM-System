<?php
session_start();

$userLogin = $passLogin = "";

include_once "../../Resource/db.php";

if (isset($_POST['btn'])) {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
      $userLogin = "Email is required";
    }

    if (empty($password)) {
      $passLogin = "Password is required";
    }

    if (empty($userLogin) && empty($passLogin)) {
      $sql = "SELECT * FROM users WHERE email = '$email'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
          if ($row['pass_status'] === 'not taken') {
            $_SESSION['user'] = $row['email'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: ../main.php");
            exit();
          } else {
            echo '<script>alert("You already took the test and You .' . $row['pass_status'] . '");</script>';
            echo "<script>alert('" . $row['result'] . "');</script>";
          }
        } else {
          $passLogin = "Incorrect password";
        }
      } else {
        $userLogin = "Email not found";
      }
    }
  }
}

$conn->close();
